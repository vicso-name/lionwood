<?php
/**
 * Custom Post Type: Solutions
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'theme_register_cpt_solutions' ) ) {
    function theme_register_cpt_solutions(): void {
        $labels = [
            'name'               => __( 'Solutions', 'lionwood' ),
            'singular_name'      => __( 'Solution', 'lionwood' ),
            'add_new'            => __( 'Add New', 'lionwood' ),
            'add_new_item'       => __( 'Add New Solution', 'lionwood' ),
            'edit_item'          => __( 'Edit Solution', 'lionwood' ),
            'new_item'           => __( 'New Solution', 'lionwood' ),
            'view_item'          => __( 'View Solution', 'lionwood' ),
            'search_items'       => __( 'Search Solutions', 'lionwood' ),
            'not_found'          => __( 'No solutions found', 'lionwood' ),
            'not_found_in_trash' => __( 'No solutions found in trash', 'lionwood' ),
            'menu_name'          => __( 'Solutions', 'lionwood' ),
        ];

        register_post_type( 'solution', [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
            'hierarchical'       => false,
            'menu_icon'          => 'dashicons-lightbulb',
            'has_archive'        => false,
            'rewrite'            => [ 'slug' => 'solutions' ],
            'query_var'          => true,
            'capability_type'    => 'post',
        ] );
    }
}
add_action( 'init', 'theme_register_cpt_solutions' );

if ( ! function_exists( 'theme_register_tax_solution_category' ) ) {
    function theme_register_tax_solution_category(): void {
        register_taxonomy( 'solution_category', 'solution', [
            'labels' => [
                'name'          => __( 'Solution Categories', 'lionwood' ),
                'singular_name' => __( 'Solution Category', 'lionwood' ),
                'search_items'  => __( 'Search Solution Categories', 'lionwood' ),
                'all_items'     => __( 'All Solution Categories', 'lionwood' ),
                'edit_item'     => __( 'Edit Solution Category', 'lionwood' ),
                'add_new_item'  => __( 'Add New Solution Category', 'lionwood' ),
                'menu_name'     => __( 'Categories', 'lionwood' ),
            ],
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => [ 'slug' => 'solution-category' ],
        ] );
    }
}
add_action( 'init', 'theme_register_tax_solution_category' );
