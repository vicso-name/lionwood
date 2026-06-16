<?php
/**
 * Template: Single Post
 *
 * WordPress automatically uses this template for single blog posts (post type: post).
 * Breadcrumb trail: Home → Post Title
 */

get_header();

get_template_part( 'template-parts/partials/breadcrumbs' );

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer();
