<?php
/**
 * Block: Value Section
 *
 * ACF block slug : acf/value-section
 * Template file  : blocks/value-section/value-section.php
 *
 * Same visual base as Our Partners:
 * - Red bg, 60px top border-radius
 * - Row of value stats with dividers
 * - Animated expanding bars at the bottom (scroll-driven via JS)
 */

defined( 'ABSPATH' ) || exit;

$pt      = absint( get_field( 'padding_top' )        ?: 100 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 80 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 60 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 48 );
$items   = get_field( 'items' ) ?: [];
?>

<section
    class="vs-section"
    style="
        --vs-pt: <?php echo $pt; ?>px;
        --vs-pb: <?php echo $pb; ?>px;
        --vs-pt-mob: <?php echo $pt_mob; ?>px;
        --vs-pb-mob: <?php echo $pb_mob; ?>px;
    "
    aria-label="<?php esc_attr_e( 'Our Values', 'theme' ); ?>"
>
    <?php /* ── Value items row ────────────────────────────────────────────── */ ?>
    <?php if ( ! empty( $items ) ) : ?>
        <div class="vs-row">
            <?php foreach ( $items as $i => $item ) :
                $number = esc_html( $item['number'] ?? '' );
                $label_raw = $item['label'] ?? '';
                $label = $label_raw ? wp_kses( $label_raw, [ 'br' => [] ] ) : '';
                if ( ! $number ) continue;
            ?>
                <?php if ( $i > 0 ) : ?>
                    <div class="vs-divider" aria-hidden="true"></div>
                <?php endif; ?>

                <div class="vs-item" data-index="<?php echo $i; ?>">
                    <span class="vs-item__number"><?php echo $number; ?></span>
                    <?php if ( $label ) : ?>
                        <p class="vs-item__label"><?php echo $label; ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php /* ── Animated bars ────────────────────────────────────────────── */ ?>
    <div class="vs-bars" aria-hidden="true">
        <span class="vs-bar" data-base="51.8"></span>
        <span class="vs-bar" data-base="70.1"></span>
        <span class="vs-bar" data-base="83.2"></span>
        <span class="vs-bar" data-base="92.6"></span>
        <span class="vs-bar" data-base="97.9"></span>
    </div>

</section>
