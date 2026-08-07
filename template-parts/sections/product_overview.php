<?php
/**
 * Block: Product Overview
 * Slug: acf/product-overview
 *
 * Dark bg, scrolling marquee title, then two-col row:
 *   Left:  two-line heading (width 370px)
 *   Right: description + repeater items with dividers (width 441px)
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field('padding_top')        ?: 100 );
$pb     = absint( get_field('padding_bottom')     ?: 200 );
$pt_mob = absint( get_field('padding_top_mob')    ?: 70 );
$pb_mob = absint( get_field('padding_bottom_mob') ?: 140 );

$marquee_text  = get_field('marquee_text')  ?: 'Product Overview';
$title_top     = get_field('title_top')     ?: '';
$title_bottom  = get_field('title_bottom')  ?: '';
$description   = get_field('description')  ?: '';
$items         = get_field('items') ?: [];

$decor_enabled = get_field('decor_bottom_enabled');
$decor_color   = get_field('decor_bottom_color') ?: '#E9E9E9';

$chevron_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
    <path d="M6 12.4446L11 8.00011L6 3.55566" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    class="po-section"
    style="
        --po-pt: <?php echo $pt; ?>px;
        --po-pb: <?php echo $pb; ?>px;
        --po-pt-mob: <?php echo $pt_mob; ?>px;
        --po-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Scrolling marquee title ── */ ?>
    <div class="po-marquee" aria-hidden="true">
        <div class="po-marquee__track">
            <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                <span class="po-marquee__item"><?php echo esc_html( $marquee_text ); ?></span>
            <?php endfor; ?>
            <?php /* Duplicate for seamless loop */ ?>
            <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                <span class="po-marquee__item"><?php echo esc_html( $marquee_text ); ?></span>
            <?php endfor; ?>
        </div>
    </div>

    <div class="po-section__container">

        <?php /* ── Two-column row ── */ ?>
        <div class="po-row">

            <?php /* ── Left col: two-line heading ── */ ?>
            <div class="po-col--left">
                <div class="po-heading">
                    <?php if ( $title_top ) : ?>
                        <span class="po-heading__top"><?php echo esc_html( $title_top ); ?></span>
                    <?php endif; ?>
                    <?php if ( $title_bottom ) : ?>
                        <span class="po-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php /* ── Right col: description + items ── */ ?>
            <div class="po-col--right">
                <div class="po-col__inner">

                    <?php if ( $description ) : ?>
                        <p class="po-description"><?php echo wp_kses( $description, [ 'br' => [] ] ); ?></p>
                    <?php endif; ?>

                    <?php if ( ! empty( $items ) ) : ?>
                        <ul class="po-list">
                            <?php foreach ( $items as $item ) :
                                $label = esc_html( $item['label'] ?? '' );
                                $value = esc_html( $item['value'] ?? '' );
                                if ( ! $label ) continue;
                            ?>
                                <li class="po-list__item">
                                    <div class="po-list__main">
                                        <span class="po-list__icon"><?php echo $chevron_svg; ?></span>
                                        <span class="po-list__label"><?php echo $label; ?></span>
                                    </div>
                                    <?php if ( $value ) : ?>
                                        <span class="po-list__value"><?php echo $value; ?></span>
                                    <?php endif; ?>
                                </li>
                                <li class="po-list__divider" aria-hidden="true"></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>

        </div>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
