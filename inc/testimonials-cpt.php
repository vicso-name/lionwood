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
			'name'               => __( 'Testimonials', 'theme' ),
			'singular_name'      => __( 'Testimonial', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Testimonial', 'theme' ),
			'edit_item'          => __( 'Edit Testimonial', 'theme' ),
			'new_item'           => __( 'New Testimonial', 'theme' ),
			'view_item'          => __( 'View Testimonial', 'theme' ),
			'search_items'       => __( 'Search Testimonials', 'theme' ),
			'not_found'          => __( 'No testimonials found', 'theme' ),
			'not_found_in_trash' => __( 'No testimonials found in trash', 'theme' ),
			'menu_name'          => __( 'Testimonials', 'theme' ),
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
