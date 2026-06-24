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
			'name'               => __( 'Careers', 'lionwood' ),
			'singular_name'      => __( 'Career', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New Career', 'lionwood' ),
			'edit_item'          => __( 'Edit Career', 'lionwood' ),
			'new_item'           => __( 'New Career', 'lionwood' ),
			'view_item'          => __( 'View Career', 'lionwood' ),
			'search_items'       => __( 'Search Careers', 'lionwood' ),
			'not_found'          => __( 'No careers found', 'lionwood' ),
			'not_found_in_trash' => __( 'No careers found in trash', 'lionwood' ),
			'menu_name'          => __( 'Careers', 'lionwood' ),
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
