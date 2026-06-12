<?php
/**
 * Custom Post Type: Careers
 *
 * File: inc/careers-cpt.php
 * Include in functions.php:
 *   require_once get_template_directory() . '/inc/careers-cpt.php';
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'theme_register_cpt_career' ) ) {
	function theme_register_cpt_career(): void {
		$labels = [
			'name'               => __( 'Careers', 'theme' ),
			'singular_name'      => __( 'Career', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Career', 'theme' ),
			'edit_item'          => __( 'Edit Career', 'theme' ),
			'new_item'           => __( 'New Career', 'theme' ),
			'view_item'          => __( 'View Career', 'theme' ),
			'search_items'       => __( 'Search Careers', 'theme' ),
			'not_found'          => __( 'No careers found', 'theme' ),
			'not_found_in_trash' => __( 'No careers found in trash', 'theme' ),
			'menu_name'          => __( 'Careers', 'theme' ),
		];

		register_post_type( 'career', [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'supports'           => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'          => 'dashicons-id-alt',
			'has_archive'        => false,
			'rewrite'            => [ 'slug' => 'career', 'with_front' => false ],
			'query_var'          => true,
			'capability_type'    => 'post',
		] );
	}
}

add_action( 'init', 'theme_register_cpt_career' );
