<?php
/**
 * Custom Post Type: Cases (Case Studies)
 * Taxonomies: Industry, Service (hierarchical — like categories)
 *
 * File: inc/cpt/cases.php
 * Include in functions.php:
 *   require_once get_template_directory() . '/inc/cpt/cases.php';
 */

defined( 'ABSPATH' ) || exit;

// ── CPT ───────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'theme_register_cpt_cases' ) ) {
	function theme_register_cpt_cases(): void {
		$labels = [
			'name'               => __( 'Cases', 'theme' ),
			'singular_name'      => __( 'Case', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Case', 'theme' ),
			'edit_item'          => __( 'Edit Case', 'theme' ),
			'new_item'           => __( 'New Case', 'theme' ),
			'view_item'          => __( 'View Case', 'theme' ),
			'search_items'       => __( 'Search Cases', 'theme' ),
			'not_found'          => __( 'No cases found', 'theme' ),
			'not_found_in_trash' => __( 'No cases found in trash', 'theme' ),
			'menu_name'          => __( 'Cases', 'theme' ),
		];

		register_post_type( 'case_study', [
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,   // Gutenberg enabled
			'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
			'taxonomies'          => [ 'case_study_category', 'case_study_service' ],
			'menu_icon'           => 'dashicons-portfolio',
			'has_archive'         => true,
			'rewrite'             => [ 'slug' => 'case-study' ],
			'query_var'           => true,
			'capability_type'     => 'post',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_cases' );


// ── Taxonomy: Industry ────────────────────────────────────────────────────────
if ( ! function_exists( 'theme_register_tax_industry' ) ) {
	function theme_register_tax_industry(): void {
		$labels = [
			'name'              => __( 'Industries', 'theme' ),
			'singular_name'     => __( 'Industry', 'theme' ),
			'search_items'      => __( 'Search Industries', 'theme' ),
			'all_items'         => __( 'All Industries', 'theme' ),
			'parent_item'       => __( 'Parent Industry', 'theme' ),
			'parent_item_colon' => __( 'Parent Industry:', 'theme' ),
			'edit_item'         => __( 'Edit Industry', 'theme' ),
			'update_item'       => __( 'Update Industry', 'theme' ),
			'add_new_item'      => __( 'Add New Industry', 'theme' ),
			'new_item_name'     => __( 'New Industry Name', 'theme' ),
			'menu_name'         => __( 'Industries', 'theme' ),
		];

		register_taxonomy( 'case_study_category', [ 'case_study' ], [
			'labels'            => $labels,
			'hierarchical'      => true,   // like categories — supports parent/child
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'      => true,   // visible in Gutenberg sidebar
			'show_admin_column' => true,
			'rewrite'           => [ 'slug' => 'case-study/industry' ],
		] );
	}
}
add_action( 'init', 'theme_register_tax_industry' );


// ── Taxonomy: Service ─────────────────────────────────────────────────────────
if ( ! function_exists( 'theme_register_tax_case_service' ) ) {
	function theme_register_tax_case_service(): void {
		$labels = [
			'name'              => __( 'Services', 'theme' ),
			'singular_name'     => __( 'Service', 'theme' ),
			'search_items'      => __( 'Search Services', 'theme' ),
			'all_items'         => __( 'All Services', 'theme' ),
			'parent_item'       => __( 'Parent Service', 'theme' ),
			'parent_item_colon' => __( 'Parent Service:', 'theme' ),
			'edit_item'         => __( 'Edit Service', 'theme' ),
			'update_item'       => __( 'Update Service', 'theme' ),
			'add_new_item'      => __( 'Add New Service', 'theme' ),
			'new_item_name'     => __( 'New Service Name', 'theme' ),
			'menu_name'         => __( 'Services', 'theme' ),
		];

		register_taxonomy( 'case_study_service', [ 'case_study' ], [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => [ 'slug' => 'case-study/service' ],
		] );
	}
}
add_action( 'init', 'theme_register_tax_case_service' );


// ── Explicit rewrite rules for taxonomy term archives ─────────────────────────
// Needed because WordPress does not generate correct rules for hierarchical
// taxonomies when the rewrite slug contains a slash (e.g. 'case-study/industry').
if ( ! function_exists( 'theme_cases_rewrite_rules' ) ) {
	function theme_cases_rewrite_rules(): void {
		add_rewrite_rule(
			'^case-study/industry/([^/]+)/?$',
			'index.php?case_study_category=$matches[1]',
			'top'
		);
		add_rewrite_rule(
			'^case-study/service/([^/]+)/?$',
			'index.php?case_study_service=$matches[1]',
			'top'
		);
	}
}
add_action( 'init', 'theme_cases_rewrite_rules' );


// ── 301 redirects for legacy case study URLs ──────────────────────────────────
if ( ! function_exists( 'theme_cases_legacy_redirects' ) ) {
	function theme_cases_legacy_redirects(): void {
		$path = trailingslashit( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

		// /cases/ → /case-study/
		if ( $path === '/cases/' ) {
			wp_redirect( home_url( '/case-study/' ), 301 );
			exit;
		}

		// /case-study-category/{term}/ → /case-study/industry/{term}/
		if ( preg_match( '#^/case-study-category/([^/]+)/?$#', $path, $m ) ) {
			wp_redirect( home_url( '/case-study/industry/' . $m[1] . '/' ), 301 );
			exit;
		}

		// /case-study-service/{term}/ → /case-study/service/{term}/
		if ( preg_match( '#^/case-study-service/([^/]+)/?$#', $path, $m ) ) {
			wp_redirect( home_url( '/case-study/service/' . $m[1] . '/' ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'theme_cases_legacy_redirects' );
