<?php
/**
 * Template: Single Case Study
 *
 * File: single-case_study.php
 * WordPress automatically uses this template for single posts of type 'case_study'.
 */

get_header();

get_template_part( 'template-parts/partials/breadcrumbs' );

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
