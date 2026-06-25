<?php
/**
 * ACF: populate CF7 form choices for the "Form Shortcode" select field
 * (field_cs_form_shortcode) in the Contact Section block.
 *
 * Choices are built dynamically from all published CF7 forms.
 * Key = full shortcode string; Label = form title.
 * This keeps backward compatibility: existing stored values (full shortcode)
 * match the choice keys and are pre-selected automatically.
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'acf/load_field/key=field_cs_form_shortcode', function ( array $field ): array {
    $forms = get_posts( [
        'post_type'      => 'wpcf7_contact_form',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ] );

    if ( empty( $forms ) ) {
        return $field;
    }

    $choices = [];
    foreach ( $forms as $form ) {
        $shortcode            = sprintf( '[contact-form-7 id="%s" title="%s"]', $form->post_name, esc_attr( $form->post_title ) );
        $choices[ $shortcode ] = $form->post_title;
    }

    $field['choices'] = $choices;

    return $field;
} );
