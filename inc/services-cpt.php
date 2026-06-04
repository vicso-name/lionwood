<?php
/**
 * Custom Post Type: Services
 *
 * File: inc/cpt/services.php
 * Include in functions.php:
 *   require_once get_template_directory() . '/inc/cpt/services.php';
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'theme_register_cpt_services' ) ) {
	function theme_register_cpt_services(): void {
		$labels = [
			'name'               => __( 'Services', 'theme' ),
			'singular_name'      => __( 'Service', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Service', 'theme' ),
			'edit_item'          => __( 'Edit Service', 'theme' ),
			'new_item'           => __( 'New Service', 'theme' ),
			'view_item'          => __( 'View Service', 'theme' ),
			'search_items'       => __( 'Search Services', 'theme' ),
			'not_found'          => __( 'No services found', 'theme' ),
			'not_found_in_trash' => __( 'No services found in trash', 'theme' ),
			'parent_item_colon'  => __( 'Parent Service:', 'theme' ),
			'menu_name'          => __( 'Services', 'theme' ),
		];

		register_post_type( 'service', [
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,   // Gutenberg enabled
			'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions' ],
			'hierarchical'        => true,   // parent/child like pages
			'menu_icon'           => 'dashicons-admin-tools',
			'has_archive'         => false,
			'rewrite'             => [ 'slug' => 'services' ],
			'query_var'           => true,
			'capability_type'     => 'page',
		] );
	}
}
add_action( 'init', 'theme_register_cpt_services' );
