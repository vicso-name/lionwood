<?php
/**
 * Block: Career Our Life
 * Slug: acf/career-life
 *
 * Dark bg, two-line heading + description, full-width Swiper gallery,
 * custom progress bar + prev/next navigation (desktop) / slide counter (mobile).
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: 'Our Life at';
$title_bottom = get_field( 'title_bottom' ) ?: 'Lionwood Culture';
$description  = get_field( 'description' )  ?: '';
$desc_out     = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';

$gallery = get_field( 'gallery' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$block_id = 'cl-' . uniqid();
?>

<section
    class="cl-section"
    id="<?php echo esc_attr( $block_id ); ?>"
    style="
        --cl-pt: <?php echo $pt; ?>px;
        --cl-pb: <?php echo $pb; ?>px;
        --cl-pt-mob: <?php echo $pt_mob; ?>px;
        --cl-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Header row: heading + description ── */ ?>
    <div class="cl-section__container">
        <div class="cl-header">
            <div class="cl-heading">
                <span class="cl-heading__top"><?php echo esc_html( $title_top ); ?></span>
                <span class="cl-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </div>
            <?php if ( $desc_out ) : ?>
                <p class="cl-description"><?php echo $desc_out; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php /* ── Full-width Swiper ── */ ?>
    <?php if ( ! empty( $gallery ) ) : ?>
        <div class="cl-slider swiper" data-cl-slider>
            <div class="swiper-wrapper">
                <?php foreach ( $gallery as $image ) :
                    if ( ! $image ) continue;
                ?>
                    <div class="swiper-slide cl-slide">
                        <div
                            class="cl-slide__img"
                            style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                            role="img"
                            aria-label="<?php echo esc_attr( $image['alt'] ?: $title_bottom ); ?>"
                        ></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php /* ── Desktop: progress bar + nav | Mobile: slide counter ── */ ?>
        <div class="cl-section__container">
            <div class="cl-controls">
                <div class="cl-progress" role="progressbar" aria-label="<?php esc_attr_e( 'Slider progress', 'lionwood' ); ?>">
                    <div class="cl-progress__track">
                        <div class="cl-progress__bar" data-cl-progress></div>
                    </div>
                </div>

                <div class="cl-nav">
                    <button class="cl-nav__btn cl-nav__btn--prev" data-cl-prev aria-label="<?php esc_attr_e( 'Previous slide', 'lionwood' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M16.6615 10L3.32813 10M8.32812 15L3.32813 10L8.32812 5" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="cl-nav__btn cl-nav__btn--next" data-cl-next aria-label="<?php esc_attr_e( 'Next slide', 'lionwood' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M3.33854 10L16.6719 10M11.6719 5L16.6719 10L11.6719 15" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="cl-counter" aria-live="polite">
                <span class="cl-counter__current" data-cl-current>01</span><span class="cl-counter__sep">&nbsp;/&nbsp;</span><span class="cl-counter__total" data-cl-total>01</span>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
