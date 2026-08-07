<?php
/**
 * Template Name: Products
 *
 * Page template for displaying the products listing.
 * Assign this template to any page via the WordPress editor sidebar.
 * Build the layout using ACF Gutenberg blocks.
 */

get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
