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
			'name'               => __( 'Cases', 'lionwood' ),
			'singular_name'      => __( 'Case', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New Case', 'lionwood' ),
			'edit_item'          => __( 'Edit Case', 'lionwood' ),
			'new_item'           => __( 'New Case', 'lionwood' ),
			'view_item'          => __( 'View Case', 'lionwood' ),
			'search_items'       => __( 'Search Cases', 'lionwood' ),
			'not_found'          => __( 'No cases found', 'lionwood' ),
			'not_found_in_trash' => __( 'No cases found in trash', 'lionwood' ),
			'menu_name'          => __( 'Cases', 'lionwood' ),
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
			'name'              => __( 'Industries', 'lionwood' ),
			'singular_name'     => __( 'Industry', 'lionwood' ),
			'search_items'      => __( 'Search Industries', 'lionwood' ),
			'all_items'         => __( 'All Industries', 'lionwood' ),
			'parent_item'       => __( 'Parent Industry', 'lionwood' ),
			'parent_item_colon' => __( 'Parent Industry:', 'lionwood' ),
			'edit_item'         => __( 'Edit Industry', 'lionwood' ),
			'update_item'       => __( 'Update Industry', 'lionwood' ),
			'add_new_item'      => __( 'Add New Industry', 'lionwood' ),
			'new_item_name'     => __( 'New Industry Name', 'lionwood' ),
			'menu_name'         => __( 'Industries', 'lionwood' ),
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
			'name'              => __( 'Services', 'lionwood' ),
			'singular_name'     => __( 'Service', 'lionwood' ),
			'search_items'      => __( 'Search Services', 'lionwood' ),
			'all_items'         => __( 'All Services', 'lionwood' ),
			'parent_item'       => __( 'Parent Service', 'lionwood' ),
			'parent_item_colon' => __( 'Parent Service:', 'lionwood' ),
			'edit_item'         => __( 'Edit Service', 'lionwood' ),
			'update_item'       => __( 'Update Service', 'lionwood' ),
			'add_new_item'      => __( 'Add New Service', 'lionwood' ),
			'new_item_name'     => __( 'New Service Name', 'lionwood' ),
			'menu_name'         => __( 'Services', 'lionwood' ),
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
