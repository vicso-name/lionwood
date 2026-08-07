<?php
/**
 * Custom Post Type: Sub-Services
 */

defined('ABSPATH') || exit;

add_action('init', function () {
    $labels = [
        'name'               => __('Sub-Services', 'lionwood'),
        'singular_name'      => __('Sub-Service', 'lionwood'),
        'add_new'            => __('Add New', 'lionwood'),
        'add_new_item'       => __('Add New Sub-Service', 'lionwood'),
        'edit_item'          => __('Edit Sub-Service', 'lionwood'),
        'new_item'           => __('New Sub-Service', 'lionwood'),
        'view_item'          => __('View Sub-Service', 'lionwood'),
        'search_items'       => __('Search Sub-Services', 'lionwood'),
        'not_found'          => __('No sub-services found', 'lionwood'),
        'not_found_in_trash' => __('No sub-services found in trash', 'lionwood'),
        'menu_name'          => __('Sub-Services', 'lionwood'),
    ];

    register_post_type('sub_service', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions'],
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-networking',
        'has_archive'        => false,
        'rewrite'            => false,  // managed manually below
        'query_var'          => 'sub_service',
        'capability_type'    => 'page',
    ]);
});


/* ─────────────────────────────────────────────
   Rewrite rules — /services/{parent}/{child}/
   ───────────────────────────────────────────── */

add_action('init', function () {
    // Must be 'top' so it's checked before the service CPT rules
    add_rewrite_rule(
        '^services/([^/]+)/([^/]+)/?$',
        'index.php?post_type=sub_service&name=$matches[2]',
        'top'
    );
}, 5);

// Flush once when the rule is not yet in the saved ruleset
add_action('init', function () {
    $rules = get_option('rewrite_rules', []);
    if (empty($rules['^services/([^/]+)/([^/]+)/?$'])) {
        flush_rewrite_rules();
    }
}, 99);


/* ─────────────────────────────────────────────
   Permalink — /services/{parent-slug}/{child-slug}/
   ───────────────────────────────────────────── */

add_filter('post_type_link', function (string $link, WP_Post $post): string {
    if ($post->post_type !== 'sub_service') {
        return $link;
    }

    $parent_id = (int) get_post_meta($post->ID, 'parent_service', true);

    if ($parent_id) {
        $parent = get_post($parent_id);
        if ($parent) {
            return trailingslashit(home_url("services/{$parent->post_name}/{$post->post_name}"));
        }
    }

    // Fallback when no parent assigned yet
    return trailingslashit(home_url("services/{$post->post_name}"));
}, 10, 2);

// After ACF writes postmeta, flush the object cache so any subsequent get_permalink()
// call (canonical redirect check, block-editor re-fetch) reads the updated parent_service.
add_action('acf/save_post', function ($post_id) {
    if (is_numeric($post_id) && get_post_type((int) $post_id) === 'sub_service') {
        clean_post_cache((int) $post_id);
    }
}, 20);

// Flush rewrite rules on first publish so the new post's URL is immediately routable.
// Skipped on subsequent saves (performance) — only !$update (new post) triggers the flush.
add_action('save_post_sub_service', function (int $post_id, WP_Post $post, bool $update): void {
    if ($post->post_status === 'publish' && !$update) {
        flush_rewrite_rules();
    }
}, 20, 3);

// Correct the "View Post" / "is now live" notice link after first publish.
// redirect_post_location fires after all save_post hooks including ACF's, so by this
// point parent_service postmeta is written. Busting the object cache ensures the notice
// link rendered on the following page load calls get_permalink() with fresh postmeta.
add_filter('redirect_post_location', function (string $location, int $post_id): string {
    if (get_post_type($post_id) !== 'sub_service') {
        return $location;
    }
    clean_post_cache($post_id);
    return $location;
}, 20, 2);


/* ─────────────────────────────────────────────
   One-segment URL redirect fallback
   Catches /services/{slug}/ 404s (e.g. from the "is now live" link on first publish
   before the guid is corrected) and 301s to /services/{parent-slug}/{slug}/.
   ───────────────────────────────────────────── */

add_action('template_redirect', function (): void {
    if (!is_404()) {
        return;
    }

    $path  = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $parts = explode('/', $path);

    if (count($parts) !== 2 || $parts[0] !== 'services') {
        return;
    }

    $post = get_page_by_path($parts[1], OBJECT, 'sub_service');
    if (!$post) {
        return;
    }

    $parent_id = (int) get_post_meta($post->ID, 'parent_service', true);
    if (!$parent_id) {
        return;
    }

    $parent = get_post($parent_id);
    if (!$parent) {
        return;
    }

    wp_redirect(trailingslashit(home_url("services/{$parent->post_name}/{$post->post_name}")), 301);
    exit;
});


/* ─────────────────────────────────────────────
   Template — reuse single-service.php
   ───────────────────────────────────────────── */

add_filter('template_include', function (string $template): string {
    if (!is_singular('sub_service')) {
        return $template;
    }

    $service_tpl = locate_template('single-service.php');

    return $service_tpl ?: $template;
});


/* ─────────────────────────────────────────────
   Helper — get sub-service IDs for a service
   ───────────────────────────────────────────── */

function lionwood_get_subservices( int $parent_id, int $limit = 5 ): array {
    return get_posts( [
        'post_type'      => 'sub_service',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'parent_service',
                'value' => $parent_id,
            ],
        ],
    ] );
}


/* ─────────────────────────────────────────────
   Admin columns — Sub-Services list
   Shows "Parent Service" column next to title
   ───────────────────────────────────────────── */

add_filter('manage_sub_service_posts_columns', function (array $columns): array {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['parent_service'] = __('Service', 'lionwood');
        }
    }
    return $new;
});

add_action('manage_sub_service_posts_custom_column', function (string $column, int $post_id): void {
    if ($column !== 'parent_service') {
        return;
    }

    $parent_id = (int) get_post_meta($post_id, 'parent_service', true);

    if (!$parent_id) {
        echo '<span style="color:#aaa;">—</span>';
        return;
    }

    $parent = get_post($parent_id);
    if (!$parent) {
        echo '<span style="color:#aaa;">—</span>';
        return;
    }

    printf(
        '<a href="%s">%s</a>',
        esc_url(get_edit_post_link($parent_id)),
        esc_html($parent->post_title)
    );
}, 10, 2);


/* ─────────────────────────────────────────────
   Admin columns — Services list
   Shows "Sub-Services" column with linked titles
   ───────────────────────────────────────────── */

add_filter('manage_service_posts_columns', function (array $columns): array {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['sub_services'] = __('Sub-Services', 'lionwood');
        }
    }
    return $new;
});

add_action('manage_service_posts_custom_column', function (string $column, int $post_id): void {
    if ($column !== 'sub_services') {
        return;
    }

    $subs = get_posts([
        'post_type'      => 'sub_service',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'parent_service',
                'value' => $post_id,
            ],
        ],
    ]);

    if (empty($subs)) {
        echo '<span style="color:#aaa;">—</span>';
        return;
    }

    $limit   = 3;
    $total   = count($subs);
    $visible = array_slice($subs, 0, $limit);
    $links   = [];

    foreach ($visible as $sub_id) {
        $links[] = sprintf(
            '<a href="%s">%s</a>',
            esc_url(get_edit_post_link($sub_id)),
            esc_html(get_the_title($sub_id))
        );
    }

    echo implode(', ', $links);

    if ($total > $limit) {
        $remaining_url = add_query_arg([
            'post_type'  => 'sub_service',
            'meta_key'   => 'parent_service',
            'meta_value' => $post_id,
        ], admin_url('edit.php'));

        printf(
            ' <a href="%s" style="color:#aaa;">+%d %s</a>',
            esc_url($remaining_url),
            $total - $limit,
            esc_html__('more', 'lionwood')
        );
    }
}, 10, 2);


/* ─────────────────────────────────────────────
   ACF field group — Parent Service selector
   ───────────────────────────────────────────── */

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_sub_service_meta',
        'title'  => __('Sub-Service Settings', 'lionwood'),
        'fields' => [
            [
                'key'           => 'field_sub_service_parent',
                'label'         => __('Parent Service', 'lionwood'),
                'name'          => 'parent_service',
                'type'          => 'post_object',
                'instructions'  => __('Select the service this sub-service belongs to.', 'lionwood'),
                'required'      => 1,
                'post_type'     => ['service'],
                'taxonomy'      => [],
                'allow_null'    => 1,
                'multiple'      => 0,
                'return_format' => 'id',
                'ui'            => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'sub_service',
                ],
            ],
        ],
        'position'    => 'side',
        'menu_order'  => 0,
        'active'      => true,
    ]);
});
