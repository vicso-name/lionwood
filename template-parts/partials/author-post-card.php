<?php
/**
 * Partial: Author Post Card
 * Used in author.php posts grid.
 */

defined( 'ABSPATH' ) || exit;

$post_id   = $args['post_id'] ?? 0;
if ( ! $post_id ) return;

$title     = esc_html( get_the_title( $post_id ) );
$permalink = esc_url( get_permalink( $post_id ) );
$thumb_id  = get_post_thumbnail_id( $post_id );
$thumb_url = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';
$date      = esc_html( get_the_date( 'j M, Y', $post_id ) );

$cats     = get_the_category( $post_id );
$cat_name = ! empty( $cats ) ? esc_html( $cats[0]->name ) : '';

$excerpt = esc_html( wp_trim_words( get_the_excerpt( $post_id ), 20 ) );
?>
<article class="apc-card">
    <a class="apc-card__link" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">

        <div class="apc-card__image">
            <?php if ( $thumb_url ) : ?>
                <img class="apc-card__img" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
            <?php endif; ?>
            <?php if ( $cat_name ) : ?>
                <div class="apc-card__tags">
                    <span class="apc-card__tag">#<?php echo esc_html( $cat_name ); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="apc-card__meta">
            <span class="apc-card__meta-date"><?php echo esc_html( $date ); ?></span>
        </div>

        <h3 class="apc-card__title"><?php echo esc_html( $title ); ?></h3>

        <?php if ( $excerpt ) : ?>
            <p class="apc-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
        <?php endif; ?>

    </a>
</article>
