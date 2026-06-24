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
			'name'               => __( 'Whitepapers', 'lionwood' ),
			'singular_name'      => __( 'Whitepaper', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New Whitepaper', 'lionwood' ),
			'edit_item'          => __( 'Edit Whitepaper', 'lionwood' ),
			'new_item'           => __( 'New Whitepaper', 'lionwood' ),
			'view_item'          => __( 'View Whitepaper', 'lionwood' ),
			'search_items'       => __( 'Search Whitepapers', 'lionwood' ),
			'not_found'          => __( 'No whitepapers found', 'lionwood' ),
			'not_found_in_trash' => __( 'No whitepapers found in trash', 'lionwood' ),
			'menu_name'          => __( 'Whitepapers', 'lionwood' ),
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
			'name'              => __( 'Whitepaper Categories', 'lionwood' ),
			'singular_name'     => __( 'Whitepaper Category', 'lionwood' ),
			'search_items'      => __( 'Search Whitepaper Categories', 'lionwood' ),
			'all_items'         => __( 'All Whitepaper Categories', 'lionwood' ),
			'parent_item'       => __( 'Parent Category', 'lionwood' ),
			'parent_item_colon' => __( 'Parent Category:', 'lionwood' ),
			'edit_item'         => __( 'Edit Whitepaper Category', 'lionwood' ),
			'update_item'       => __( 'Update Whitepaper Category', 'lionwood' ),
			'add_new_item'      => __( 'Add New Whitepaper Category', 'lionwood' ),
			'new_item_name'     => __( 'New Whitepaper Category Name', 'lionwood' ),
			'menu_name'         => __( 'Categories', 'lionwood' ),
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
