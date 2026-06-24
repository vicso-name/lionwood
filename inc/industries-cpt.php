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
			'rewrite'            => [ 'slug' => 'industries' ],
			'query_var'          => true,
			'capability_type'    => 'post',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_industries' );
