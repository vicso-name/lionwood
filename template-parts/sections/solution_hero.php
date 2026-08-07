<?php
/**
 * Block: Solution Hero Section
 *
 * ACF block slug : acf/solution-hero
 * Dark #111319 background, two-line staircase heading, description, CTA button,
 * hero image on the right bleeding out of the section (auh-style).
 */

defined( 'ABSPATH' ) || exit;

$pt           = absint( get_field( 'padding_top' )        ?: 80 );
$pb           = absint( get_field( 'padding_bottom' )     ?: 0 );
$pt_mob       = absint( get_field( 'padding_top_mob' )    ?: 60 );
$pb_mob       = absint( get_field( 'padding_bottom_mob' ) ?: 0 );
$title_line_1 = esc_html( get_field( 'title_line_1' ) ?: __( 'Solution:', 'lionwood' ) );
$title_line_2 = esc_html( get_field( 'title_line_2' ) ?: '' );
$description  = esc_html( get_field( 'description' )  ?: '' );
$link_raw     = get_field( 'cta_link' );
$link_url     = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
$link_label   = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'Book a Discovery Call', 'lionwood' );
$link_tgt     = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';
$hero_image   = get_field( 'hero_image' );
$decor_enabled = get_field( 'decor_bottom_enabled' );
if ( $decor_enabled === null ) $decor_enabled = true;
$decor_color  = get_field( 'decor_bottom_color' ) ?: '#C83030';
?>

<section
    class="sh-section"
    style="
        --sh-pt: <?php echo $pt; ?>px;
        --sh-pb: <?php echo $pb; ?>px;
        --sh-pt-mob: <?php echo $pt_mob; ?>px;
        --sh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sh-section__inner">

        <?php /* ── Left: text column ──────────────────────────────────── */ ?>
        <div class="sh-col sh-col--text">

            <div class="sh-heading">
                <span class="sh-heading__line-1"><?php echo $title_line_1; ?></span>
                <?php if ( $title_line_2 ) : ?>
                    <h1 class="sh-heading__line-2"><?php echo $title_line_2; ?></h1>
                <?php endif; ?>
            </div>

            <?php if ( $description || $link_url ) : ?>
                <div class="sh-content">
                    <?php if ( $description ) : ?>
                        <p class="sh-description"><?php echo $description; ?></p>
                    <?php endif; ?>

                    <?php if ( $link_url ) : ?>
                        <a
                            class="sh-btn"
                            href="<?php echo $link_url; ?>"
                            target="<?php echo esc_attr( $link_tgt ); ?>"
                            <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                        ><?php echo $link_label; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <?php /* ── Right: hero image ────────────────────────────────────── */ ?>
        <?php if ( $hero_image ) : ?>
            <div class="sh-col sh-col--image">
                <img
                    class="sh-image"
                    src="<?php echo esc_url( $hero_image['url'] ); ?>"
                    alt="<?php echo esc_attr( $hero_image['alt'] ?: $title_line_2 ); ?>"
                    loading="eager"
                >
            </div>
        <?php endif; ?>

    </div><!-- .sh-section__inner -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
