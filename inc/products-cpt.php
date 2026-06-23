<?php
/**
 * Custom Post Type: Products
 *
 * - No archive (products displayed via a dedicated page template).
 * - Gutenberg blocks supported via show_in_rest + editor support.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'lionwood_register_cpt_products' ) ) {
    function lionwood_register_cpt_products(): void {
        $labels = [
            'name'               => __( 'Products',             'lionwood' ),
            'singular_name'      => __( 'Product',              'lionwood' ),
            'add_new'            => __( 'Add New',              'lionwood' ),
            'add_new_item'       => __( 'Add New Product',      'lionwood' ),
            'edit_item'          => __( 'Edit Product',         'lionwood' ),
            'new_item'           => __( 'New Product',          'lionwood' ),
            'view_item'          => __( 'View Product',         'lionwood' ),
            'search_items'       => __( 'Search Products',      'lionwood' ),
            'not_found'          => __( 'No products found',    'lionwood' ),
            'not_found_in_trash' => __( 'No products in trash', 'lionwood' ),
            'menu_name'          => __( 'Products',             'lionwood' ),
        ];

        register_post_type( 'product', [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
            'menu_icon'          => 'dashicons-products',
            'has_archive'        => false,
            'rewrite'            => [ 'slug' => 'product' ],
            'query_var'          => true,
            'capability_type'    => 'post',
        ] );
    }
}
add_action( 'init', 'lionwood_register_cpt_products' );
