<?php
/**
 * Bottom decorative arc.
 *
 * ACF fields expected on the calling block:
 *   decor_bottom_enabled  — true_false
 *   decor_bottom_color    — color_picker
 *
 * Usage inside a section template:
 *   get_template_part('template-parts/partials/decor-bottom');
 */

// When called with $args['color'] (partial context), use it directly.
// When called from an ACF block without $args, fall back to get_field().
if ( ! empty( $args['color'] ) ) {
    $color = $args['color'];
} else {
    if ( ! get_field( 'decor_bottom_enabled' ) ) return;
    $color = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
}
?>
<div class="decor-bottom" style="background-color:<?php echo esc_attr( $color ); ?>;"></div>
