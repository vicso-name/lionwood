<?php
/**
 * Block: Product List
 *
 * ACF block slug: acf/product-list
 *
 * Three-column sticky-scroll product showcase:
 *   Left  (330px) — product nav list
 *   Center(480px) — feature image
 *   Right (345px) — logo + title + excerpt + CTA
 *
 * Scroll mechanic: po-pin-wrapper height set by JS;
 * pl-pin-inner is position:sticky so the section "pins" while
 * the user scrolls through each product.
 *
 * Fields:
 *   padding_top / padding_bottom / padding_top_mob / padding_bottom_mob
 *   decor_bottom_enabled / decor_bottom_color
 *   products (relationship, post_type: product — optional; WP_Query fallback)
 *
 * Per-product ACF post meta:
 *   product_logo (image) — logo/label shown in right column
 */

defined( 'ABSPATH' ) || exit;

// ── Padding ───────────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )        ?: 60 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 60 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 40 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 40 );

$section_style = sprintf(
    '--pl-pt:%dpx; --pl-pb:%dpx; --pl-pt-mob:%dpx; --pl-pb-mob:%dpx;',
    $pt, $pb, $pt_mob, $pb_mob
);

// ── Products ──────────────────────────────────────────────────────────────────
$rel = get_field( 'products' ) ?: [];

if ( ! empty( $rel ) ) {
    $products = $rel;
} else {
    $q = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
    $products = $q->posts;
    wp_reset_postdata();
}

if ( empty( $products ) ) return;

$total = count( $products );
?>

<section class="pl-section" style="<?php echo esc_attr( $section_style ); ?>">

    <div class="pl-pin-wrapper" data-pl-count="<?php echo $total; ?>">
        <div class="pl-pin-inner">
            <div class="pl-grid">

                <?php /* ── LEFT: product nav list ───────────────────────── */ ?>
                <nav class="pl-nav" aria-label="<?php esc_attr_e( 'Products', 'lionwood' ); ?>">
                    <?php foreach ( $products as $idx => $product ) :
                        $name = esc_html( get_the_title( $product->ID ) );
                    ?>
                        <div class="pl-nav__item<?php echo $idx === 0 ? ' pl-nav__item--active' : ''; ?>"
                             data-pl-index="<?php echo $idx; ?>"
                             role="button"
                             tabindex="0"
                             aria-label="<?php echo $name; ?>">
                            <span class="pl-nav__name"><?php echo $name; ?></span>
                            <span class="pl-nav__dot" aria-hidden="true">
                                <svg width="6" height="6" viewBox="0 0 6 6" fill="none">
                                    <circle cx="3" cy="3" r="3" fill="#111319"/>
                                </svg>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </nav>

                <?php /* ── CENTER: feature image ────────────────────────── */ ?>
                <div class="pl-visual" aria-hidden="true">
                    <?php foreach ( $products as $idx => $product ) :
                        $thumb = get_the_post_thumbnail_url( $product->ID, 'large' );
                    ?>
                        <div class="pl-visual__slide<?php echo $idx === 0 ? ' pl-visual__slide--active' : ''; ?>"
                             data-pl-slide="<?php echo $idx; ?>">
                            <?php if ( $thumb ) : ?>
                                <img
                                    src="<?php echo esc_url( $thumb ); ?>"
                                    alt="<?php echo esc_attr( get_the_title( $product->ID ) ); ?>"
                                    loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>"
                                >
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php /* ── RIGHT: product info ──────────────────────────── */ ?>
                <div class="pl-info">
                    <?php foreach ( $products as $idx => $product ) :
                        $post_id   = $product->ID;
                        $title     = esc_html( get_the_title( $post_id ) );
                        $permalink = esc_url( get_permalink( $post_id ) );
                        $excerpt   = wp_strip_all_tags( get_the_excerpt( $post_id ) )
                                     ?: wp_strip_all_tags( wp_trim_words( get_the_content( null, false, $post_id ), 25 ) );
                        $excerpt   = esc_html( $excerpt );

                        $logo     = get_field( 'product_logo', $post_id ) ?: null;
                        $logo_url = is_array( $logo ) ? esc_url( $logo['url'] ?? '' ) : '';
                        $logo_alt = is_array( $logo ) ? esc_attr( $logo['alt'] ?? $title ) : $title;
                    ?>
                        <div class="pl-info__slide<?php echo $idx === 0 ? ' pl-info__slide--active' : ''; ?>"
                             data-pl-slide="<?php echo $idx; ?>">

                            <?php if ( $logo_url ) : ?>
                                <div class="pl-info__logo">
                                    <img src="<?php echo $logo_url; ?>"
                                         alt="<?php echo $logo_alt; ?>"
                                         height="60"
                                         loading="lazy">
                                </div>
                            <?php endif; ?>

                            <h3 class="pl-info__title"><?php echo $title; ?></h3>

                            <?php if ( $excerpt ) : ?>
                                <p class="pl-info__excerpt"><?php echo $excerpt; ?></p>
                            <?php endif; ?>

                            <a class="pl-info__btn" href="<?php echo $permalink; ?>">
                                <?php esc_html_e( 'Learn More', 'lionwood' ); ?>
                            </a>

                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>

    <?php get_template_part( 'template-parts/partials/decor-bottom' ); ?>
</section>
