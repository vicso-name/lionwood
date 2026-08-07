<?php
/**
 * Custom Post Type: Industries
 *
 * File: inc/cpt/industries.php
 * Include in functions.php:
 *   require_once get_template_directory() . '/inc/cpt/industries.php';
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'theme_register_cpt_industries' ) ) {
	function theme_register_cpt_industries(): void {
		$labels = [
			'name'               => __( 'Industries', 'lionwood' ),
			'singular_name'      => __( 'Industry', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New Industry', 'lionwood' ),
			'edit_item'          => __( 'Edit Industry', 'lionwood' ),
			'new_item'           => __( 'New Industry', 'lionwood' ),
			'view_item'          => __( 'View Industry', 'lionwood' ),
			'search_items'       => __( 'Search Industries', 'lionwood' ),
			'not_found'          => __( 'No industries found', 'lionwood' ),
			'not_found_in_trash' => __( 'No industries found in trash', 'lionwood' ),
			'menu_name'          => __( 'Industries', 'lionwood' ),
		];

		register_post_type( 'industry', [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,   // Gutenberg enabled
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
			'menu_icon'          => 'dashicons-building',
			'has_archive'        => false,
			'rewrite'            => [ 'slug' => 'domains' ],
			'query_var'          => true,
			'capability_type'    => 'post',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_industries' );


// ── Legacy redirect: /industries/{slug}/ renamed to /domains/{slug}/ ─────────
// URL slug was intentionally changed (client request); keep old links/bookmarks
// working with a 301 rather than letting them 404. Handles the optional
// 2-letter Polylang language prefix (e.g. /uk/industries/healthcare/).
//
// Priority 0 is required: WP core's redirect_canonical() also hooks
// template_redirect at priority 10 and, on an unresolvable /uk/industries/...
// 404, gets there first with its own guessed redirect (which drops the /uk/
// prefix) — same reason the case-study uk-fallback in cases-cpt.php runs at
// priority 0. Confirmed live: without priority 0 this redirect never ran at
// all for /uk/ requests, core's own guess won the race first.
if ( ! function_exists( 'theme_industries_legacy_redirect' ) ) {
	function theme_industries_legacy_redirect(): void {
		$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ?: '';

		if ( preg_match( '#^(/[a-z]{2}/)?industries(/.*)?$#', $path, $m ) ) {
			wp_redirect( home_url( ( $m[1] ?? '' ) . 'domains' . ( $m[2] ?? '' ) ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'theme_industries_legacy_redirect', 0 );


// ── UA fallback for untranslated /domains/ pages ──────────────────────────────
// Confirmed via REST: the `industry` CPT has no separate Ukrainian posts at
// all (?lang=uk returns the exact same 12 posts as the unfiltered query) —
// Polylang itself already 301s /uk/domains/{slug}/ to the English URL
// (X-Redirect-By: Polylang), same symptom as the case-study pages had. Same
// treatment: redirect to /uk/ instead of leaking English content under a /uk/
// URL. Priority 0 for the same race-with-redirect_canonical/Polylang reason
// as theme_industries_legacy_redirect above.
if ( ! function_exists( 'theme_industries_uk_fallback' ) ) {
	function theme_industries_uk_fallback(): void {
		if ( ! function_exists( 'pll_current_language' ) || pll_current_language() !== 'uk' ) return;

		$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ?: '';
		if ( strpos( $path, '/uk/domains/' ) !== 0 ) return;

		$has_uk_equivalent = is_singular( 'industry' ) && function_exists( 'pll_get_post_language' )
			&& pll_get_post_language( get_the_ID() ) === 'uk';

		if ( ! $has_uk_equivalent ) {
			wp_safe_redirect( home_url( '/uk/' ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'theme_industries_uk_fallback', 0 );
