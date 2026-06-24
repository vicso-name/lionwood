<?php
/**
 * Partial: Insights Card
 *
 * Used by insights_grid block (server render + AJAX response).
 *
 * $args:
 *   post_id  (int)  — required
 *   featured (bool) — first card in grid gets wider treatment
 */

defined( 'ABSPATH' ) || exit;

$post_id  = (int) ( $args['post_id']  ?? 0 );
$featured = (bool) ( $args['featured'] ?? false );

if ( ! $post_id ) return;

$post_type = get_post_type( $post_id );
$permalink = esc_url( get_permalink( $post_id ) );
$title     = esc_html( get_the_title( $post_id ) );
$thumb_id  = get_post_thumbnail_id( $post_id );
$thumb_src = $thumb_id
    ? wp_get_attachment_image_src( $thumb_id, $featured ? 'large' : 'medium_large' )
    : null;
$thumb_alt = $thumb_id
    ? esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: $title )
    : esc_attr( $title );

// Excerpt
$post    = get_post( $post_id );
$excerpt = '';
if ( $post ) {
    $excerpt = $post->post_excerpt
        ? esc_html( wp_trim_words( $post->post_excerpt, 20, '…' ) )
        : esc_html( wp_trim_words( strip_shortcodes( $post->post_content ), 20, '…' ) );
}

// Category tag — handles all three post types
$tax_map  = [
    'post'       => 'category',
    'news'       => 'news_category',
    'whitepaper' => 'whitepaper_category',
];
$taxonomy = $tax_map[ $post_type ] ?? 'category';
$tag_name = '';
if ( $taxonomy === 'category' ) {
    $cats     = get_the_category( $post_id );
    $tag_name = ! empty( $cats ) ? esc_html( $cats[0]->name ) : '';
} else {
    $terms    = get_the_terms( $post_id, $taxonomy );
    $tag_name = ( $terms && ! is_wp_error( $terms ) ) ? esc_html( $terms[0]->name ) : '';
}

// Post type label badge
$type_labels = [
    'post'       => __( 'Article', 'lionwood' ),
    'news'       => __( 'News', 'lionwood' ),
    'whitepaper' => __( 'Whitepaper', 'lionwood' ),
];
$type_label = $type_labels[ $post_type ] ?? '';

$card_class = 'ia-card ig-card' . ( $featured ? ' ia-card--featured ig-card--featured' : '' );
?>

<article class="<?php echo esc_attr( $card_class ); ?>" data-post-id="<?php echo $post_id; ?>">
    <a class="ia-card__link" href="<?php echo $permalink; ?>" aria-label="<?php echo esc_attr( $title ); ?>">

        <div class="ia-card__image-wrap">
            <?php if ( $thumb_src ) : ?>
                <img
                    class="ia-card__image"
                    src="<?php echo esc_url( $thumb_src[0] ); ?>"
                    width="<?php echo esc_attr( $thumb_src[1] ); ?>"
                    height="<?php echo esc_attr( $thumb_src[2] ); ?>"
                    alt="<?php echo $thumb_alt; ?>"
                    loading="<?php echo $featured ? 'eager' : 'lazy'; ?>"
                >
            <?php else : ?>
                <div class="ia-card__image-placeholder" aria-hidden="true"></div>
            <?php endif; ?>

            <?php if ( $tag_name ) : ?>
                <span class="ia-card__tag">#<?php echo $tag_name; ?></span>
            <?php endif; ?>

        </div>

        <div class="ia-card__body">
            <h3 class="ia-card__title"><?php echo $title; ?></h3>
            <?php if ( $excerpt ) : ?>
                <p class="ia-card__excerpt"><?php echo $excerpt; ?></p>
            <?php endif; ?>
        </div>

    </a>
</article>
