<?php
/**
 * Block: Awards and Certifications Hero
 *
 * ACF block slug: acf/awards-hero
 *
 * Layout:
 *   Line 1: [word_start] [arrow SVG] ......... [word_end]
 *   Line 2:        [line_2] (gray, centred)
 *
 *   120px gap
 *
 *   Row: [description 410px] .......... [CTA buttons]
 *
 *   ──────────────────────── divider ────────────────────────
 *
 *   Row: [awards_label]  ........  [cert icon] [cert icon] …
 */

defined( 'ABSPATH' ) || exit;

$line1_start = get_field( 'title_line1_start' ) ?: __( 'Awards &', 'lionwood' );
$line1_end   = get_field( 'title_line1_end' )   ?: __( 'Trust', 'lionwood' );
$line2       = get_field( 'title_line2' )        ?: __( 'Certifications', 'lionwood' );

$desc_raw    = get_field( 'description' );
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$est_raw    = get_field( 'cta_estimate' );
$est_url    = ! empty( $est_raw['url'] )    ? esc_url( $est_raw['url'] )    : '#';
$est_label  = ! empty( $est_raw['title'] )  ? esc_html( $est_raw['title'] ) : __( 'Get Estimate', 'lionwood' );
$est_target = ! empty( $est_raw['target'] ) ? $est_raw['target']             : '_self';

$mtg_raw    = get_field( 'cta_meeting' );
$mtg_url    = ! empty( $mtg_raw['url'] )    ? esc_url( $mtg_raw['url'] )    : '#';
$mtg_label  = ! empty( $mtg_raw['title'] )  ? esc_html( $mtg_raw['title'] ) : __( 'Book a Meeting', 'lionwood' );
$mtg_target = ! empty( $mtg_raw['target'] ) ? $mtg_raw['target']             : '_self';

$certifications = get_field( 'certifications' ) ?: [];
?>

<section class="awh-section" aria-label="<?php esc_attr_e( 'Awards and Certifications', 'lionwood' ); ?>">
    <div class="awh-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <h1 class="awh-heading">

            <span class="awh-heading__line awh-heading__line--1">
                <span class="awh-heading__start-group awh-anim" data-delay="0">
                    <span class="awh-heading__word awh-heading__word--start">
                        <?php echo esc_html( $line1_start ); ?>
                    </span>
                    <span class="awh-heading__arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="110" height="110" viewBox="0 0 110 110" fill="none">
                            <path d="M93.5 71.5L97.0355 67.9645L100.571 71.5L97.0355 75.0355L93.5 71.5ZM33 71.5V76.5V71.5ZM33 27.5V22.5V27.5ZM71.5 49.5L75.0355 45.9645L97.0355 67.9645L93.5 71.5L89.9645 75.0355L67.9645 53.0355L71.5 49.5ZM93.5 71.5L97.0355 75.0355L75.0355 97.0355L71.5 93.5L67.9645 89.9645L89.9645 67.9645L93.5 71.5ZM93.5 71.5V76.5H33V71.5V66.5H93.5V71.5ZM33 71.5V76.5C25.8392 76.5 18.9716 73.6554 13.9081 68.5919L17.4437 65.0564L20.9792 61.5208C24.1673 64.7089 28.4913 66.5 33 66.5V71.5ZM17.4437 65.0564L13.9081 68.5919C8.84464 63.5284 6 56.6608 6 49.5H11H16C16 54.0087 17.7911 58.3327 20.9792 61.5208L17.4437 65.0564ZM11 49.5H6C6 42.3392 8.84464 35.4716 13.9081 30.4081L17.4437 33.9436L20.9792 37.4792C17.7911 40.6673 16 44.9913 16 49.5H11ZM17.4437 33.9436L13.9081 30.4081C18.9716 25.3446 25.8392 22.5 33 22.5V27.5V32.5C28.4913 32.5 24.1673 34.2911 20.9792 37.4792L17.4437 33.9436ZM33 27.5V22.5H38.5V27.5V32.5H33V27.5Z" fill="#C83030"/>
                        </svg>
                    </span>
                </span>
                <span class="awh-heading__word awh-heading__word--end awh-anim" data-delay="300">
                    <?php echo esc_html( $line1_end ); ?>
                </span>
            </span>

            <span class="awh-heading__line awh-heading__line--2">
                <span class="awh-heading__gray awh-anim" data-delay="450">
                    <?php echo esc_html( $line2 ); ?>
                </span>
            </span>

        </h1>

        <?php /* ── Bottom row: description + CTAs ────────────────────── */ ?>
        <div class="awh-bottom">

            <?php if ( $description ) : ?>
                <p class="awh-description awh-anim" data-delay="600">
                    <?php echo $description; ?>
                </p>
            <?php endif; ?>

            <div class="awh-ctas awh-anim" data-delay="700">
                <a
                    class="awh-btn awh-btn--primary"
                    href="<?php echo $est_url; ?>"
                    target="<?php echo esc_attr( $est_target ); ?>"
                    <?php echo '_blank' === $est_target ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $est_label; ?></a>

                <a
                    class="awh-btn awh-btn--outline"
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

        </div>

        <?php /* ── Certifications row ──────────────────────────────────── */ ?>
        <?php if ( ! empty( $certifications ) ) : ?>
        <div class="awh-certs awh-anim" data-delay="800">

            <div class="awh-certs__icons">
                <?php foreach ( $certifications as $item ) :
                    $img = $item['image'] ?? [];
                    if ( empty( $img['url'] ) ) continue;
                ?>
                    <div class="awh-certs__item">
                        <img
                            src="<?php echo esc_url( $img['url'] ); ?>"
                            alt="<?php echo esc_attr( $img['alt'] ?? '' ); ?>"
                            width="188"
                            height="74"
                            loading="lazy"
                        >
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endif; ?>

    </div>
    <?php get_template_part( 'template-parts/partials/decor-bottom' ); ?>
</section>
