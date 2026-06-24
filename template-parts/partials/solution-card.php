<?php
/**
 * Partial: Solution Card
 * Used by solutions_grid block and sg_ajax handler.
 */

defined( 'ABSPATH' ) || exit;

$post_id   = (int) ( $args['post_id'] ?? 0 );
if ( ! $post_id ) return;

$title     = esc_html( get_the_title( $post_id ) );
$permalink = esc_url( get_permalink( $post_id ) );
$thumb_id  = get_post_thumbnail_id( $post_id );
$thumb_url = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';
$thumb_alt = $thumb_id
    ? esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: $title )
    : esc_attr( $title );

$country   = esc_html( get_field( 'solution_country', $post_id ) ?: '' );

// Category tags
$terms         = get_the_terms( $post_id, 'solution_category' );
$terms         = ( $terms && ! is_wp_error( $terms ) ) ? $terms : [];
$services_str  = implode( ', ', array_map( fn( $t ) => esc_html( $t->name ), $terms ) );
?>

<article class="sg-card">
    <a class="sg-card__link" href="<?php echo $permalink; ?>" aria-label="<?php echo $title; ?>">

        <div class="sg-card__image-wrap">
            <?php if ( $thumb_url ) : ?>
                <img
                    class="sg-card__image"
                    src="<?php echo $thumb_url; ?>"
                    alt="<?php echo $thumb_alt; ?>"
                    loading="lazy"
                >
            <?php else : ?>
                <div class="sg-card__image-placeholder" aria-hidden="true"></div>
            <?php endif; ?>

            <?php if ( ! empty( $terms ) ) : ?>
                <div class="sg-card__tags">
                    <?php foreach ( $terms as $term ) : ?>
                        <span class="sg-card__tag">#<?php echo esc_html( $term->name ); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="sg-card__body">
            <?php if ( $country ) : ?>
                <span class="sg-card__country"><?php echo $country; ?></span>
            <?php endif; ?>
            <h3 class="sg-card__title"><?php echo $title; ?></h3>
            <?php if ( $services_str ) : ?>
                <p class="sg-card__services"><?php echo $services_str; ?></p>
            <?php endif; ?>
        </div>

    </a>
</article>
