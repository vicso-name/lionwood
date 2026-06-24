<?php
/**
 * Block: Whitepaper Hero
 *
 * ACF block slug : acf/whitepaper-hero
 *
 * Two equal columns:
 *   Left  — title + description + subtitle + outcomes checklist + body text + CTA button
 *   Right — hero image
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$title         = esc_html( get_field( 'title' ) ?: get_the_title() );
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$subtitle      = esc_html( get_field( 'subtitle' ) ?: '' );
$outcomes      = get_field( 'outcomes' ) ?: [];
$body_raw      = get_field( 'body_text' );
$body_text     = $body_raw ? wp_kses( $body_raw, [ 'br' => [] ] ) : '';
$cta_link      = get_field( 'cta_link' );
$cta_url       = ! empty( $cta_link['url'] )    ? esc_url( $cta_link['url'] )    : '';
$cta_label     = ! empty( $cta_link['title'] )  ? esc_html( $cta_link['title'] ) : __( 'Download Whitepaper', 'lionwood' );
$cta_target    = ! empty( $cta_link['target'] ) ? $cta_link['target']             : '_self';
$image         = get_field( 'image' );
$decor_enabled = get_field( 'decor_bottom_enabled' ) ?? true;
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#E9E9E9';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
    <path d="M12.0784 3.67079C12.1878 3.78018 12.2492 3.92853 12.2492 4.08321C12.2492 4.23789 12.1878 4.38623 12.0784 4.49563L6.24509 10.329C6.1357 10.4383 5.98736 10.4998 5.83268 10.4998C5.678 10.4998 5.52965 10.4383 5.42026 10.329L2.50359 7.41229C2.39733 7.30227 2.33854 7.15492 2.33987 7.00197C2.34119 6.84903 2.40254 6.70272 2.5107 6.59456C2.61885 6.48641 2.76516 6.42506 2.91811 6.42373C3.07106 6.4224 3.21841 6.4812 3.32843 6.58746L5.83268 9.09171L11.2536 3.67079C11.363 3.56143 11.5113 3.5 11.666 3.5C11.8207 3.5 11.969 3.56143 12.0784 3.67079Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="wph-section"
    style="
        --wph-pt: <?php echo $pt; ?>px;
        --wph-pb: <?php echo $pb; ?>px;
        --wph-pt-mob: <?php echo $pt_mob; ?>px;
        --wph-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="wph-section__container">
        <div class="wph-row">

            <?php /* ── Left column ──────────────────────────────────────── */ ?>
            <div class="wph-col wph-col--left">

                <h1 class="wph-title wph-anim" data-delay="0"><?php echo $title; ?></h1>

                <?php if ( $description ) : ?>
                    <p class="wph-description wph-anim" data-delay="120"><?php echo $description; ?></p>
                <?php endif; ?>

                <?php if ( $subtitle ) : ?>
                    <p class="wph-subtitle wph-anim" data-delay="220"><?php echo $subtitle; ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $outcomes ) ) : ?>
                    <ul class="wph-outcomes">
                        <?php foreach ( $outcomes as $i => $outcome ) :
                            $text = esc_html( $outcome['text'] ?? '' );
                            if ( ! $text ) continue;
                            $delay = 300 + $i * 60;
                        ?>
                            <li class="wph-outcomes__item wph-anim" data-delay="<?php echo $delay; ?>">
                                <span class="wph-outcomes__icon" aria-hidden="true"><?php echo $check_svg; ?></span>
                                <span class="wph-outcomes__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ( $body_text ) : ?>
                    <p class="wph-body wph-anim" data-delay="460"><?php echo $body_text; ?></p>
                <?php endif; ?>

                <?php if ( $cta_url ) : ?>
                    <a
                        class="wph-cta wph-anim"
                        href="<?php echo $cta_url; ?>"
                        target="<?php echo esc_attr( $cta_target ); ?>"
                        <?php echo '_blank' === $cta_target ? 'rel="noopener noreferrer"' : ''; ?>
                        data-delay="520"
                    ><?php echo $cta_label; ?></a>
                <?php endif; ?>

            </div><!-- .wph-col--left -->

            <?php /* ── Right column: image ──────────────────────────────── */ ?>
            <div class="wph-col wph-col--right wph-anim wph-anim--image" data-delay="60">
                <div
                    class="wph-image"
                    <?php if ( $image ) : ?>
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                    <?php endif; ?>
                ></div>
            </div><!-- .wph-col--right -->

        </div><!-- .wph-row -->
    </div><!-- .wph-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
