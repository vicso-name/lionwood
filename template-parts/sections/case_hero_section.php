<?php
/**
 * Block: Case Hero Section
 *
 * ACF block slug : acf/case-hero-section
 *
 * Two equal columns:
 *   Left  — client name (pre-title) + H1 title + description + outcomes checklist
 *   Right — hero image with industry taxonomy tag overlay
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$client_name   = esc_html( get_field( 'client_name' ) ?: '' );
$title_raw     = get_field( 'title' );
$title         = $title_raw ? esc_html( $title_raw ) : esc_html( get_the_title() );
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$outcomes      = get_field( 'outcomes' ) ?: [];
$image         = get_field( 'image' );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Industry taxonomy tags from case_study_category
$industries = get_the_terms( get_the_ID(), 'case_study_category' );
$industries = ( $industries && ! is_wp_error( $industries ) ) ? $industries : [];

// Check icon SVG
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
    <path d="M12.0784 3.67079C12.1878 3.78018 12.2492 3.92853 12.2492 4.08321C12.2492 4.23789 12.1878 4.38623 12.0784 4.49563L6.24509 10.329C6.1357 10.4383 5.98736 10.4998 5.83268 10.4998C5.678 10.4998 5.52965 10.4383 5.42026 10.329L2.50359 7.41229C2.39733 7.30227 2.33854 7.15492 2.33987 7.00197C2.34119 6.84903 2.40254 6.70272 2.5107 6.59456C2.61885 6.48641 2.76516 6.42506 2.91811 6.42373C3.07106 6.4224 3.21841 6.4812 3.32843 6.58746L5.83268 9.09171L11.2536 3.67079C11.363 3.56143 11.5113 3.5 11.666 3.5C11.8207 3.5 11.969 3.56143 12.0784 3.67079Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="chs-section"
    style="
        --chs-pt: <?php echo $pt; ?>px;
        --chs-pb: <?php echo $pb; ?>px;
        --chs-pt-mob: <?php echo $pt_mob; ?>px;
        --chs-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="chs-section__container">
        <div class="chs-row">

            <?php /* ── Left column ──────────────────────────────────────── */ ?>
            <div class="chs-col chs-col--left">

                <?php if ( $client_name ) : ?>
                    <p class="chs-client chs-anim" data-delay="0"><?php echo $client_name; ?></p>
                <?php endif; ?>

                <h1 class="chs-title chs-anim" data-delay="100"><?php echo $title; ?></h1>

                <?php if ( $description ) : ?>
                    <p class="chs-description chs-anim" data-delay="220"><?php echo $description; ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $outcomes ) ) : ?>
                    <ul class="chs-outcomes">
                        <?php foreach ( $outcomes as $i => $outcome ) :
                            $text = esc_html( $outcome['text'] ?? '' );
                            if ( ! $text ) continue;
                            $delay = 340 + $i * 60;
                        ?>
                            <li class="chs-outcomes__item chs-anim" data-delay="<?php echo $delay; ?>">
                                <span class="chs-outcomes__icon" aria-hidden="true"><?php echo $check_svg; ?></span>
                                <span class="chs-outcomes__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div><!-- .chs-col--left -->

            <?php /* ── Right column: image ──────────────────────────────── */ ?>
            <div class="chs-col chs-col--right chs-anim chs-anim--image" data-delay="60">
                <div
                    class="chs-image"
                    <?php if ( $image ) : ?>
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                    <?php endif; ?>
                >
                    <?php if ( ! empty( $industries ) ) : ?>
                        <div class="chs-image__tags">
                            <?php foreach ( $industries as $industry ) : ?>
                                <span class="chs-image__tag">#<?php echo esc_html( $industry->name ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- .chs-col--right -->

        </div><!-- .chs-row -->
    </div><!-- .chs-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
