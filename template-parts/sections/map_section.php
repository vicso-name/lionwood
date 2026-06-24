<?php
/**
 * Block: Map Section
 *
 * ACF block slug : acf/map-section
 * Template file  : blocks/map-section/map-section.php
 *
 * Layout:
 *   Desktop — title (max-width 1195px, first line indented) → row [text 303px | map flush-right]
 *   Mobile  — title → map (flush right) → text below
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title   = get_field( 'title' ) ?: ''; // wysiwyg

$para_1_raw = get_field( 'text_para_1' );
$para_2_raw = get_field( 'text_para_2' );
$para_1 = $para_1_raw ? wp_kses( $para_1_raw, [ 'br' => [] ] ) : '';
$para_2 = $para_2_raw ? wp_kses( $para_2_raw, [ 'br' => [] ] ) : '';

$map = get_field( 'map' );
?>

<section
    class="ms-section"
    style="
        --ms-pt: <?php echo $pt; ?>px;
        --ms-pb: <?php echo $pb; ?>px;
        --ms-pt-mob: <?php echo $pt_mob; ?>px;
        --ms-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ms-section__container">

        <?php /* ── Title ────────────────────────────────────────────────── */ ?>
        <?php if ( $title ) : ?>
            <div class="ms-title">
                <?php echo wp_kses_post( $title ); ?>
            </div>
        <?php endif; ?>

        <?php /* ── Content row ─────────────────────────────────────────── */ ?>
        <div class="ms-row">

            <?php /* Left text — desktop only above map on mobile */ ?>
            <div class="ms-text">
                <?php if ( $para_1 ) : ?>
                    <p class="ms-text__para"><?php echo $para_1; ?></p>
                <?php endif; ?>
                <?php if ( $para_2 ) : ?>
                    <p class="ms-text__para"><?php echo $para_2; ?></p>
                <?php endif; ?>
            </div>

            <?php /* Map — flush to right edge of container */ ?>
            <?php if ( $map ) : ?>
                <div class="ms-map">
                    <img
                        src="<?php echo esc_url( $map['url'] ); ?>"
                        alt="<?php echo esc_attr( $map['alt'] ?: __( 'World map', 'lionwood' ) ); ?>"
                        width="968"
                        height="502"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>

        </div><!-- .ms-row -->

    </div><!-- .ms-section__container -->
</section>
