<?php
/**
 * Block: About Map Section
 * Slug: acf/about-map
 *
 * Based on map-section pattern.
 * Left col: label badge + stats repeater (value + label) with counter animation.
 * Right col: map image flush to viewport right edge.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$label  = get_field( 'label' );
$title  = get_field( 'title' );
$stats  = get_field( 'stats' ) ?: [];
$map    = get_field( 'map' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="ams-section"
    style="
        --ams-pt: <?php echo $pt; ?>px;
        --ams-pb: <?php echo $pb; ?>px;
        --ams-pt-mob: <?php echo $pt_mob; ?>px;
        --ams-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ams-section__container">

        <?php /* ── Title (same as ms-title) ── */ ?>
        <?php if ( $title ) : ?>
            <div class="ams-title">
                <?php echo wp_kses_post( $title ); ?>
            </div>
        <?php endif; ?>

        <div class="ams-row">

            <?php /* ── Left col: badge + stats ── */ ?>
            <div class="ams-stats">

                <?php if ( $label ) : ?>
                    <span class="ams-stats__label"><?php echo esc_html( $label ); ?></span>
                <?php endif; ?>

                <?php if ( $stats ) : ?>
                    <ul class="ams-stats__list">
                        <?php foreach ( $stats as $stat ) :
                            $value      = esc_html( $stat['value']      ?? '' );
                            $suffix     = esc_html( $stat['suffix']     ?? '' );
                            $descriptor = esc_html( $stat['descriptor'] ?? '' );
                            // Extract numeric part for counter animation
                            $numeric    = preg_replace( '/[^0-9.]/', '', $value );
                        ?>
                        <li class="ams-stats__item">
                            <div class="ams-stats__value-wrap">
                                <span
                                    class="ams-stats__value"
                                    data-target="<?php echo esc_attr( $numeric ); ?>"
                                    data-suffix="<?php echo esc_attr( $suffix ); ?>"
                                    aria-label="<?php echo esc_attr( $value . $suffix ); ?>"
                                >0<?php echo $suffix; ?></span>
                            </div>
                            <?php if ( $descriptor ) : ?>
                                <span class="ams-stats__descriptor"><?php echo $descriptor; ?></span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div><!-- .ams-stats -->

            <?php /* ── Map — flush to right edge ── */ ?>
            <?php if ( $map ) : ?>
                <div class="ams-map">
                    <img
                        src="<?php echo esc_url( $map['url'] ); ?>"
                        alt="<?php echo esc_attr( $map['alt'] ?: __( 'World map', 'lionwood' ) ); ?>"
                        width="968"
                        height="502"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>

        </div><!-- .ams-row -->

    </div><!-- .ams-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
