<?php
/**
 * Template Name: Careers Page
 *
 * Assign this template to the page that acts as the Careers listing.
 * Its permalink is used automatically in single-career breadcrumbs
 * via lionwood_get_template_page_id() — so renaming the page slug
 * requires no code changes.
 */

get_header();

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
