<?php
/**
 * ACF Options Pages
 *
 * Registers custom options pages for global/archive settings.
 * Includes Polylang compatibility: fields are stored and retrieved
 * per-language using the suffix options_{name}_{lang}.
 */

defined( 'ABSPATH' ) || exit;

// ── Case Archive Options page ──────────────────────────────────────────────────
add_action( 'acf/init', 'theme_register_case_archive_options_page' );
function theme_register_case_archive_options_page(): void {
    if ( ! function_exists( 'acf_add_options_page' ) ) return;

    acf_add_options_page( [
        'page_title' => 'Case Archive Settings',
        'menu_title' => 'Case Archive',
        'menu_slug'  => 'case-archive-options',
        'parent_slug'=> 'edit.php?post_type=case_study',
        'capability' => 'edit_theme_options',
        'autoload'   => true,
    ] );
}

// ── HubSpot Settings page ─────────────────────────────────────────────────────
add_action( 'acf/init', 'theme_register_hubspot_options_page' );
function theme_register_hubspot_options_page(): void {
    if ( ! function_exists( 'acf_add_options_page' ) ) return;

    acf_add_options_page( [
        'page_title' => 'HubSpot Settings',
        'menu_title' => 'HubSpot',
        'menu_slug'  => 'hubspot-options',
        'parent_slug'=> 'options-general.php',
        'capability' => 'manage_options',
        'autoload'   => true,
    ] );
}

// ── Polylang compatibility — load language-specific option values ───────────────
// When a field is read from the options page, check if a language-specific
// override exists (stored as options_{name}_{lang} in wp_options).
// Saving language-specific values: switch to the desired language in admin,
// then save the options page — values are written via acf/update_value below.
add_filter( 'acf/load_value', 'theme_pll_load_options_value', 10, 3 );
function theme_pll_load_options_value( $value, $post_id, $field ) {
    if ( $post_id !== 'option' ) return $value;
    if ( ! function_exists( 'pll_current_language' ) ) return $value;

    $lang = pll_current_language();
    if ( ! $lang ) return $value;

    $lang_value = get_option( 'options_' . $field['name'] . '_' . $lang );
    return ( $lang_value !== false ) ? $lang_value : $value;
}

// ── Polylang compatibility — save language-specific option values ───────────────
add_filter( 'acf/update_value', 'theme_pll_update_options_value', 10, 3 );
function theme_pll_update_options_value( $value, $post_id, $field ) {
    if ( $post_id !== 'option' ) return $value;
    if ( ! function_exists( 'pll_current_language' ) ) return $value;

    $lang = pll_current_language();
    if ( ! $lang ) return $value;

    // Store language-specific value; let ACF also store the default
    update_option( 'options_' . $field['name'] . '_' . $lang, $value );
    return $value;
}
