<?php
/**
 * Block: Partnership Hero Section
 *
 * ACF block slug : acf/partnership-hero
 *
 * Layout (same as Home Hero, no typewriter):
 *   Line 1: [word_start] [arrow SVG] ......... [word_end]
 *   Line 2:        [line_2] (gray, indented)
 *
 *   120px gap
 *
 *   Row: [description 410px] .......... [Get Estimate btn] [Book a Meeting btn]
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$line1_start = get_field( 'title_line1_start' ) ?: __( 'Become Our', 'lionwood' );
$line1_end   = get_field( 'title_line1_end' )   ?: __( 'Team Of', 'lionwood' );
$line2       = get_field( 'title_line2' )        ?: __( 'Together', 'lionwood' );
$label_text  = get_field( 'label_text' ) ?: '';
$desc_raw    = get_field( 'description' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#C83030';
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$est_raw    = get_field( 'cta_estimate' );
$est_url    = ! empty( $est_raw['url'] )    ? esc_url( $est_raw['url'] )    : '#';
$est_label  = ! empty( $est_raw['title'] )  ? esc_html( $est_raw['title'] ) : __( 'Get Estimate', 'lionwood' );
$est_target = ! empty( $est_raw['target'] ) ? $est_raw['target']             : '_self';

$mtg_raw    = get_field( 'cta_meeting' );
$mtg_url    = ! empty( $mtg_raw['url'] )    ? esc_url( $mtg_raw['url'] )    : '#';
$mtg_label  = ! empty( $mtg_raw['title'] )  ? esc_html( $mtg_raw['title'] ) : __( 'Book a Meeting', 'lionwood' );
$mtg_target = ! empty( $mtg_raw['target'] ) ? $mtg_raw['target']             : '_self';
?>

<section class="ph-section" aria-label="<?php esc_attr_e( 'Partnership Hero', 'lionwood' ); ?>"
    style="
        --ph-pt: <?php echo $pt; ?>px;
        --ph-pb: <?php echo $pb; ?>px;
        --ph-pt-mob: <?php echo $pt_mob; ?>px;
        --ph-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ph-section__container">

        <?php /* ── Heading (h1 for SEO) ──────────────────────────────── */ ?>
        <h1 class="ph-heading">

            <?php /* Line 1: [start+arrow group] ........... [end] */ ?>
            <span class="ph-heading__line ph-heading__line--1">
                <span class="ph-heading__start-group ph-anim" data-delay="0">
                    <span class="ph-heading__word ph-heading__word--start">
                        <?php echo esc_html( $line1_start ); ?>
                    </span>
                    <span class="ph-heading__arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="110" height="110" viewBox="0 0 110 110" fill="none">
                            <path d="M93.5 71.5L97.0355 67.9645L100.571 71.5L97.0355 75.0355L93.5 71.5ZM33 71.5V76.5V71.5ZM33 27.5V22.5V27.5ZM71.5 49.5L75.0355 45.9645L97.0355 67.9645L93.5 71.5L89.9645 75.0355L67.9645 53.0355L71.5 49.5ZM93.5 71.5L97.0355 75.0355L75.0355 97.0355L71.5 93.5L67.9645 89.9645L89.9645 67.9645L93.5 71.5ZM93.5 71.5V76.5H33V71.5V66.5H93.5V71.5ZM33 71.5V76.5C25.8392 76.5 18.9716 73.6554 13.9081 68.5919L17.4437 65.0564L20.9792 61.5208C24.1673 64.7089 28.4913 66.5 33 66.5V71.5ZM17.4437 65.0564L13.9081 68.5919C8.84464 63.5284 6 56.6608 6 49.5H11H16C16 54.0087 17.7911 58.3327 20.9792 61.5208L17.4437 65.0564ZM11 49.5H6C6 42.3392 8.84464 35.4716 13.9081 30.4081L17.4437 33.9436L20.9792 37.4792C17.7911 40.6673 16 44.9913 16 49.5H11ZM17.4437 33.9436L13.9081 30.4081C18.9716 25.3446 25.8392 22.5 33 22.5V27.5V32.5C28.4913 32.5 24.1673 34.2911 20.9792 37.4792L17.4437 33.9436ZM33 27.5V22.5H38.5V27.5V32.5H33V27.5Z" fill="#C83030"/>
                        </svg>
                    </span>
                </span>
                <span class="ph-heading__word ph-heading__word--end ph-anim" data-delay="300">
                    <?php echo esc_html( $line1_end ); ?>
                </span>
            </span>

            <?php /* Line 2: gray */ ?>
            <span class="ph-heading__line ph-heading__line--2">
                <span class="ph-heading__gray ph-anim" data-delay="450">
                    <?php echo esc_html( $line2 ); ?>
                </span>
            </span>

        </h1><!-- .ph-heading -->

        <?php /* ── Bottom row: description + CTAs ────────────────────── */ ?>
        <div class="ph-bottom">

            <?php if ( $label_text || $description ) : ?>
                <div class="ph-desc-group ph-anim" data-delay="600">
                    <?php if ( $label_text ) : ?>
                        <span class="ph-label"><?php echo esc_html( $label_text ); ?></span>
                    <?php endif; ?>
                    <?php if ( $description ) : ?>
                        <p class="ph-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ph-ctas ph-anim" data-delay="700">
                <a
                    class="ph-btn ph-btn--primary"
                    href="<?php echo $est_url; ?>"
                    target="<?php echo esc_attr( $est_target ); ?>"
                    <?php echo '_blank' === $est_target ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $est_label; ?></a>

                <a
                    class="ph-btn ph-btn--outline js-book-meeting"
                    href="<?php echo $mtg_url; ?>"
                    target="<?php echo esc_attr( $mtg_target ); ?>"
                    <?php echo '_blank' === $mtg_target ? 'rel="noopener noreferrer"' : ''; ?>
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                        <rect x="1.5" y="3" width="15" height="13.5" rx="2" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M1.5 7H16.5" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M6 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                        <path d="M12 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <?php echo $mtg_label; ?>
                </a>
            </div>

        </div><!-- .ph-bottom -->

    </div><!-- .ph-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
