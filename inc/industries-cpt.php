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
			'name'               => __( 'Industries', 'theme' ),
			'singular_name'      => __( 'Industry', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Industry', 'theme' ),
			'edit_item'          => __( 'Edit Industry', 'theme' ),
			'new_item'           => __( 'New Industry', 'theme' ),
			'view_item'          => __( 'View Industry', 'theme' ),
			'search_items'       => __( 'Search Industries', 'theme' ),
			'not_found'          => __( 'No industries found', 'theme' ),
			'not_found_in_trash' => __( 'No industries found in trash', 'theme' ),
			'menu_name'          => __( 'Industries', 'theme' ),
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
			'has_archive'        => true,
			'rewrite'            => [ 'slug' => 'industries' ],
			'query_var'          => true,
			'capability_type'    => 'post',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_industries' );
