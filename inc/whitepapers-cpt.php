<?php
/**
 * Custom Post Type: Whitepapers
 * Taxonomy: Whitepaper Category (hierarchical)
 */

defined( 'ABSPATH' ) || exit;

// ── CPT ───────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'theme_register_cpt_whitepaper' ) ) {
	function theme_register_cpt_whitepaper(): void {
		$labels = [
			'name'               => __( 'Whitepapers', 'theme' ),
			'singular_name'      => __( 'Whitepaper', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Whitepaper', 'theme' ),
			'edit_item'          => __( 'Edit Whitepaper', 'theme' ),
			'new_item'           => __( 'New Whitepaper', 'theme' ),
			'view_item'          => __( 'View Whitepaper', 'theme' ),
			'search_items'       => __( 'Search Whitepapers', 'theme' ),
			'not_found'          => __( 'No whitepapers found', 'theme' ),
			'not_found_in_trash' => __( 'No whitepapers found in trash', 'theme' ),
			'menu_name'          => __( 'Whitepapers', 'theme' ),
		];

		register_post_type( 'whitepaper', [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
			'taxonomies'         => [ 'whitepaper_category' ],
			'menu_icon'          => 'dashicons-media-document',
			'has_archive'        => true,
			'rewrite'            => [ 'slug' => 'whitepapers' ],
			'query_var'          => true,
			'capability_type'    => 'post',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_whitepaper' );


// ── Taxonomy: Whitepaper Category ─────────────────────────────────────────────
if ( ! function_exists( 'theme_register_tax_whitepaper_category' ) ) {
	function theme_register_tax_whitepaper_category(): void {
		$labels = [
			'name'              => __( 'Whitepaper Categories', 'theme' ),
			'singular_name'     => __( 'Whitepaper Category', 'theme' ),
			'search_items'      => __( 'Search Whitepaper Categories', 'theme' ),
			'all_items'         => __( 'All Whitepaper Categories', 'theme' ),
			'parent_item'       => __( 'Parent Category', 'theme' ),
			'parent_item_colon' => __( 'Parent Category:', 'theme' ),
			'edit_item'         => __( 'Edit Whitepaper Category', 'theme' ),
			'update_item'       => __( 'Update Whitepaper Category', 'theme' ),
			'add_new_item'      => __( 'Add New Whitepaper Category', 'theme' ),
			'new_item_name'     => __( 'New Whitepaper Category Name', 'theme' ),
			'menu_name'         => __( 'Categories', 'theme' ),
		];

		register_taxonomy( 'whitepaper_category', [ 'whitepaper' ], [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => [ 'slug' => 'whitepapers/category' ],
		] );
	}
}
add_action( 'init', 'theme_register_tax_whitepaper_category' );
