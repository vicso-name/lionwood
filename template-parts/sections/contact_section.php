<?php
/**
 * Block: Contact Section
 *
 * ACF block slug: acf/contact-section
 * Collects ACF field values and delegates rendering to the shared partial.
 */

defined( 'ABSPATH' ) || exit;

$grid_labels = [];
for ( $i = 1; $i <= 5; $i++ ) {
    $grid_labels[ $i ] = get_field( 'grid_label_' . $i ) ?: '';
}

get_template_part( 'template-parts/partials/contact-section', null, [
    'padding_top'        => absint( get_field( 'padding_top' )        ?: 100 ),
    'padding_bottom'     => absint( get_field( 'padding_bottom' )     ?: 200 ),
    'padding_top_mob'    => absint( get_field( 'padding_top_mob' )    ?: 70  ),
    'padding_bottom_mob' => absint( get_field( 'padding_bottom_mob' ) ?: 140 ),
    'title_top'          => get_field( 'title_top' )    ?: __( 'Ready to Accelerate', 'lionwood' ),
    'title_bottom'       => get_field( 'title_bottom' ) ?: "Your Business Growth?\nContact Us.",
    'description'        => get_field( 'description' )  ?: '',
    'grid_labels'        => $grid_labels,
    'form_shortcode'     => get_field( 'form_shortcode' ) ?: '[contact-form-7 id="3b827c0" title="Contact Form"]',
    'terms_link'         => get_field( 'terms_link' ),
    'decor_enabled'      => (bool) get_field( 'decor_bottom_enabled' ),
    'decor_color'        => get_field( 'decor_bottom_color' ) ?: '#F7F7F7',
] );
