<?php
/**
 * Template Name: Insights & Articles Page
 *
 * Assign this template to the page that acts as the Insights listing.
 * Its permalink is used automatically in single-whitepaper and single-news
 * breadcrumbs via lionwood_get_template_page_id() — so renaming the page
 * slug requires no code changes.
 */

get_header();

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
