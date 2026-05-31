<?php
/**
 * Custom Post Type: Cases (Case Studies)
 * Taxonomy: Industry (hierarchical — like categories)
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
			'taxonomies'          => [ 'case_study_category' ],
			'menu_icon'           => 'dashicons-portfolio',
			'has_archive'         => true,
			'rewrite'             => [ 'slug' => 'cases' ],
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
			'rewrite'           => [ 'slug' => 'case-study-category' ],
		] );
	}
}
add_action( 'init', 'theme_register_tax_industry' );
