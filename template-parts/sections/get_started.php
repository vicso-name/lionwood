<?php
/**
 * Block: Get Started Slider
 *
 * ACF block slug : acf/get-started
 * Template file  : blocks/get-started/get-started.php
 *
 * Slider: Swiper with effect: 'cards'
 * Card bg cycles: #C83030 → #EDEDED → #111319 → repeat
 * Content color: white on red/black, dark on gray
 */

defined( 'ABSPATH' ) || exit;

// ── Fields ───────────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )        ?: 160 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 160 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$line_1 = get_field( 'title_line_1' ) ?: __( 'Get', 'lionwood' );
$line_2 = get_field( 'title_line_2' ) ?: __( 'Started', 'lionwood' );
$line_3 = get_field( 'title_line_3' ) ?: __( 'With the Right', 'lionwood' );
$line_4 = get_field( 'title_line_4' ) ?: __( 'Approach', 'lionwood' );

$desc_raw    = get_field( 'description' );
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$slides = get_field( 'slides' ) ?: [];

// ── Colour cycle ─────────────────────────────────────────────────────────────
$bg_cycle    = [ '#C83030', '#EDEDED', '#111319' ];
// Dark bg (#C83030 and #111319) → white content; gray (#EDEDED) → dark content
$dark_bgs    = [ '#C83030', '#111319' ];

// ── Button styles per bg ──────────────────────────────────────────────────────
// On red/black: white border button; on gray: dark border button
?>

<section
    class="gs-section"
    style="
        --gs-pt: <?php echo $pt; ?>px;
        --gs-pb: <?php echo $pb; ?>px;
        --gs-pt-mob: <?php echo $pt_mob; ?>px;
        --gs-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="gs-section__container">

        <?php /* ── Large heading ──────────────────────────────────────────── */ ?>
        <div class="gs-heading">
            <span class="gs-heading__line gs-heading__line--1"><?php echo esc_html( $line_1 ); ?></span>
            <span class="gs-heading__line gs-heading__line--2"><?php echo esc_html( $line_2 ); ?></span>
            <span class="gs-heading__line gs-heading__line--3"><?php echo esc_html( $line_3 ); ?></span>
            <span class="gs-heading__line gs-heading__line--4"><?php echo esc_html( $line_4 ); ?></span>
        </div>

        <?php /* ── Slider row ───────────────────────────────────────────────── */ ?>
        <?php if ( ! empty( $slides ) ) : ?>
        <div class="gs-row">

            <?php /* Left col: indicator only */ ?>
            <div class="gs-col gs-col--left">
                <p class="gs-indicator" data-gs-indicator>
                    [ <?php echo esc_html( $slides[0]['title'] ?? '' ); ?> ]
                </p>
            </div>

            <?php /* Middle col: description + nav */ ?>
            <div class="gs-col gs-col--middle">
                <?php if ( $description ) : ?>
                    <p class="gs-description"><?php echo $description; ?></p>
                <?php endif; ?>
                <div class="gs-nav">
                    <button class="gs-nav__btn gs-nav__btn--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'lionwood' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M16.6615 9.99903L3.32813 9.99902M8.32812 14.999L3.32813 9.99902L8.32812 4.99902" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="gs-nav__btn gs-nav__btn--next" aria-label="<?php esc_attr_e( 'Next slide', 'lionwood' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M3.33854 10.001L16.6719 10.001M11.6719 5.00098L16.6719 10.001L11.6719 15.001" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <?php /* Right col: card stack */ ?>
            <div class="gs-col gs-col--right">
                <div class="gs-cards-wrapper">
                    <?php foreach ( $slides as $i => $slide ) :
                        $icon     = $slide['icon']        ?? null;
                        $stitle   = esc_html( $slide['title']       ?? '' );
                        $sdesc    = $slide['description'] ?? '';
                        $sdesc_out = $sdesc ? wp_kses( $sdesc, [ 'br' => [] ] ) : '';
                        $link_raw  = $slide['link']        ?? null;
                        $link_url  = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
                        $link_lbl  = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'Book Consultation', 'lionwood' );
                        $link_tgt  = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';

                        // Background colour
                        $bg_override = $slide['bg_color'] ?? '';
                        $bg = $bg_override ?: $bg_cycle[ $i % 3 ];

                        // Content colour based on bg
                        $is_dark_bg = in_array( $bg, $dark_bgs, true );
                        $color_mod  = $is_dark_bg ? 'gs-slide--light' : 'gs-slide--dark';
                    ?>
                        <div
                            class="gs-slide <?php echo esc_attr( $color_mod ); ?>"
                            style="background-color: <?php echo esc_attr( $bg ); ?>;"
                            data-slide-title="<?php echo esc_attr( $stitle ); ?>"
                        >
                            <?php if ( $icon ) : ?>
                                <div class="gs-slide__icon">
                                    <img
                                        src="<?php echo esc_url( $icon['url'] ); ?>"
                                        alt="<?php echo esc_attr( $icon['alt'] ?: $stitle ); ?>"
                                        width="70"
                                        height="70"
                                        loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>"
                                    >
                                </div>
                            <?php endif; ?>

                            <h3 class="gs-slide__title"><?php echo $stitle; ?></h3>

                            <?php if ( $sdesc_out ) : ?>
                                <p class="gs-slide__desc"><?php echo $sdesc_out; ?></p>
                            <?php endif; ?>

                            <?php if ( $link_url ) : ?>
                                <a
                                    class="gs-slide__btn"
                                    href="<?php echo $link_url; ?>"
                                    target="<?php echo esc_attr( $link_tgt ); ?>"
                                    <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                                ><?php echo $link_lbl; ?></a>
                            <?php endif; ?>

                        </div><!-- .gs-slide -->
                    <?php endforeach; ?>
                </div><!-- .gs-cards-wrapper -->
            </div><!-- .gs-col--right -->

        </div><!-- .gs-row -->
        <?php endif; ?>

    </div><!-- .gs-section__container -->
</section>
