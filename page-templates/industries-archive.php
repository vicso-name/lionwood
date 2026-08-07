<?php
/**
 * Template Name: Industries Archive
 *
 * Assign this template to the page that acts as the Industries listing.
 * Its permalink is used automatically in single-industry breadcrumbs
 * via lionwood_get_template_page_id() — so renaming the page slug
 * requires no code changes.
 */

get_header();

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
