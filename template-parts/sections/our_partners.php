<?php
/**
 * Block: Our Partners
 *
 * ACF block slug : acf/our-partners
 * Template file  : blocks/our-partners/our-partners.php
 *
 * Features:
 * - Red section with 60px top border-radius
 * - Infinite CSS marquee of partner logos
 * - Animated expanding bars at the bottom (IntersectionObserver + scroll)
 */

defined( 'ABSPATH' ) || exit;

// ── Fields ───────────────────────────────────────────────────────────────────
$pt      = absint( get_field( 'padding_top' )        ?: 100 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 60 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 60 );
$speed   = absint( get_field( 'speed' )              ?: 30 );
$logos   = get_field( 'logos' )                       ?: [];

$uid = 'op-' . uniqid();
?>

<section
    class="op-section"
    id="<?php echo esc_attr( $uid ); ?>"
    style="
        --op-pt: <?php echo $pt; ?>px;
        --op-pb: <?php echo $pb; ?>px;
        --op-pt-mob: <?php echo $pt_mob; ?>px;
        --op-pb-mob: <?php echo $pb_mob; ?>px;
        --op-speed: <?php echo $speed; ?>s;
    "
    aria-label="<?php esc_attr_e( 'Our Partners', 'lionwood' ); ?>"
>
    <?php /* ── Marquee ───────────────────────────────────────────────────── */ ?>
    <?php if ( ! empty( $logos ) ) : ?>
        <div class="op-marquee-wrap">

            <?php /* Edge fade masks */ ?>
            <div class="op-mask op-mask--left"  aria-hidden="true"></div>
            <div class="op-mask op-mask--right" aria-hidden="true"></div>

            <div class="op-track" aria-hidden="true" data-logo-count="<?php echo count( $logos ); ?>">
                <?php
                // Render twice for seamless loop — gallery returns image arrays directly
                for ( $pass = 0; $pass < 2; $pass++ ) :
                    foreach ( $logos as $img ) :
                        if ( empty( $img['url'] ) ) continue;
                        $alt = ! empty( $img['alt'] ) ? $img['alt'] : ( ! empty( $img['title'] ) ? $img['title'] : '' );
                ?>
                    <div class="op-logo">
                        <img
                            src="<?php echo esc_url( $img['url'] ); ?>"
                            alt="<?php echo esc_attr( $alt ); ?>"
                            width="<?php echo esc_attr( $img['width'] ); ?>"
                            height="<?php echo esc_attr( $img['height'] ); ?>"
                            decoding="async"
                        >
                    </div>
                    <div class="op-divider" aria-hidden="true"></div>
                <?php
                    endforeach;
                endfor;
                ?>
            </div>
        </div>
    <?php endif; ?>

    <?php /* ── Animated bars ────────────────────────────────────────────── */ ?>
    <div class="op-bars" aria-hidden="true">
        <span class="op-bar" data-base="51.8"></span>
        <span class="op-bar" data-base="70.1"></span>
        <span class="op-bar" data-base="83.2"></span>
        <span class="op-bar" data-base="92.6"></span>
        <span class="op-bar" data-base="97.9"></span>
    </div>

</section>
