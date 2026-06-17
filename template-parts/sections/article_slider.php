<?php
/**
 * Block: Article Slider
 * Slug: acf/article-slider
 *
 * Gallery-based image slider for use inside article content.
 * Desktop: arrows inside bottom-right of slide.
 * Mobile:  arrows outside, centered below slide.
 */

defined( 'ABSPATH' ) || exit;

$images = get_field( 'images' ) ?: [];
if ( empty( $images ) ) return;

$uid = 'as-' . uniqid();

$arrow_prev = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <path d="M14.4887 8.69583L2.89453 8.69582M7.24236 13.0437L2.89453 8.69582L7.24236 4.348" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

$arrow_next = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <path d="M2.90257 8.69548L14.4968 8.69548M10.1489 4.34766L14.4968 8.69548L10.1489 13.0433" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<div class="as-wrap" id="<?php echo esc_attr( $uid ); ?>" data-as-slider>

    <div class="as-slider swiper">
        <div class="swiper-wrapper">
            <?php foreach ( $images as $image ) :
                $url = esc_url( $image['url'] );
                $alt = esc_attr( $image['alt'] ?: '' );
            ?>
                <div class="swiper-slide as-slide">
                    <img
                        src="<?php echo $url; ?>"
                        alt="<?php echo $alt; ?>"
                        width="700"
                        height="442"
                        loading="lazy"
                    >
                </div>
            <?php endforeach; ?>
        </div>

        <?php /* Desktop arrows — inside slide, bottom-right */ ?>
        <div class="as-nav as-nav--desktop">
            <button class="as-btn as-btn--prev" aria-label="<?php esc_attr_e( 'Previous', 'theme' ); ?>"><?php echo $arrow_prev; ?></button>
            <button class="as-btn as-btn--next" aria-label="<?php esc_attr_e( 'Next', 'theme' ); ?>"><?php echo $arrow_next; ?></button>
        </div>
    </div>

    <?php /* Mobile arrows — outside, below slider */ ?>
    <div class="as-nav as-nav--mobile">
        <button class="as-btn as-btn--prev" aria-label="<?php esc_attr_e( 'Previous', 'theme' ); ?>"><?php echo $arrow_prev; ?></button>
        <button class="as-btn as-btn--next" aria-label="<?php esc_attr_e( 'Next', 'theme' ); ?>"><?php echo $arrow_next; ?></button>
    </div>

</div>
