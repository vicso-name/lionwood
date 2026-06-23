<?php
/**
 * Custom Post Type: News
 * Taxonomy: News Category (hierarchical)
 */

defined( 'ABSPATH' ) || exit;

// ── CPT ───────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'theme_register_cpt_news' ) ) {
	function theme_register_cpt_news(): void {
		$labels = [
			'name'               => __( 'News', 'theme' ),
			'singular_name'      => __( 'News', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New News', 'theme' ),
			'edit_item'          => __( 'Edit News', 'theme' ),
			'new_item'           => __( 'New News', 'theme' ),
			'view_item'          => __( 'View News', 'theme' ),
			'search_items'       => __( 'Search News', 'theme' ),
			'not_found'          => __( 'No news found', 'theme' ),
			'not_found_in_trash' => __( 'No news found in trash', 'theme' ),
			'menu_name'          => __( 'News', 'theme' ),
		];

		register_post_type( 'news', [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
			'taxonomies'         => [ 'news_category' ],
			'menu_icon'          => 'dashicons-megaphone',
			'has_archive'        => true,
			'rewrite'            => [ 'slug' => 'news' ],
			'query_var'          => true,
			'capability_type'    => 'post',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_news' );


// ── Taxonomy: News Category ───────────────────────────────────────────────────
if ( ! function_exists( 'theme_register_tax_news_category' ) ) {
	function theme_register_tax_news_category(): void {
		$labels = [
			'name'              => __( 'News Categories', 'theme' ),
			'singular_name'     => __( 'News Category', 'theme' ),
			'search_items'      => __( 'Search News Categories', 'theme' ),
			'all_items'         => __( 'All News Categories', 'theme' ),
			'parent_item'       => __( 'Parent Category', 'theme' ),
			'parent_item_colon' => __( 'Parent Category:', 'theme' ),
			'edit_item'         => __( 'Edit News Category', 'theme' ),
			'update_item'       => __( 'Update News Category', 'theme' ),
			'add_new_item'      => __( 'Add New News Category', 'theme' ),
			'new_item_name'     => __( 'New News Category Name', 'theme' ),
			'menu_name'         => __( 'Categories', 'theme' ),
		];

		register_taxonomy( 'news_category', [ 'news' ], [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => [ 'slug' => 'news/category' ],
		] );
	}
}
add_action( 'init', 'theme_register_tax_news_category' );
