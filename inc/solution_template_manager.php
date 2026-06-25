<?php
/**
 * Solution CPT — Default Block Template Manager
 *
 * Lets the client define the default Gutenberg block structure
 * for new Solution posts via a settings page.
 *
 * A hidden "template post" is edited in the normal Gutenberg editor;
 * its blocks are parsed and injected into the CPT `template` argument.
 */

defined('ABSPATH') || exit;


/* ─────────────────────────────────────────────
   1. Hidden CPT to store the template
   ───────────────────────────────────────────── */

add_action('init', function () {
    register_post_type('solution_template', [
        'labels' => [
            'name'          => __('Solution Templates', 'lionwood'),
            'singular_name' => __('Solution Template', 'lionwood'),
            'edit_item'     => __('Edit Solution Block Template', 'lionwood'),
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => false,
        'show_in_rest'  => true,
        'supports'      => ['title', 'editor', 'custom-fields'],
        'template_lock' => false,
    ]);
});


/* ─────────────────────────────────────────────
   2. Settings page under Solutions menu
   ───────────────────────────────────────────── */

add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=solution',
        __('Block Template Settings', 'lionwood'),
        __('Block Template', 'lionwood'),
        'manage_options',
        'solution-block-template',
        'lionwood_render_solution_template_settings_page'
    );
});


function lionwood_render_solution_template_settings_page(): void {
    $template_post_id = lionwood_get_or_create_solution_template_post();
    $edit_link        = get_edit_post_link($template_post_id, 'raw');

    if (
        isset($_POST['lionwood_solution_template_nonce']) &&
        wp_verify_nonce($_POST['lionwood_solution_template_nonce'], 'lionwood_solution_template_save')
    ) {
        if (isset($_POST['action_reset'])) {
            wp_update_post([
                'ID'           => $template_post_id,
                'post_content' => lionwood_get_solution_default_template_content(),
            ]);
            echo '<div class="notice notice-success"><p>' . esc_html__('Template reset to default.', 'lionwood') . '</p></div>';
        } elseif (isset($_POST['action_backfill'])) {
            $updated = lionwood_backfill_empty_solution_posts();
            echo '<div class="notice notice-success"><p>' . sprintf(
                esc_html__('Done. %d solution post(s) updated with the block template.', 'lionwood'),
                $updated
            ) . '</p></div>';
        } elseif (isset($_POST['action_fix_edit_mode'])) {
            $updated = lionwood_fix_edit_mode_on_solution_posts();
            echo '<div class="notice notice-success"><p>' . sprintf(
                esc_html__('Done. Edit mode fixed on %d solution post(s).', 'lionwood'),
                $updated
            ) . '</p></div>';
        }
    }

    $empty_count    = lionwood_count_empty_solution_posts();
    $fix_mode_count = lionwood_count_solution_posts_needing_edit_mode();
    ?>

    <div class="wrap">
        <h1><?php esc_html_e('Solutions — Block Template Settings', 'lionwood'); ?></h1>

        <div class="card" style="max-width: 720px; margin-top: 20px;">
            <h2><?php esc_html_e('Default Block Template', 'lionwood'); ?></h2>
            <p class="description">
                <?php esc_html_e('Define which Gutenberg blocks are pre-loaded when creating a new Solution post. Edit the template in the block editor.', 'lionwood'); ?>
            </p>

            <table class="form-table" role="presentation" style="margin-top: 16px;">
                <tr>
                    <th scope="row"><?php esc_html_e('Edit template', 'lionwood'); ?></th>
                    <td>
                        <a href="<?php echo esc_url($edit_link); ?>" class="button button-primary" target="_blank">
                            <?php esc_html_e('Open Template in Block Editor →', 'lionwood'); ?>
                        </a>
                        <p class="description" style="margin-top: 8px;">
                            <?php esc_html_e('Add, remove, or reorder blocks. Changes apply to all NEW solution posts only.', 'lionwood'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Reset template', 'lionwood'); ?></th>
                    <td>
                        <form method="post">
                            <?php wp_nonce_field('lionwood_solution_template_save', 'lionwood_solution_template_nonce'); ?>
                            <button type="submit" name="action_reset" value="1" class="button button-secondary"
                                    onclick="return confirm('<?php esc_attr_e('Reset template to default? This cannot be undone.', 'lionwood'); ?>')">
                                <?php esc_html_e('Reset to Default', 'lionwood'); ?>
                            </button>
                            <p class="description" style="margin-top: 8px;">
                                <?php esc_html_e('Restore the built-in block order in the template editor.', 'lionwood'); ?>
                            </p>
                        </form>
                    </td>
                </tr>
            </table>
        </div>

        <?php if ($empty_count > 0) : ?>
        <div class="card" style="max-width: 720px; margin-top: 20px;">
            <h2><?php esc_html_e('Apply Template to Existing Posts', 'lionwood'); ?></h2>
            <p>
                <?php printf(
                    esc_html__('%d solution post(s) have no content yet and will receive the block template.', 'lionwood'),
                    $empty_count
                ); ?>
                <?php esc_html_e('Posts that already have content will not be touched.', 'lionwood'); ?>
            </p>
            <form method="post">
                <?php wp_nonce_field('lionwood_solution_template_save', 'lionwood_solution_template_nonce'); ?>
                <button type="submit" name="action_backfill" value="1" class="button button-primary"
                        onclick="return confirm('<?php esc_attr_e('Apply template to all empty solution posts? This cannot be undone.', 'lionwood'); ?>')">
                    <?php printf(esc_html__('Apply Template to %d Post(s)', 'lionwood'), $empty_count); ?>
                </button>
            </form>
        </div>
        <?php else : ?>
        <div class="card" style="max-width: 720px; margin-top: 20px;">
            <p style="margin: 0;">✅ <?php esc_html_e('All existing solution posts already have content. Nothing to migrate.', 'lionwood'); ?></p>
        </div>
        <?php endif; ?>

        <?php if ($fix_mode_count > 0) : ?>
        <div class="card" style="max-width: 720px; margin-top: 20px;">
            <h2><?php esc_html_e('Fix Block Edit Mode', 'lionwood'); ?></h2>
            <p>
                <?php printf(
                    esc_html__('%d solution post(s) have blocks displayed in Preview mode instead of Edit mode.', 'lionwood'),
                    $fix_mode_count
                ); ?>
            </p>
            <form method="post">
                <?php wp_nonce_field('lionwood_solution_template_save', 'lionwood_solution_template_nonce'); ?>
                <button type="submit" name="action_fix_edit_mode" value="1" class="button button-primary">
                    <?php printf(esc_html__('Fix Edit Mode on %d Post(s)', 'lionwood'), $fix_mode_count); ?>
                </button>
            </form>
        </div>
        <?php endif; ?>

        <div class="card" style="max-width: 720px; margin-top: 20px; background: #fff8e1; border-left: 4px solid #f0b429;">
            <h3 style="margin-top: 0;"><?php esc_html_e('How it works', 'lionwood'); ?></h3>
            <ul style="list-style: disc; padding-left: 20px;">
                <li><?php esc_html_e('Click "Open Template in Block Editor" and arrange the blocks you want.', 'lionwood'); ?></li>
                <li><?php esc_html_e('All NEW solution posts will start with these blocks pre-loaded.', 'lionwood'); ?></li>
                <li><?php esc_html_e('Posts that already have content are never touched automatically.', 'lionwood'); ?></li>
                <li><?php esc_html_e('Use "Reset to Default" to restore the original block order in the editor.', 'lionwood'); ?></li>
            </ul>
        </div>
    </div>
    <?php
}


/* ─────────────────────────────────────────────
   3. Get or create the template post
   ───────────────────────────────────────────── */

function lionwood_get_or_create_solution_template_post(): int {
    $post_id = (int) get_option('lionwood_solution_template_post_id', 0);

    if ($post_id && get_post_status($post_id) !== false) {
        return $post_id;
    }

    $post_id = wp_insert_post([
        'post_title'   => __('Solution — Default Block Template', 'lionwood'),
        'post_type'    => 'solution_template',
        'post_status'  => 'publish',
        'post_content' => lionwood_get_solution_default_template_content(),
    ]);

    update_option('lionwood_solution_template_post_id', $post_id);

    return $post_id;
}


/* ─────────────────────────────────────────────
   4. Hardcoded default — single solution block order
   ───────────────────────────────────────────── */

function lionwood_get_solution_default_template_content(): string {
    return <<<'BLOCKS'
<!-- wp:acf/simple-hero {"mode":"edit"} /-->
<!-- wp:acf/solution-comparison {"mode":"edit"} /-->
<!-- wp:acf/get-started {"mode":"edit"} /-->
BLOCKS;
}


/* ─────────────────────────────────────────────
   5. Parse block content → template array
   ───────────────────────────────────────────── */

function lionwood_solution_blocks_to_template_array(string $content): array {
    $parsed   = parse_blocks($content);
    $template = [];

    foreach ($parsed as $block) {
        if (empty($block['blockName'])) {
            continue;
        }

        $entry = [$block['blockName'], array_merge($block['attrs'] ?? [], ['mode' => 'edit'])];

        if (!empty($block['innerBlocks'])) {
            $inner = [];
            foreach ($block['innerBlocks'] as $ib) {
                if (!empty($ib['blockName'])) {
                    $inner[] = [$ib['blockName'], $ib['attrs'] ?? []];
                }
            }
            $entry[] = $inner;
        }

        $template[] = $entry;
    }

    return $template;
}


/* ─────────────────────────────────────────────
   6. Inject template into Solution CPT args
   ───────────────────────────────────────────── */

add_filter('register_post_type_args', function (array $args, string $post_type): array {
    if ($post_type !== 'solution') {
        return $args;
    }

    $content = lionwood_get_solution_default_template_content();
    $tid     = (int) get_option('lionwood_solution_template_post_id', 0);

    if ($tid) {
        $post = get_post($tid);
        if ($post && !empty($post->post_content)) {
            $content = $post->post_content;
        }
    }

    $args['template']      = lionwood_solution_blocks_to_template_array($content);
    $args['template_lock'] = false;

    return $args;
}, 20, 2);


/* ─────────────────────────────────────────────
   7. Allow all blocks in the template editor
   ───────────────────────────────────────────── */

add_filter('allowed_block_types_all', function ($allowed, $ctx) {
    if (isset($ctx->post) && $ctx->post->post_type === 'solution_template') {
        return true;
    }
    return $allowed;
}, 10, 2);


/* ─────────────────────────────────────────────
   8. Force edit mode on every save of the template post
   ───────────────────────────────────────────── */

add_filter('wp_insert_post_data', function (array $data): array {
    if ($data['post_type'] === 'solution_template' && !empty($data['post_content'])) {
        $data['post_content'] = lionwood_inject_edit_mode($data['post_content']);
    }
    return $data;
}, 10, 1);


/* ─────────────────────────────────────────────
   8b. Auto-fix existing template post on admin init
   ───────────────────────────────────────────── */

add_action('admin_init', function (): void {
    $tid = (int) get_option('lionwood_solution_template_post_id', 0);
    if (!$tid) return;

    $post = get_post($tid);
    if (!$post || empty($post->post_content)) return;

    if (strpos($post->post_content, '"mode":"edit"') === false) {
        wp_update_post([
            'ID'           => $tid,
            'post_content' => lionwood_inject_edit_mode($post->post_content),
        ]);
    }
});


/* ─────────────────────────────────────────────
   9. Restrict template editing to admins only
   ───────────────────────────────────────────── */

add_filter('user_has_cap', function (array $allcaps, array $caps, array $args): array {
    if (
        isset($args[0]) &&
        in_array($args[0], ['edit_post', 'delete_post'], true)
    ) {
        $post_id = $args[2] ?? 0;
        if ($post_id && get_post_type($post_id) === 'solution_template') {
            if (empty($allcaps['manage_options'])) {
                $allcaps[$caps[0]] = false;
            }
        }
    }
    return $allcaps;
}, 10, 3);


/* ─────────────────────────────────────────────
   10. Hide template CPT from frontend
   ───────────────────────────────────────────── */

add_action('pre_get_posts', function ($query): void {
    if (!is_admin() && $query->is_main_query()) {
        $excluded   = $query->get('post_type__not_in', []);
        $excluded[] = 'solution_template';
        $query->set('post_type__not_in', $excluded);
    }
});


/* ─────────────────────────────────────────────
   11. Backfill helpers
   ───────────────────────────────────────────── */

function lionwood_get_empty_solution_post_ids(): array {
    $posts = get_posts([
        'post_type'      => 'solution',
        'post_status'    => ['publish', 'draft', 'pending', 'future'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    return array_filter($posts, function (int $id): bool {
        $post = get_post($id);
        return $post && trim($post->post_content) === '';
    });
}

function lionwood_count_empty_solution_posts(): int {
    return count(lionwood_get_empty_solution_post_ids());
}

function lionwood_count_solution_posts_needing_edit_mode(): int {
    $posts = get_posts([
        'post_type'      => 'solution',
        'post_status'    => ['publish', 'draft', 'pending', 'future'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $count = 0;
    foreach ($posts as $id) {
        $post = get_post($id);
        if (
            $post &&
            strpos($post->post_content, 'wp:acf/') !== false &&
            strpos($post->post_content, '"mode":"edit"') === false
        ) {
            $count++;
        }
    }

    return $count;
}

function lionwood_fix_edit_mode_on_solution_posts(): int {
    $posts = get_posts([
        'post_type'      => 'solution',
        'post_status'    => ['publish', 'draft', 'pending', 'future'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $updated = 0;
    foreach ($posts as $id) {
        $post = get_post($id);
        if (
            !$post ||
            strpos($post->post_content, 'wp:acf/') === false ||
            strpos($post->post_content, '"mode":"edit"') !== false
        ) {
            continue;
        }

        wp_update_post([
            'ID'           => $id,
            'post_content' => lionwood_inject_edit_mode($post->post_content),
        ]);
        $updated++;
    }

    return $updated;
}

function lionwood_backfill_empty_solution_posts(): int {
    $content = lionwood_get_solution_default_template_content();
    $tid     = (int) get_option('lionwood_solution_template_post_id', 0);

    if ($tid) {
        $tpost = get_post($tid);
        if ($tpost && !empty($tpost->post_content)) {
            $content = $tpost->post_content;
        }
    }

    $content = lionwood_inject_edit_mode($content);

    $updated = 0;
    foreach (lionwood_get_empty_solution_post_ids() as $id) {
        wp_update_post([
            'ID'           => $id,
            'post_content' => $content,
        ]);
        $updated++;
    }

    return $updated;
}
