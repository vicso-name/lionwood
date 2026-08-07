<?php
/**
 * Template: Single Industry
 *
 * File: single-industry.php
 * WordPress automatically uses this template for single posts of type 'industry'.
 */

get_header();

get_template_part( 'template-parts/partials/breadcrumbs' );

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
