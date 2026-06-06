<?php
/**
 * Block: Business Impact
 *
 * ACF block slug : acf/business-impact
 * Template file  : blocks/business-impact/business-impact.php
 *
 * Layout:
 *   - Heading block 950px: top line (gray) + bottom line (black)
 *   - Description 385px — right-aligned below heading (mirroring solutions-section pattern)
 *   - Card grid: N cols (ACF select), gap 16px, equal height
 *   - Mobile: Swiper carousel + numeric counter
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_top     = get_field( 'title_top' )    ?: '';
$title_bottom  = get_field( 'title_bottom' ) ?: '';
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$columns       = absint( get_field( 'columns' ) ?: 4 );
$items         = get_field( 'items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';
$total         = count( $items );

if ( empty( $items ) ) return;

// Default check icon SVG
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" aria-hidden="true">
    <path d="M19.5821 8.31921C19.7211 8.45986 19.7992 8.6506 19.7992 8.84948C19.7992 9.04836 19.7211 9.23909 19.5821 9.37974L12.1648 16.88C12.0257 17.0206 11.8371 17.0996 11.6404 17.0996C11.4438 17.0996 11.2551 17.0206 11.116 16.88L7.40743 13.1299C7.27232 12.9884 7.19756 12.799 7.19925 12.6023C7.20094 12.4057 7.27894 12.2175 7.41646 12.0785C7.55399 11.9394 7.74002 11.8605 7.9345 11.8588C8.12897 11.8571 8.31633 11.9327 8.45622 12.0693L11.6404 15.2892L18.5333 8.31921C18.6724 8.1786 18.861 8.09961 19.0577 8.09961C19.2543 8.09961 19.443 8.1786 19.5821 8.31921Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="bi-section"
    style="
        --bi-pt: <?php echo $pt; ?>px;
        --bi-pb: <?php echo $pb; ?>px;
        --bi-pt-mob: <?php echo $pt_mob; ?>px;
        --bi-pb-mob: <?php echo $pb_mob; ?>px;
        --bi-columns: <?php echo $columns; ?>;
    "
>
    <div class="bi-section__container">

        <?php /* ── Heading + Description ─────────────────────────────── */ ?>
        <div class="bi-header">
            <div class="bi-heading">
                <?php if ( $title_top ) : ?>
                    <span class="bi-heading__top"><?php echo esc_html( $title_top ); ?></span>
                <?php endif; ?>
                <?php if ( $title_bottom ) : ?>
                    <span class="bi-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
                <?php endif; ?>
            </div>

            <?php if ( $description ) : ?>
                <p class="bi-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Desktop grid ─────────────────────────────────────────── */ ?>
        <div class="bi-grid">
            <?php foreach ( $items as $item ) :
                $icon  = $item['icon']        ?? null;
                $title = esc_html( $item['title']       ?? '' );
                $desc  = $item['description'] ?? '';
                $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
            ?>
                <div class="bi-card">
                    <div class="bi-card__icon-wrap">
                        <?php if ( $icon ) : ?>
                            <img
                                class="bi-card__icon-img"
                                src="<?php echo esc_url( $icon['url'] ); ?>"
                                alt="<?php echo esc_attr( $icon['alt'] ?: $title ); ?>"
                                width="36"
                                height="36"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <span class="bi-card__icon-default" aria-hidden="true">
                                <?php echo $check_svg; ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ( $title ) : ?>
                        <h3 class="bi-card__title"><?php echo $title; ?></h3>
                    <?php endif; ?>

                    <?php if ( $desc_out ) : ?>
                        <p class="bi-card__desc"><?php echo $desc_out; ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php /* ── Mobile Swiper ─────────────────────────────────────────── */ ?>
        <div class="bi-swiper-wrap">
            <div class="swiper bi-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $items as $item ) :
                        $icon  = $item['icon']        ?? null;
                        $title = esc_html( $item['title']       ?? '' );
                        $desc  = $item['description'] ?? '';
                        $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    ?>
                        <div class="swiper-slide bi-slide">
                            <div class="bi-card">
                                <div class="bi-card__icon-wrap">
                                    <?php if ( $icon ) : ?>
                                        <img
                                            class="bi-card__icon-img"
                                            src="<?php echo esc_url( $icon['url'] ); ?>"
                                            alt="<?php echo esc_attr( $icon['alt'] ?: $title ); ?>"
                                            width="36"
                                            height="36"
                                            loading="lazy"
                                        >
                                    <?php else : ?>
                                        <span class="bi-card__icon-default" aria-hidden="true">
                                            <?php echo $check_svg; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ( $title ) : ?>
                                    <h3 class="bi-card__title"><?php echo $title; ?></h3>
                                <?php endif; ?>

                                <?php if ( $desc_out ) : ?>
                                    <p class="bi-card__desc"><?php echo $desc_out; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bi-counter" aria-live="polite">
                <span class="bi-counter__current">01</span>
                <span class="bi-counter__sep">/</span>
                <span class="bi-counter__total"><?php echo str_pad( $total, 2, '0', STR_PAD_LEFT ); ?></span>
            </div>
        </div>

    </div><!-- .bi-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
