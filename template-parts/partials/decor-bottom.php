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

if (!get_field('decor_bottom_enabled')) return;

$color = get_field('decor_bottom_color') ?: '#ffffff';
?>
<div class="decor-bottom" style="background-color:<?= esc_attr($color); ?>;"></div>
