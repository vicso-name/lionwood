<?php
/**
 * Template: Single Career
 *
 * File: single-career.php
 * WordPress automatically uses this template for single posts of type 'career'.
 */

get_header();

get_template_part( 'template-parts/partials/breadcrumbs' );

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
