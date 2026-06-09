<?php
/**
 * Block: Case Results
 *
 * ACF block slug : acf/case-results
 * bg #E9E9E9, heading + description row, then stat cards row
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 30 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_raw     = get_field( 'title' ) ?: __( 'Implementation & Results', 'theme' );
$title         = wp_kses( $title_raw, [ 'br' => [] ] );
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$items         = get_field( 'items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';
?>

<section
    class="cr-section"
    style="
        --cr-pt: <?php echo $pt; ?>px;
        --cr-pb: <?php echo $pb; ?>px;
        --cr-pt-mob: <?php echo $pt_mob; ?>px;
        --cr-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cr-section__container">

        <?php /* ── Row 1: Heading + Description ───────────────────────── */ ?>
        <div class="cr-header">
            <h2 class="cr-title"><?php echo $title; ?></h2>
            <?php if ( $description ) : ?>
                <p class="cr-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Row 2: Stat cards ──────────────────────────────────── */ ?>
        <?php if ( ! empty( $items ) ) : ?>
            <div class="cr-cards">
                <?php foreach ( $items as $item ) :
                    $value    = esc_html( $item['value'] ?? '' );
                    $label    = $item['label'] ? wp_kses( $item['label'], [ 'br' => [] ] ) : '';
                    $icon     = $item['icon'] ?? null;
                ?>
                    <div class="cr-card">
                        <div class="cr-card__top">
                            <?php if ( $icon ) : ?>
                                <img
                                    class="cr-card__icon"
                                    src="<?php echo esc_url( $icon['url'] ); ?>"
                                    alt="<?php echo esc_attr( $icon['alt'] ?: $label ); ?>"
                                    width="36"
                                    height="36"
                                    loading="lazy"
                                >
                            <?php elseif ( $value ) : ?>
                                <span class="cr-card__value"><?php echo $value; ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ( $label ) : ?>
                            <p class="cr-card__label"><?php echo $label; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div><!-- .cr-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
