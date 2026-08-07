#!/usr/bin/env bash
#
# Deploy the theme to the FTP host via curl batch upload.
# Usage: npm run deploy:ftp        (incremental — only files changed since the
#                                    last successful deploy)
#        npm run deploy:ftp:full  (force a full re-upload of every file)
#        bash scripts/deploy-ftp.sh [--full]
#
# Incremental mode diffs the local-only git tag `deployed` against HEAD to
# find what changed, uploads added/modified files, and FTP-deletes removed
# ones. The tag only moves forward once the upload AND all deletes succeed,
# so a failed run is safe to just re-run — it'll retry the same diff.
#
# build/ is rebuilt from scratch and re-uploaded in full on every run
# regardless of mode — it's .gitignore'd, so `git diff` never sees it, and
# source -> compiled-output isn't 1:1 (any partial change can affect the
# single style.min.css bundle), so partial build/ uploads aren't safe.
#
# Falls back to a full deploy automatically the first time (no `deployed`
# tag yet) or whenever --full is passed.
#
# Requires a clean working tree — this deploys what's committed, not
# whatever happens to be sitting in the working copy.
#
# Password is never stored in the repo — it is prompted for on each run
# (or read from the FTP_PASSWORD env var, e.g. for CI).

set -euo pipefail

FTP_HOST="smplfyd3.ftp.tools"
FTP_USER="smplfyd3_lionwood"
REMOTE_PATH="wp-content/themes/lionwood"
REMOTE_BASE="ftp://${FTP_HOST}/${REMOTE_PATH}"
DEPLOY_TAG="deployed"

# Paths to skip — dev tooling that has no business on the server, plus
# acf-json/, which must NEVER be touched by deploy (ACF field groups are
# managed manually through wp-admin; overwriting/creating JSON there causes
# ACF key conflicts and fatal errors on the live site.
EXCLUDES=(
    -not -path "./.git/*" -not -path "./.git"
    -not -path "./node_modules/*"
    -not -path "./.claude/*"
    -not -path "./acf-json/*"
)

# Same exclusion rules, applied to individual paths coming out of `git diff`
# instead of `find` — kept as a plain case match rather than git pathspec
# magic so it behaves identically to EXCLUDES above.
is_excluded() {
    case "$1" in
        .git/*|.git|node_modules/*|.claude/*|acf-json/*) return 0 ;;
        *) return 1 ;;
    esac
}

cd "$(dirname "$0")/.."
REPO_ROOT="$(pwd)"

FULL=false
[[ "${1:-}" == "--full" ]] && FULL=true

if [[ -n "$(git status --porcelain)" ]]; then
    echo "== Working tree has uncommitted changes — commit first, then deploy. =="
    git status --short
    exit 1
fi

echo "== Lint (warning only — does not block deploy) =="
npm run lint || echo "  ⚠ lint failed — continuing anyway, review before shipping further work"

echo "== Production build =="
npx cross-env NODE_ENV=production gulp clean
npx cross-env NODE_ENV=production gulp styles
npx cross-env NODE_ENV=production gulp scripts
npx cross-env NODE_ENV=production gulp copyFonts
npx cross-env NODE_ENV=production gulp generateFontsCSS

if [ -z "${FTP_PASSWORD:-}" ]; then
    read -r -s -p "FTP password for ${FTP_USER}@${FTP_HOST}: " FTP_PASSWORD
    echo
fi

SCRATCH="$(mktemp -d)"
trap 'rm -rf "$SCRATCH"' EXIT

ADDED_MODIFIED=()
DELETED=()

if $FULL || ! git rev-parse -q --verify "refs/tags/${DEPLOY_TAG}" >/dev/null; then
    $FULL || echo "== No previous '${DEPLOY_TAG}' tag found — doing a full deploy =="
    while IFS= read -r f; do
        ADDED_MODIFIED+=("$f")
    done < <(find . -type f "${EXCLUDES[@]}" | sed 's|^\./||')
else
    echo "== Incremental deploy: diffing ${DEPLOY_TAG}..HEAD =="
    while IFS=$'\t' read -r status f1 f2; do
        [ -z "$status" ] && continue
        case "$status" in
            A|M)
                is_excluded "$f1" || ADDED_MODIFIED+=("$f1")
                ;;
            D)
                is_excluded "$f1" || DELETED+=("$f1")
                ;;
            R*)
                is_excluded "$f1" || DELETED+=("$f1")
                is_excluded "$f2" || ADDED_MODIFIED+=("$f2")
                ;;
        esac
    done < <(git diff --name-status -M "${DEPLOY_TAG}" HEAD)

    # build/ is .gitignore'd — it never appears in `git diff` no matter what
    # changed, yet it's regenerated from scratch (gulp clean + rebuild) on
    # every run above. Re-uploading it in full every time, rather than trying
    # to map which src/ file maps to which build/ output (not 1:1 — any
    # partial under src/scss/partials/ affects the single style.min.css
    # bundle, for instance), is the only way to guarantee the server's
    # compiled assets can't drift out of sync with source that *did* diff.
    while IFS= read -r f; do
        ADDED_MODIFIED+=("$f")
    done < <(find build -type f | sed 's|^\./||')

    if [ ${#ADDED_MODIFIED[@]} -eq 0 ] && [ ${#DELETED[@]} -eq 0 ]; then
        echo "== Nothing changed since last deploy — nothing to do =="
        exit 0
    fi
fi

TOTAL=${#ADDED_MODIFIED[@]}

: > "$SCRATCH/upload.cfg"
for f in "${ADDED_MODIFIED[@]}"; do
    printf 'url = "%s/%s"\n' "$REMOTE_BASE" "$f" >> "$SCRATCH/upload.cfg"
    printf 'upload-file = "%s"\n' "$f" >> "$SCRATCH/upload.cfg"
done

echo "== Uploading ${TOTAL} file(s), deleting ${#DELETED[@]} file(s) on ${FTP_HOST}/${REMOTE_PATH} =="
echo "   Excluded: .git/, node_modules/, .claude/, acf-json/"
read -r -p "Proceed? [y/N] " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 1
fi

UPLOAD_OK=0
if [ "$TOTAL" -gt 0 ]; then
    curl -s --user "${FTP_USER}:${FTP_PASSWORD}" --ftp-create-dirs \
        -K "$SCRATCH/upload.cfg" -w "%{filename_effective} -> %{http_code}\n" \
        > "$SCRATCH/result.log" 2>&1
    UPLOAD_OK=$(grep -c "226$" "$SCRATCH/result.log" || true)
fi

# FTP has no bulk-delete verb curl can batch the way -K batches uploads, so
# each deleted file gets its own lightweight request: a pre-transfer `DELE`
# quote command, then a harmless directory listing of the theme root (not
# the file's own now-possibly-empty subdirectory) just so curl has a valid
# transfer to complete and report a code for.
DELETE_OK=0
DELETE_FAILED=()
for f in "${DELETED[@]}"; do
    if curl -s --user "${FTP_USER}:${FTP_PASSWORD}" \
         -Q "DELE ${REMOTE_PATH}/${f}" "${REMOTE_BASE}/" > /dev/null 2>&1; then
        DELETE_OK=$((DELETE_OK + 1))
    else
        DELETE_FAILED+=("$f")
    fi
done

echo "== Done: ${UPLOAD_OK}/${TOTAL} uploaded, ${DELETE_OK}/${#DELETED[@]} deleted =="

FAILED=false
if [ "$UPLOAD_OK" -ne "$TOTAL" ]; then
    FAILED=true
    echo "== Upload failures: =="
    grep -v "226$" "$SCRATCH/result.log" || true
fi
if [ ${#DELETE_FAILED[@]} -gt 0 ]; then
    FAILED=true
    echo "== Delete failures (already gone, or check permissions): =="
    printf '  %s\n' "${DELETE_FAILED[@]}"
fi

if $FAILED; then
    echo "== Not moving '${DEPLOY_TAG}' tag — re-run to retry the same diff. =="
    exit 1
fi

git tag -f "$DEPLOY_TAG" HEAD >/dev/null
echo "== Tagged '${DEPLOY_TAG}' at $(git rev-parse --short HEAD) =="
