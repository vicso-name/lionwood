<?php
/**
 * Custom Post Type: Testimonials
 *
 * File: inc/cpt/testimonials.php
 * Include in functions.php:  require_once get_template_directory() . '/inc/cpt/testimonials.php';
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'theme_register_cpt_testimonials' ) ) {
	function theme_register_cpt_testimonials(): void {
		$labels = [
			'name'               => __( 'Testimonials', 'lionwood' ),
			'singular_name'      => __( 'Testimonial', 'lionwood' ),
			'add_new'            => __( 'Add New', 'lionwood' ),
			'add_new_item'       => __( 'Add New Testimonial', 'lionwood' ),
			'edit_item'          => __( 'Edit Testimonial', 'lionwood' ),
			'new_item'           => __( 'New Testimonial', 'lionwood' ),
			'view_item'          => __( 'View Testimonial', 'lionwood' ),
			'search_items'       => __( 'Search Testimonials', 'lionwood' ),
			'not_found'          => __( 'No testimonials found', 'lionwood' ),
			'not_found_in_trash' => __( 'No testimonials found in trash', 'lionwood' ),
			'menu_name'          => __( 'Testimonials', 'lionwood' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => false,   // no front-end archive/single
			'publicly_queryable'  => false,
			'show_ui'             => true,    // visible in WP admin
			'show_in_menu'        => true,
			'show_in_rest'        => false,   // no Gutenberg editor
			'supports'            => [ 'title' ], // title = reviewer name for admin list
			'menu_icon'           => 'dashicons-format-quote',
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'capability_type'     => 'post',
		];

		register_post_type( 'testimonial', $args );
	}
}

add_action( 'init', 'theme_register_cpt_testimonials' );
