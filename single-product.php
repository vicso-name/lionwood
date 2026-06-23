<?php
/**
 * Template: Single Product
 *
 * Renders a single product post.
 * Build the layout using ACF Gutenberg blocks.
 */

get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
