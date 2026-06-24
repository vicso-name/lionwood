<?php
/**
 * ACF field group: Solution single post meta.
 * Location: Post Type → solution
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'lionwood_register_solution_post_fields' );
function lionwood_register_solution_post_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_solution_meta',
        'title'  => 'Solution Details',
        'fields' => [
            [
                'key'          => 'field_solution_country',
                'label'        => 'Country',
                'name'         => 'solution_country',
                'type'         => 'text',
                'instructions' => 'e.g. Ukraine, Sweden, Germany',
            ],
        ],
        'location' => [ [ [
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'solution',
        ] ] ],
        'position' => 'side',
        'active'   => true,
    ] );
}
