<?php
/**
 * Block: Partnership Partners
 * Slug: acf/partnership-partners
 *
 * Based on our-partners (op-) — same marquee + bars mechanic.
 * Differences: bg #F7F7F7 (light), no border-radius, logos NOT inverted.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$speed  = absint( get_field( 'speed' )              ?: 30 );
$logos  = get_field( 'logos' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$uid = 'pp-' . uniqid();
?>

<section
    class="pp-section"
    id="<?php echo esc_attr( $uid ); ?>"
    style="
        --pp-pt: <?php echo $pt; ?>px;
        --pp-pb: <?php echo $pb; ?>px;
        --pp-pt-mob: <?php echo $pt_mob; ?>px;
        --pp-pb-mob: <?php echo $pb_mob; ?>px;
        --pp-speed: <?php echo $speed; ?>s;
    "
    aria-label="<?php esc_attr_e( 'Partnership Partners', 'lionwood' ); ?>"
>
    <?php if ( ! empty( $logos ) ) : ?>
        <div class="pp-marquee-wrap">
        <div class="pp-track" aria-hidden="true" data-logo-count="<?php echo count( $logos ); ?>">
                <?php for ( $pass = 0; $pass < 2; $pass++ ) :
                    foreach ( $logos as $img ) :
                        if ( empty( $img['url'] ) ) continue;
                        $alt = ! empty( $img['alt'] ) ? $img['alt'] : ( ! empty( $img['title'] ) ? $img['title'] : '' );
                ?>
                    <div class="pp-logo">
                        <img
                            src="<?php echo esc_url( $img['url'] ); ?>"
                            alt="<?php echo esc_attr( $alt ); ?>"
                            width="<?php echo esc_attr( $img['width'] ); ?>"
                            height="<?php echo esc_attr( $img['height'] ); ?>"
                            decoding="async"
                        >
                    </div>
                    <div class="pp-divider" aria-hidden="true"></div>
                <?php endforeach;
                endfor; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
