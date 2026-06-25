<?php
/**
 * Theme Settings
 *
 * Central configuration: WordPress defaults cleanup, theme support,
 * comments, ACF local JSON, and login hardening.
 *
 * To re-enable any removed feature, delete or comment out the block.
 */

// =============================================================================
// 1. Clean up WordPress defaults
// =============================================================================

// Remove all emoji scripts and styles — wp_head bloat, not needed for agency sites
function lionwood_remove_emoji(): void {
    remove_action('wp_head',             'print_emoji_detection_script', 7);
    remove_action('wp_print_styles',     'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles',  'print_emoji_styles');
    remove_filter('the_content_feed',    'wp_staticize_emoji');
    remove_filter('comment_text_rss',    'wp_staticize_emoji');
    remove_filter('wp_mail',             'wp_staticize_emoji_for_email');
}
add_action('init', 'lionwood_remove_emoji');

// Remove RSS feed links, WP version tag, and oEmbed discovery from <head>
function lionwood_remove_head_noise(): void {
    // RSS <link> tags — not needed on most agency builds
    remove_action('wp_head', 'feed_links',       2);
    remove_action('wp_head', 'feed_links_extra', 3);
    // WordPress generator tag — avoids exposing WP version to vulnerability scanners
    remove_action('wp_head', 'wp_generator');
    // oEmbed discovery and host JS — not needed if you don't embed external content
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
}
add_action('init', 'lionwood_remove_head_noise');

// Disable XML-RPC — security risk, only needed for WP mobile app or Jetpack
add_filter('xmlrpc_enabled', '__return_false');

// Remove jQuery Migrate — legacy shim only needed for plugins targeting jQuery < 1.9
function lionwood_remove_jquery_migrate(WP_Scripts $scripts): void {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
        );
    }
}
add_action('wp_default_scripts', 'lionwood_remove_jquery_migrate');

// Remove medium_large image size — unnecessary middle size that wastes disk space
function lionwood_remove_unneeded_image_sizes(array $sizes): array {
    unset($sizes['medium_large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'lionwood_remove_unneeded_image_sizes');

// =============================================================================
// 2. Theme support
// =============================================================================

function lionwood_register_theme_support(): void {
    // Let WordPress manage the <title> tag
    add_theme_support('title-tag');

    // Enable featured images on posts and pages
    add_theme_support('post-thumbnails');

    // Output valid HTML5 markup for core UI elements
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);

    // Allow editor-styles.css to be applied inside the Gutenberg editor
    add_theme_support('editor-styles');

    // Navigation menus — add or remove locations as needed
    register_nav_menus([
        'primary' => __('Primary Navigation', 'lionwood'),
        'footer'  => __('Footer Navigation', 'lionwood'),
    ]);
}
add_action('after_setup_theme', 'lionwood_register_theme_support');

// =============================================================================
// 3. Disable comments
// =============================================================================

// Remove comments support from posts and pages — delete this block to re-enable
function lionwood_disable_comment_support(): void {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
}
add_action('init', 'lionwood_disable_comment_support');

add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove Comments from admin sidebar — delete this block to re-enable
function lionwood_remove_comments_menu(): void {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'lionwood_remove_comments_menu');

// Remove Comments node from admin bar — delete this block to re-enable
function lionwood_remove_comments_admin_bar(WP_Admin_Bar $bar): void {
    $bar->remove_node('comments');
}
add_action('admin_bar_menu', 'lionwood_remove_comments_admin_bar', 999);

// Redirect direct access to the comments admin page — delete this block to re-enable
function lionwood_redirect_comments_admin(): void {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url(), 301);
        exit;
    }
}
add_action('admin_init', 'lionwood_redirect_comments_admin');

// =============================================================================
// 4. ACF Options pages
// =============================================================================

if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug'  => 'theme-options',
        'capability' => 'edit_posts',
        'redirect'   => false,
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Footer',
        'menu_title'  => 'Footer',
        'menu_slug'   => 'acf-options-footer',
        'parent_slug' => 'theme-options',
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Contact',
        'menu_title'  => 'Contact',
        'menu_slug'   => 'acf-options-contact',
        'parent_slug' => 'theme-options',
    ]);
}

// =============================================================================
// 6. Login page hardening
// =============================================================================

// Generic error message — prevents revealing whether the username or password was wrong
add_filter('login_errors', fn(): string =>
    __('Incorrect credentials. Please try again.', 'lionwood')
);

// Remove "← Back to {site}" link — avoids hinting at the site URL structure
add_filter('login_site_html_link', '__return_empty_string');

// Require email address to log in — rejects login attempts using a username
function lionwood_require_email_login(
    WP_User|WP_Error|null $user,
    string $username,
    string $password
): WP_User|WP_Error|null {
    if ($user instanceof WP_User) return $user;
    if (!is_email($username)) {
        return new WP_Error(
            'email_required',
            __('Please log in with your email address.', 'lionwood')
        );
    }
    return $user;
}
add_filter('authenticate', 'lionwood_require_email_login', 10, 3);
