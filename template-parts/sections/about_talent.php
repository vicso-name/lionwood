<?php
/**
 * Block: About Our Talent
 * Slug: acf/about-talent
 *
 * Left panel: funnel card + stat card + gender card (driven by accordion data via JS)
 * Right panel: accordion (identical to solutions-showcase)
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: 'Our Talent';
$title_bottom = get_field( 'title_bottom' ) ?: 'Built for Performance';
$description  = get_field( 'description' )  ?: '';
$desc_out     = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';

$services = get_field( 'services' ) ?: [];
if ( empty( $services ) ) return;

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

// Build panel data for JS
$panels = [];
foreach ( $services as $i => $svc ) {
    $panels[] = [
        // Funnel card
        'funnel_label'   => esc_html( $svc['funnel_label']   ?? '' ),
        'funnel_row_1'   => esc_html( $svc['funnel_row_1']   ?? '' ),
        'funnel_row_2'   => esc_html( $svc['funnel_row_2']   ?? '' ),
        'funnel_row_3'   => esc_html( $svc['funnel_row_3']   ?? '' ),
        // Stat card
        'stat_label'     => esc_html( $svc['stat_label']     ?? '' ),
        'stat_value'     => esc_html( $svc['stat_value']     ?? '' ),
        'stat_suffix'    => esc_html( $svc['stat_suffix']    ?? '' ),
        'stat_desc'      => esc_html( $svc['stat_desc']      ?? '' ),
        // Gender card
        'badge_1_text'   => esc_html( $svc['badge_1_text']   ?? '' ),
        'badge_2_text'   => esc_html( $svc['badge_2_text']   ?? '' ),
    ];
}

$f = $panels[0];
?>

<section
    class="abt-section"
    style="
        --abt-pt: <?php echo $pt; ?>px;
        --abt-pb: <?php echo $pb; ?>px;
        --abt-pt-mob: <?php echo $pt_mob; ?>px;
        --abt-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="abt-section__container">

        <?php /* ── Heading ── */ ?>
        <div class="abt-section__top">
            <h2 class="abt-section__heading">
                <span class="abt-section__title-top"><?php echo esc_html( $title_top ); ?></span>
                <span class="abt-section__title-bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </h2>
            <?php if ( $desc_out ) : ?>
                <p class="abt-section__description"><?php echo $desc_out; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Body: left panel + accordion ── */ ?>
        <div class="abt-section__body">

            <?php /* ── LEFT PANEL ── */ ?>
            <div class="abt-panel" aria-live="polite">
                <div class="abt-panel__inner">

                    <?php /* ── Funnel card (left column) ── */ ?>
                    <div class="abt-funnel" data-field="funnel">
                        <span class="abt-funnel__label" data-field="funnel_label"><?php echo $f['funnel_label']; ?></span>
                        <div class="abt-funnel__rows">
                            <div class="abt-funnel__row abt-funnel__row--1">
                                <svg viewBox="0 0 267 83" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M254.819 0C262.376 1.05967e-05 268.053 6.90212 266.593 14.3174L254.057 78H12.766L0.22987 14.3174C-1.22961 6.90222 4.44589 0.00024713 12.0033 0H254.819Z" fill="white"/>
                                </svg>
                                <span data-field="funnel_row_1"><?php echo $f['funnel_row_1']; ?></span>
                            </div>
                            <div class="abt-funnel__row abt-funnel__row--2">
                                <svg viewBox="0 83 267 88" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path opacity="0.5" d="M237.001 166H30.3545L15 88H252.355L237.001 166Z" fill="white"/>
                                </svg>
                                <span data-field="funnel_row_2"><?php echo $f['funnel_row_2']; ?></span>
                            </div>
                            <div class="abt-funnel__row abt-funnel__row--3">
                                <svg viewBox="0 171 267 83" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path opacity="0.2" d="M221.261 244.317C220.153 249.943 215.22 254 209.486 254H57.2227C51.4888 254 46.5557 249.943 45.4482 244.317L32 176H234.709L221.261 244.317Z" fill="white"/>
                                </svg>
                                <span data-field="funnel_row_3"><?php echo $f['funnel_row_3']; ?></span>
                            </div>
                        </div>
                    </div>

                    <?php /* ── Right column: stat + gender cards ── */ ?>
                    <div class="abt-cards-col">

                        <?php /* Stat card */ ?>
                        <div class="abt-stat" data-field="stat">
                            <span class="abt-stat__label" data-field="stat_label"><?php echo $f['stat_label']; ?></span>
                            <div class="abt-stat__bottom">
                                <p class="abt-stat__value" data-field="stat_value" data-target="<?php echo esc_attr( preg_replace('/[^0-9.]/', '', $f['stat_value']) ); ?>" data-suffix="<?php echo esc_attr( $f['stat_suffix'] ); ?>">0<?php echo $f['stat_suffix']; ?></p>
                                <p class="abt-stat__desc" data-field="stat_desc"><?php echo $f['stat_desc']; ?></p>
                            </div>
                        </div>

                        <?php /* Gender card */ ?>
                        <div class="abt-gender" data-field="gender">
                            <div class="abt-gender__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="110" height="110" viewBox="0 0 110 110" fill="none" aria-hidden="true">
                                    <path d="M110 55C110 85.3757 85.3757 110 55 110C24.6243 110 0 85.3757 0 55C0 24.6243 24.6243 0 55 0C85.3757 0 110 24.6243 110 55ZM22.6871 55C22.6871 72.8459 37.1541 87.3129 55 87.3129C72.8459 87.3129 87.3129 72.8459 87.3129 55C87.3129 37.1541 72.8459 22.6871 55 22.6871C37.1541 22.6871 22.6871 37.1541 22.6871 55Z" fill="white"/>
                                    <path d="M55 110C69.5869 110 83.5764 104.205 93.8909 93.8909C104.205 83.5764 110 69.5869 110 55C110 40.4131 104.205 26.4236 93.8909 16.1091C83.5764 5.79463 69.5869 2.84018e-06 55 0L55 22.6856C63.5703 22.6856 71.7896 26.0901 77.8498 32.1502C83.9099 38.2104 87.3144 46.4297 87.3144 55C87.3144 63.5703 83.9099 71.7896 77.8498 77.8498C71.7896 83.9099 63.5703 87.3144 55 87.3144L55 110Z" fill="#C9372C"/>
                                </svg>
                            </div>
                            <div class="abt-gender__badges">
                                <span class="abt-gender__badge abt-gender__badge--light" data-field="badge_1_text"><?php echo $f['badge_1_text']; ?></span>
                                <span class="abt-gender__badge abt-gender__badge--red"   data-field="badge_2_text"><?php echo $f['badge_2_text']; ?></span>
                            </div>
                        </div>

                    </div><!-- .abt-cards-col -->

                </div><!-- .abt-panel__inner -->
            </div><!-- .abt-panel -->

            <?php /* ── RIGHT PANEL: accordion (identical to ss-) ── */ ?>
            <div class="abt-accordion">
                <?php foreach ( $services as $i => $svc ) :
                    $is_first  = ( 0 === $i );
                    $svc_id    = 'abt-svc-' . $i;
                    $title     = esc_html( $svc['title']       ?? '' );
                    $desc      = $svc['description'] ? wp_kses( $svc['description'], [ 'br' => [] ] ) : '';
                    $link_raw  = $svc['cta_link']               ?? null;
                    $link_url  = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
                    $link_lbl  = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'Read More', 'lionwood' );
                    $link_tgt  = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';
                    $panel_data = wp_json_encode( $panels[ $i ] );
                ?>
                <div
                    class="abt-accordion__item<?php echo $is_first ? ' is-active' : ''; ?>"
                    data-panel="<?php echo esc_attr( $panel_data ); ?>"
                >
                    <button
                        class="abt-accordion__trigger"
                        aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $svc_id ); ?>"
                    >
                        <span class="abt-accordion__title"><?php echo $title; ?></span>
                        <span class="abt-accordion__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                <path d="M9.12534 3.04484L9.12534 15.2187M13.6906 10.6535L9.12534 15.2187L4.56012 10.6535" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="abt-accordion__body" id="<?php echo esc_attr( $svc_id ); ?>" <?php echo $is_first ? '' : 'hidden'; ?>>
                        <?php if ( $desc ) : ?>
                            <p class="abt-accordion__desc"><?php echo $desc; ?></p>
                        <?php endif; ?>
                        <?php if ( $link_url ) : ?>
                            <div class="abt-accordion__cta-row">
                                <a class="abt-accordion__cta-btn" href="<?php echo $link_url; ?>" target="<?php echo esc_attr( $link_tgt ); ?>"><?php echo $link_lbl; ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div><!-- .abt-accordion -->

        </div><!-- .abt-section__body -->

    </div><!-- .abt-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
