#!/usr/bin/env bash
#
# Deploy the theme to the FTP host via curl batch upload.
# Usage: npm run deploy:ftp   (or: bash scripts/deploy-ftp.sh)
#
# Password is never stored in the repo — it is prompted for on each run
# (or read from the FTP_PASSWORD env var, e.g. for CI).

set -euo pipefail

FTP_HOST="smplfyd3.ftp.tools"
FTP_USER="smplfyd3_lionwood"
REMOTE_BASE="ftp://${FTP_HOST}/wp-content/themes/lionwood"

# Paths to skip — dev tooling that has no business on the server, plus
# acf-json/, which must NEVER be touched by deploy (ACF field groups are
# managed manually through wp-admin; overwriting/creating JSON there causes
# ACF key conflicts and fatal errors on the live site).
EXCLUDES=(
    -not -path "./.git/*" -not -path "./.git"
    -not -path "./node_modules/*"
    -not -path "./.claude/*"
    -not -path "./acf-json/*"
)

cd "$(dirname "$0")/.."
REPO_ROOT="$(pwd)"

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

find . -type f "${EXCLUDES[@]}" | sed 's|^\./||' > "$SCRATCH/filelist.txt"
TOTAL=$(wc -l < "$SCRATCH/filelist.txt")

: > "$SCRATCH/upload.cfg"
while IFS= read -r f; do
    printf 'url = "%s/%s"\n' "$REMOTE_BASE" "$f" >> "$SCRATCH/upload.cfg"
    printf 'upload-file = "%s"\n' "$f" >> "$SCRATCH/upload.cfg"
done < "$SCRATCH/filelist.txt"

echo "== Uploading $TOTAL files to ${FTP_HOST}${REMOTE_BASE#ftp://$FTP_HOST} =="
echo "   Excluded: .git/, node_modules/, .claude/, acf-json/"
read -r -p "Proceed? [y/N] " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 1
fi

curl -s --user "${FTP_USER}:${FTP_PASSWORD}" --ftp-create-dirs \
    -K "$SCRATCH/upload.cfg" -w "%{filename_effective} -> %{http_code}\n" \
    > "$SCRATCH/result.log" 2>&1

OK=$(grep -c "226$" "$SCRATCH/result.log" || true)
echo "== Done: $OK/$TOTAL files uploaded (code 226) =="
if [ "$OK" -ne "$TOTAL" ]; then
    echo "== Failures: =="
    grep -v "226$" "$SCRATCH/result.log" || true
    exit 1
fi
