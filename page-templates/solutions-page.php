<?php
/**
 * Template Name: Solutions Page
 *
 * Assign this template to the Solutions archive page.
 * Its permalink is used automatically in single-solution breadcrumbs
 * via lionwood_get_template_page_id() — renaming the page slug requires no code changes.
 */

get_header();

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
