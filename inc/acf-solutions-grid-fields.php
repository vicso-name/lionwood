<?php
/**
 * ACF field group: Solutions Grid block.
 * Location: Block → acf/solutions-grid
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'lionwood_register_solutions_grid_fields' );
function lionwood_register_solutions_grid_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_solutions_grid_block',
        'title'  => 'Solutions Grid',
        'fields' => [

            // ── Spacing ───────────────────────────────────────────────────────
            [
                'key'           => 'field_sg_padding_top',
                'label'         => 'Padding Top (px)',
                'name'          => 'padding_top',
                'type'          => 'number',
                'default_value' => 100,
                'min'           => 0,
                'step'          => 10,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_sg_padding_bottom',
                'label'         => 'Padding Bottom (px)',
                'name'          => 'padding_bottom',
                'type'          => 'number',
                'default_value' => 100,
                'min'           => 0,
                'step'          => 10,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_sg_padding_top_mob',
                'label'         => 'Padding Top Mobile (px)',
                'name'          => 'padding_top_mob',
                'type'          => 'number',
                'default_value' => 70,
                'min'           => 0,
                'step'          => 10,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_sg_padding_bottom_mob',
                'label'         => 'Padding Bottom Mobile (px)',
                'name'          => 'padding_bottom_mob',
                'type'          => 'number',
                'default_value' => 70,
                'min'           => 0,
                'step'          => 10,
                'wrapper'       => [ 'width' => '25' ],
            ],

            // ── Heading ───────────────────────────────────────────────────────
            [
                'key'          => 'field_sg_title_top',
                'label'        => 'Heading — Top Line',
                'name'         => 'title_top',
                'type'         => 'text',
                'wrapper'      => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_sg_title_bottom',
                'label'        => 'Heading — Bottom Line',
                'name'         => 'title_bottom',
                'type'         => 'text',
                'wrapper'      => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_sg_marquee_text',
                'label'        => 'Marquee Text',
                'name'         => 'marquee_text',
                'type'         => 'text',
                'default_value'=> 'Solutions',
                'wrapper'      => [ 'width' => '50' ],
            ],

            // ── Per page + decoration ─────────────────────────────────────────
            [
                'key'           => 'field_sg_per_page',
                'label'         => 'Posts Per Page',
                'name'          => 'per_page',
                'type'          => 'number',
                'default_value' => 6,
                'min'           => 3,
                'step'          => 3,
                'instructions'  => 'How many solutions to load per page / load more click.',
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_sg_decor_enabled',
                'label'         => 'Enable Bottom Decoration',
                'name'          => 'decor_bottom_enabled',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_sg_decor_color',
                'label'         => 'Decoration Color',
                'name'          => 'decor_bottom_color',
                'type'          => 'color_picker',
                'default_value' => '#F7F7F7',
                'wrapper'       => [ 'width' => '25' ],
            ],

        ],
        'location' => [ [ [
            'param'    => 'block',
            'operator' => '==',
            'value'    => 'acf/solutions-grid',
        ] ] ],
        'active' => true,
    ] );
}
