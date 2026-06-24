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
			'name'               => __( 'News', 'lionwood' ),
			'singular_name'      => __( 'News', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New News', 'lionwood' ),
			'edit_item'          => __( 'Edit News', 'lionwood' ),
			'new_item'           => __( 'New News', 'lionwood' ),
			'view_item'          => __( 'View News', 'lionwood' ),
			'search_items'       => __( 'Search News', 'lionwood' ),
			'not_found'          => __( 'No news found', 'lionwood' ),
			'not_found_in_trash' => __( 'No news found in trash', 'lionwood' ),
			'menu_name'          => __( 'News', 'lionwood' ),
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
			'name'              => __( 'News Categories', 'lionwood' ),
			'singular_name'     => __( 'News Category', 'lionwood' ),
			'search_items'      => __( 'Search News Categories', 'lionwood' ),
			'all_items'         => __( 'All News Categories', 'lionwood' ),
			'parent_item'       => __( 'Parent Category', 'lionwood' ),
			'parent_item_colon' => __( 'Parent Category:', 'lionwood' ),
			'edit_item'         => __( 'Edit News Category', 'lionwood' ),
			'update_item'       => __( 'Update News Category', 'lionwood' ),
			'add_new_item'      => __( 'Add New News Category', 'lionwood' ),
			'new_item_name'     => __( 'New News Category Name', 'lionwood' ),
			'menu_name'         => __( 'Categories', 'lionwood' ),
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
