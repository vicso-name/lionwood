<?php
/**
 * Block: Solutions Showcase
 *
 * ACF block slug : acf/solutions-showcase
 * Template file  : blocks/solutions-showcase/solutions-showcase.php
 *
 * Left panel data (stat, image, includes) is embedded as JSON in
 * data-attributes on each accordion trigger. JS swaps the panel on click
 * with a CSS fade transition — no AJAX needed.
 */

defined( 'ABSPATH' ) || exit;

// ── Block fields ─────────────────────────────────────────────────────────────
$pt      = absint( get_field( 'padding_top' )        ?: 80 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 80 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 80 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 80 );

$title_top    = get_field( 'title_top' )    ?: __( 'Our Services:', 'theme' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'From Concept to Growth', 'theme' );
$description  = get_field( 'description' )  ?: '';
$desc_escaped = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';

$all_link_raw   = get_field( 'all_services_link' );
$all_link_url   = ! empty( $all_link_raw['url'] )    ? esc_url( $all_link_raw['url'] )    : '';
$all_link_label = ! empty( $all_link_raw['title'] )  ? esc_html( $all_link_raw['title'] ) : __( 'All Services', 'theme' );
$all_link_tgt   = ! empty( $all_link_raw['target'] ) ? $all_link_raw['target']             : '_self';

$services = get_field( 'services' ) ?: [];

if ( empty( $services ) ) return;

// ── Build panel data array for JS ────────────────────────────────────────────
$panels = [];
foreach ( $services as $i => $svc ) {
    $img    = $svc['image']    ?? null;
    $icon   = $svc['stat_icon'] ?? null;
    $items  = $svc['includes_items'] ?: [];

    $panels[] = [
        'stat_value'      => esc_html( $svc['stat_value']       ?? '' ),
        'stat_desc'       => esc_html( $svc['stat_desc']        ?? '' ),
        'stat_icon_url'   => $icon ? esc_url( $icon['url'] )    : '',
        'stat_icon_alt'   => $icon ? esc_attr( $icon['alt'] )   : '',
        'image_url'       => $img  ? esc_url( $img['url'] )     : '',
        'image_alt'       => $img  ? esc_attr( $img['alt'] )    : esc_html( $svc['title'] ?? '' ),
        'includes_label'  => esc_html( $svc['includes_label']   ?? __( 'Includes:', 'theme' ) ),
        'includes_items'  => array_map( fn( $it ) => esc_html( $it['text'] ?? '' ), $items ),
    ];
}

$first = $panels[0];
?>

<section
    class="ss-section"
    style="
        --ss-pt: <?php echo $pt; ?>px;
        --ss-pb: <?php echo $pb; ?>px;
        --ss-pt-mob: <?php echo $pt_mob; ?>px;
        --ss-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ss-section__container">

        <?php /* ── Heading row ────────────────────────────────────────────── */ ?>
        <div class="ss-section__top">
            <div class="ss-section__heading">
                <span class="ss-section__title-top"><?php echo esc_html( $title_top ); ?></span>
                <span class="ss-section__title-bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </div>
            <?php if ( $desc_escaped ) : ?>
                <p class="ss-section__description"><?php echo $desc_escaped; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Main two-column layout ──────────────────────────────────── */ ?>
        <div class="ss-section__body">

            <?php /* ── LEFT PANEL ───────────────────────────────────────────
                   Driven by data-* from the active accordion item via JS.
                   Initial state = first service data.
               */ ?>
            <div class="ss-panel" aria-live="polite">

                <?php /* Top row inside left panel: stat card + image */ ?>
                <div class="ss-panel__top">

                    <?php /* Stat card */ ?>
                    <div class="ss-panel__stat">
                        <div class="ss-panel__stat-content">
                            <p class="ss-panel__stat-value" data-field="stat_value">
                                <?php echo $first['stat_value']; ?>
                            </p>
                            <p class="ss-panel__stat-desc" data-field="stat_desc">
                                <?php echo $first['stat_desc']; ?>
                            </p>
                        </div>
                        <div class="ss-panel__stat-icon" data-field="stat_icon">
                            <?php if ( $first['stat_icon_url'] ) : ?>
                                <img src="<?php echo $first['stat_icon_url']; ?>"
                                     alt="<?php echo $first['stat_icon_alt']; ?>"
                                     width="56" height="56" loading="lazy">
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php /* Image */ ?>
                    <div class="ss-panel__image" data-field="image">
                        <?php if ( $first['image_url'] ) : ?>
                            <img src="<?php echo $first['image_url']; ?>"
                                 alt="<?php echo $first['image_alt']; ?>"
                                 loading="eager">
                        <?php endif; ?>
                    </div>

                </div><!-- .ss-panel__top -->

                <?php /* Includes dark card */ ?>
                <div class="ss-panel__includes">
                    <span class="ss-panel__includes-label" data-field="includes_label">
                        <?php echo $first['includes_label']; ?>
                    </span>
                    <ul class="ss-panel__includes-list" data-field="includes_items">
                        <?php foreach ( $first['includes_items'] as $item ) : ?>
                            <li class="ss-panel__includes-item">
                                <span class="ss-panel__includes-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M14.1084 3.47703C14.0237 3.389 13.9229 3.31913 13.812 3.27145C13.701 3.22377 13.5819 3.19922 13.4617 3.19922C13.3415 3.19922 13.2224 3.22377 13.1114 3.27145C13.0004 3.31913 12.8997 3.389 12.815 3.47703L6.02971 10.4834L3.17897 7.53432C3.09106 7.44675 2.98728 7.3779 2.87356 7.33168C2.75985 7.28547 2.63842 7.26281 2.51621 7.26499C2.394 7.26717 2.27341 7.29415 2.16131 7.34439C2.04921 7.39463 1.94781 7.46715 1.86289 7.5578C1.77797 7.64845 1.71119 7.75546 1.66638 7.87273C1.62157 7.98999 1.59959 8.11521 1.6017 8.24123C1.60382 8.36725 1.62998 8.4916 1.6787 8.6072C1.72742 8.72279 1.79775 8.82736 1.88566 8.91493L5.38306 12.5214C5.46773 12.6094 5.56846 12.6793 5.67945 12.727C5.79044 12.7747 5.90948 12.7992 6.02971 12.7992C6.14995 12.7992 6.26899 12.7747 6.37998 12.727C6.49097 12.6793 6.5917 12.6094 6.67637 12.5214L14.1084 4.85764C14.2008 4.76969 14.2746 4.66295 14.325 4.54414C14.3755 4.42533 14.4016 4.29704 14.4016 4.16733C14.4016 4.03763 14.3755 3.90933 14.325 3.79052C14.2746 3.67172 14.2008 3.56498 14.1084 3.47703Z" fill="white"/>
                                    </svg>
                                </span>
                                <span><?php echo $item; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div><!-- .ss-panel -->

            <?php /* ── RIGHT PANEL: accordion ──────────────────────────────── */ ?>
            <div class="ss-accordion">

                <?php foreach ( $services as $i => $svc ) :
                    $is_first   = ( 0 === $i );
                    $svc_id     = 'ss-svc-' . $i;

                    $title      = esc_html( $svc['title']       ?? '' );
                    $desc       = $svc['description'] ? wp_kses( $svc['description'], [ 'br' => [] ] ) : '';
                    $cta_text   = esc_html( $svc['cta_text']    ?? '' );
                    $link_raw   = $svc['cta_link']               ?? null;
                    $link_url   = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
                    $link_label = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'Read More', 'theme' );
                    $link_tgt   = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';

                    // Panel data for JS (already sanitised above)
                    $panel_data = wp_json_encode( $panels[ $i ] );
                ?>
                <div
                    class="ss-accordion__item<?php echo $is_first ? ' is-active' : ''; ?>"
                    data-panel="<?php echo esc_attr( $panel_data ); ?>"
                >
                    <button
                        class="ss-accordion__trigger"
                        aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $svc_id ); ?>"
                    >
                        <span class="ss-accordion__title"><?php echo $title; ?></span>
                        <span class="ss-accordion__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                <path d="M9.12534 3.04484L9.12534 15.2187M13.6906 10.6535L9.12534 15.2187L4.56012 10.6535" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>

                    <div
                        class="ss-accordion__body"
                        id="<?php echo esc_attr( $svc_id ); ?>"
                        <?php echo $is_first ? '' : 'hidden'; ?>
                    >
                        <?php if ( $desc ) : ?>
                            <p class="ss-accordion__desc"><?php echo $desc; ?></p>
                        <?php endif; ?>

                        <?php if ( $cta_text || $link_url ) : ?>
                            <div class="ss-accordion__cta-row">
                                <?php if ( $cta_text ) : ?>
                                    <p class="ss-accordion__cta-text"><?php echo $cta_text; ?></p>
                                <?php endif; ?>
                                <?php if ( $link_url ) : ?>
                                    <a
                                        class="ss-accordion__cta-btn"
                                        href="<?php echo $link_url; ?>"
                                        target="<?php echo esc_attr( $link_tgt ); ?>"
                                        <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                                    ><?php echo $link_label; ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div><!-- .ss-accordion__body -->

                </div><!-- .ss-accordion__item -->
                <?php endforeach; ?>

            </div><!-- .ss-accordion -->

        </div><!-- .ss-section__body -->

        <?php /* ── All Services CTA ─────────────────────────────────────── */ ?>
        <?php if ( $all_link_url ) : ?>
            <div class="ss-section__cta">
                <a
                    class="ss-section__all-btn"
                    href="<?php echo $all_link_url; ?>"
                    target="<?php echo esc_attr( $all_link_tgt ); ?>"
                    <?php echo '_blank' === $all_link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $all_link_label; ?></a>
            </div>
        <?php endif; ?>

    </div><!-- .ss-section__container -->
</section>
