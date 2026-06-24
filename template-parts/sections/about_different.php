<?php
/**
 * Block: About Different (What Makes Us Different)
 * Slug: acf/about-different
 *
 * Based on get-started card stack pattern.
 * Key difference: two-line H2 heading (56px), line 2 offset 30% right.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$line_1 = get_field( 'title_line_1' ) ?: 'What Makes';
$line_2 = get_field( 'title_line_2' ) ?: 'Us Different';

$desc_raw    = get_field( 'description' );
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$slides = get_field( 'slides' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

// Colour cycle: red → gray → dark
$bg_cycle = [ '#C83030', '#EDEDED', '#111319' ];
$dark_bgs = [ '#C83030', '#111319' ];
?>

<section
    class="adf-section"
    style="
        --adf-pt: <?php echo $pt; ?>px;
        --adf-pb: <?php echo $pb; ?>px;
        --adf-pt-mob: <?php echo $pt_mob; ?>px;
        --adf-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="adf-section__container">

        <?php /* ── Heading — two lines H2, line 2 offset 30% right ── */ ?>
        <div class="adf-heading">
            <span class="adf-heading__line1"><?php echo esc_html( $line_1 ); ?></span>
            <span class="adf-heading__line2"><?php echo esc_html( $line_2 ); ?></span>
        </div>

        <?php /* ── Slider row ── */ ?>
        <?php if ( ! empty( $slides ) ) : ?>
        <div class="adf-row">

            <?php /* Left col: indicator */ ?>
            <div class="adf-col adf-col--left">
                <p class="adf-indicator" data-adf-indicator>
                    [ <?php echo esc_html( $slides[0]['title'] ?? '' ); ?> ]
                </p>
            </div>

            <?php /* Middle col: description + nav */ ?>
            <div class="adf-col adf-col--middle">
                <?php if ( $description ) : ?>
                    <p class="adf-description"><?php echo $description; ?></p>
                <?php endif; ?>
                <div class="adf-nav">
                    <button class="adf-nav__btn adf-nav__btn--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'lionwood' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M16.6615 9.99903L3.32813 9.99902M8.32812 14.999L3.32813 9.99902L8.32812 4.99902" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="adf-nav__btn adf-nav__btn--next" aria-label="<?php esc_attr_e( 'Next slide', 'lionwood' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M3.33854 10.001L16.6719 10.001M11.6719 5.00098L16.6719 10.001L11.6719 15.001" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <?php /* Right col: card stack */ ?>
            <div class="adf-col adf-col--right">
                <div class="adf-cards-wrapper">
                    <?php foreach ( $slides as $i => $slide ) :
                        $icon      = $slide['icon']        ?? null;
                        $stitle    = esc_html( $slide['title']       ?? '' );
                        $sdesc     = $slide['description'] ?? '';
                        $sdesc_out = $sdesc ? wp_kses( $sdesc, [ 'br' => [] ] ) : '';
                        $link_raw  = $slide['link']        ?? null;
                        $link_url  = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
                        $link_lbl  = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'Book Consultation', 'lionwood' );
                        $link_tgt  = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';

                        $bg_override = $slide['bg_color'] ?? '';
                        $bg          = $bg_override ?: $bg_cycle[ $i % 3 ];
                        $is_dark_bg  = in_array( $bg, $dark_bgs, true );
                        $color_mod   = $is_dark_bg ? 'adf-slide--light' : 'adf-slide--dark';
                    ?>
                        <div
                            class="adf-slide <?php echo esc_attr( $color_mod ); ?>"
                            style="background-color: <?php echo esc_attr( $bg ); ?>;"
                            data-slide-title="<?php echo esc_attr( $stitle ); ?>"
                        >
                            <?php if ( $icon ) : ?>
                                <div class="adf-slide__icon">
                                    <img
                                        src="<?php echo esc_url( $icon['url'] ); ?>"
                                        alt="<?php echo esc_attr( $icon['alt'] ?: $stitle ); ?>"
                                        width="70" height="70"
                                        loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>"
                                    >
                                </div>
                            <?php endif; ?>

                            <h3 class="adf-slide__title"><?php echo $stitle; ?></h3>

                            <?php if ( $sdesc_out ) : ?>
                                <p class="adf-slide__desc"><?php echo $sdesc_out; ?></p>
                            <?php endif; ?>

                            <?php if ( $link_url ) : ?>
                                <a
                                    class="adf-slide__btn"
                                    href="<?php echo $link_url; ?>"
                                    target="<?php echo esc_attr( $link_tgt ); ?>"
                                    <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                                ><?php echo $link_lbl; ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
