<?php
/**
 * Template: Single Solution
 *
 * File: single-solution.php
 * WordPress automatically uses this template for single posts of type 'solution'.
 */

get_header();

get_template_part( 'template-parts/partials/breadcrumbs' );

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
