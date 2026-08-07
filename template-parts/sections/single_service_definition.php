<?php
/**
 * Block: Single Service Definition
 *
 * ACF block slug : acf/single-service-definition
 * Template file  : blocks/single-service-definition/single-service-definition.php
 *
 * Desktop: equal-height card grid (CSS align-items: stretch)
 * Mobile:  Swiper slider with numeric counter (01 / 04)
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 160 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_top     = get_field( 'title_top' )    ?: __( 'When Do You Need', 'lionwood' );
$title_bottom  = get_field( 'title_bottom' ) ?: '';
$items         = get_field( 'items' )        ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';
$columns       = absint( get_field( 'columns' ) ?: 4 );
$total         = count( $items );

if ( empty( $items ) ) return;
?>

<section
    class="ssd-section"
    style="
        --ssd-pt: <?php echo $pt; ?>px;
        --ssd-pb: <?php echo $pb; ?>px;
        --ssd-pt-mob: <?php echo $pt_mob; ?>px;
        --ssd-pb-mob: <?php echo $pb_mob; ?>px;
        --ssd-columns: <?php echo $columns; ?>;
    "
>
    <div class="ssd-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="ssd-heading">
            <span class="ssd-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <?php if ( $title_bottom ) : ?>
                <span class="ssd-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
            <?php endif; ?>
        </div>

        <?php /* ── Desktop grid ─────────────────────────────────────────── */ ?>
        <div class="ssd-grid">
            <?php foreach ( $items as $item ) :
                $icon  = $item['icon']        ?? null;
                $title = esc_html( $item['title']       ?? '' );
                $desc  = $item['description'] ?? '';
                $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
            ?>
                <div class="ssd-card">
                    <?php if ( $icon ) : ?>
                        <div class="ssd-card__icon">
                            <img
                                src="<?php echo esc_url( $icon['url'] ); ?>"
                                alt="<?php echo esc_attr( $icon['alt'] ?: $title ); ?>"
                                width="60"
                                height="60"
                                loading="lazy"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ( $title ) : ?>
                        <h3 class="ssd-card__title"><?php echo $title; ?></h3>
                    <?php endif; ?>

                    <?php if ( $desc_out ) : ?>
                        <p class="ssd-card__desc"><?php echo $desc_out; ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php /* ── Mobile Swiper ─────────────────────────────────────────── */ ?>
        <div class="ssd-swiper-wrap">
            <div class="swiper ssd-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $items as $item ) :
                        $icon  = $item['icon']        ?? null;
                        $title = esc_html( $item['title']       ?? '' );
                        $desc  = $item['description'] ?? '';
                        $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    ?>
                        <div class="swiper-slide ssd-slide">
                            <div class="ssd-card">
                                <?php if ( $icon ) : ?>
                                    <div class="ssd-card__icon">
                                        <img
                                            src="<?php echo esc_url( $icon['url'] ); ?>"
                                            alt="<?php echo esc_attr( $icon['alt'] ?: $title ); ?>"
                                            width="60"
                                            height="60"
                                            loading="lazy"
                                        >
                                    </div>
                                <?php endif; ?>

                                <?php if ( $title ) : ?>
                                    <h3 class="ssd-card__title"><?php echo $title; ?></h3>
                                <?php endif; ?>

                                <?php if ( $desc_out ) : ?>
                                    <p class="ssd-card__desc"><?php echo $desc_out; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php /* Numeric counter: 01 / 04 */ ?>
            <div class="ssd-counter" aria-live="polite">
                <span class="ssd-counter__current">01</span>
                <span class="ssd-counter__sep">/</span>
                <span class="ssd-counter__total"><?php echo str_pad( $total, 2, '0', STR_PAD_LEFT ); ?></span>
            </div>
        </div>

    </div><!-- .ssd-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
