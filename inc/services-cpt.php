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
			'name'               => __( 'Services', 'lionwood' ),
			'singular_name'      => __( 'Service', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New Service', 'lionwood' ),
			'edit_item'          => __( 'Edit Service', 'lionwood' ),
			'new_item'           => __( 'New Service', 'lionwood' ),
			'view_item'          => __( 'View Service', 'lionwood' ),
			'search_items'       => __( 'Search Services', 'lionwood' ),
			'not_found'          => __( 'No services found', 'lionwood' ),
			'not_found_in_trash' => __( 'No services found in trash', 'lionwood' ),
			'parent_item_colon'  => __( 'Parent Service:', 'lionwood' ),
			'menu_name'          => __( 'Services', 'lionwood' ),
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
