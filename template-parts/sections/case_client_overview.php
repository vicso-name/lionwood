<?php
/**
 * Block: Case Client Overview
 *
 * ACF block slug : acf/case-client-overview
 * Red bg, marquee, two rows (About the Client + Summary)
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 50 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 20 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 100 );
$marquee_text  = esc_html( get_field( 'marquee_text' ) ?: __( 'Project Overview', 'lionwood' ) );

$about_top     = esc_html( get_field( 'about_title_top' )    ?: __( 'About the', 'lionwood' ) );
$about_bottom  = esc_html( get_field( 'about_title_bottom' ) ?: __( 'Client', 'lionwood' ) );
$about_content = get_field( 'about_content' ) ?: '';

$summary_content = get_field( 'summary_content' ) ?: '';

$industry    = esc_html( get_field( 'industry' )    ?: '' );
$segment     = esc_html( get_field( 'segment' )     ?: '' );
$location    = esc_html( get_field( 'location' )    ?: '' );
$team        = esc_html( get_field( 'team' )        ?: '' );
$duration    = esc_html( get_field( 'duration' )    ?: '' );
$key_outcome = esc_html( get_field( 'key_outcome' ) ?: '' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Service tags from case_study_service taxonomy
$services = get_the_terms( get_the_ID(), 'case_study_service' );
$services = ( $services && ! is_wp_error( $services ) ) ? $services : [];

// Details rows — only non-empty fields
$details = array_filter( [
    __( 'Industry',     'theme' ) => $industry,
    __( 'Segment',      'theme' ) => $segment,
    __( 'Location',     'theme' ) => $location,
    __( 'Team',         'theme' ) => $team,
    __( 'Duration',     'theme' ) => $duration,
    __( 'Key outcome',  'theme' ) => $key_outcome,
] );

// Arrow icon for details
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
    <path d="M6 12.4436L11 7.99913L6 3.55469" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    class="cco-section"
    style="
        --cco-pt: <?php echo $pt; ?>px;
        --cco-pb: <?php echo $pb; ?>px;
        --cco-pt-mob: <?php echo $pt_mob; ?>px;
        --cco-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Marquee ─────────────────────────────────────────────────── */ ?>
    <div class="cco-marquee" aria-hidden="true">
        <div class="cco-marquee__track">
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
                <span class="cco-marquee__item"><?php echo $marquee_text; ?></span>
            <?php endfor; ?>
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
                <span class="cco-marquee__item"><?php echo $marquee_text; ?></span>
            <?php endfor; ?>
        </div>
    </div>

    <div class="cco-section__container">

        <?php /* ── Row 1: About the Client ─────────────────────────────── */ ?>
        <div class="cco-row">
            <div class="cco-col cco-col--left">
                <div class="cco-heading">
                    <span class="cco-heading__top"><?php echo $about_top; ?></span>
                    <span class="cco-heading__bottom"><?php echo $about_bottom; ?></span>
                </div>
            </div>
            <div class="cco-col cco-col--right">
                <?php if ( $about_content ) : ?>
                    <div class="cco-content"><?php echo wp_kses_post( $about_content ); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php /* ── Divider ──────────────────────────────────────────────── */ ?>
        <div class="cco-divider" aria-hidden="true"></div>

        <?php /* ── Row 2: Summary ─────────────────────────────────────── */ ?>
        <div class="cco-row cco-row--summary">
            <div class="cco-col cco-col--left">
                <div class="cco-heading">
                    <span class="cco-heading__top cco-heading__top--single"><?php esc_html_e( 'Summary', 'lionwood' ); ?></span>
                </div>
            </div>
            <div class="cco-col cco-col--right">
                <?php if ( $summary_content ) : ?>
                    <div class="cco-content cco-content--summary"><?php echo wp_kses_post( $summary_content ); ?></div>
                <?php endif; ?>

                <?php /* Details table */ ?>
                <?php if ( ! empty( $details ) ) : ?>
                    <dl class="cco-details">
                        <?php foreach ( $details as $label => $value ) : ?>
                            <div class="cco-details__row">
                                <dt class="cco-details__label">
                                    <?php echo $arrow_svg; ?>
                                    <?php echo esc_html( $label ); ?>
                                </dt>
                                <dd class="cco-details__value"><?php echo esc_html( $value ); ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>

                <?php /* Service tags from taxonomy */ ?>
                <?php if ( ! empty( $services ) ) : ?>
                    <div class="cco-tags">
                        <?php foreach ( $services as $service ) : ?>
                            <span class="cco-tag"><?php echo esc_html( $service->name ); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- .cco-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
