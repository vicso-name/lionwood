<?php
/**
 * Block: Case Core Capabilities
 *
 * ACF block slug : acf/case-core-capabilities
 *
 * Left: dark panel with key features (synced to active accordion item)
 * Right: accordion items
 * JS syncs left panel content when accordion item changes
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 35 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 120 );
$title_top     = esc_html( get_field( 'title_top' )    ?: __( 'Core System', 'lionwood' ) );
$title_bottom  = esc_html( get_field( 'title_bottom' ) ?: __( 'Capabilities', 'lionwood' ) );
$panel_badge   = esc_html( get_field( 'panel_badge' )  ?: __( 'Key features', 'lionwood' ) );
$items         = get_field( 'items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

if ( empty( $items ) ) return;

// Check icon SVG
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
    <path d="M12.0784 3.67079C12.1878 3.78018 12.2492 3.92853 12.2492 4.08321C12.2492 4.23789 12.1878 4.38623 12.0784 4.49563L6.24509 10.329C6.1357 10.4383 5.98736 10.4998 5.83268 10.4998C5.678 10.4998 5.52965 10.4383 5.42026 10.329L2.50359 7.41229C2.39733 7.30227 2.33854 7.15492 2.33987 7.00197C2.34119 6.84903 2.40254 6.70272 2.5107 6.59456C2.61885 6.48641 2.76516 6.42506 2.91811 6.42373C3.07106 6.4224 3.21841 6.4812 3.32843 6.58746L5.83268 9.09171L11.2536 3.67079C11.363 3.56143 11.5113 3.5 11.666 3.5C11.8207 3.5 11.969 3.56143 12.0784 3.67079Z" fill="#F7F7F7"/>
</svg>';

// Arrow SVG
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none" aria-hidden="true">
    <path d="M9.12619 3.04399L9.12619 15.2179M13.6914 10.6527L9.12619 15.2179L4.56097 10.6527" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

// Encode all items features as JSON for JS
$items_data = [];
foreach ( $items as $item ) {
    $features = [];
    foreach ( ( $item['features'] ?? [] ) as $f ) {
        if ( ! empty( $f['text'] ) ) $features[] = esc_html( $f['text'] );
    }
    $items_data[] = [ 'features' => $features ];
}
?>

<section
    class="ccc-section"
    style="
        --ccc-pt: <?php echo $pt; ?>px;
        --ccc-pb: <?php echo $pb; ?>px;
        --ccc-pt-mob: <?php echo $pt_mob; ?>px;
        --ccc-pb-mob: <?php echo $pb_mob; ?>px;
    "
    data-ccc-items='<?php echo wp_json_encode( $items_data ); ?>'
>
    <div class="ccc-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="ccc-heading">
            <span class="ccc-heading__top"><?php echo $title_top; ?></span>
            <span class="ccc-heading__bottom"><?php echo $title_bottom; ?></span>
        </div>

        <?php /* ── Two columns ──────────────────────────────────────────── */ ?>
        <div class="ccc-row">

            <?php /* Left: dark panel ─────────────────────────────────── */ ?>
            <div class="ccc-panel" aria-live="polite" aria-atomic="true">
                <span class="ccc-panel__badge"><?php echo $panel_badge; ?></span>
                <ul class="ccc-panel__list" data-panel-list>
                    <?php foreach ( ( $items[0]['features'] ?? [] ) as $feature ) :
                        if ( empty( $feature['text'] ) ) continue;
                    ?>
                        <li class="ccc-panel__item">
                            <span class="ccc-panel__icon"><?php echo $check_svg; ?></span>
                            <span class="ccc-panel__text"><?php echo esc_html( $feature['text'] ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php /* Right: accordion ─────────────────────────────────── */ ?>
            <div class="ccc-accordion" data-ccc-accordion>
                <?php foreach ( $items as $i => $item ) :
                    $title = esc_html( $item['title'] ?? '' );
                    $desc  = $item['description'] ?? '';
                    $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    $is_first = $i === 0;
                    $item_id  = 'ccc-item-' . $i;
                    $panel_id = 'ccc-panel-' . $i;
                ?>
                    <div
                        class="ccc-item<?php echo $is_first ? ' is-active' : ''; ?>"
                        data-ccc-item
                        data-index="<?php echo $i; ?>"
                    >
                        <button
                            class="ccc-item__trigger"
                            id="<?php echo esc_attr( $item_id ); ?>"
                            aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                        >
                            <span class="ccc-item__title"><?php echo $title; ?></span>
                            <span class="ccc-item__arrow"><?php echo $arrow_svg; ?></span>
                        </button>

                        <div
                            class="ccc-item__body"
                            id="<?php echo esc_attr( $panel_id ); ?>"
                            role="region"
                            aria-labelledby="<?php echo esc_attr( $item_id ); ?>"
                            aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
                        >
                            <?php if ( $desc_out ) : ?>
                                <p class="ccc-item__desc"><?php echo $desc_out; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div><!-- .ccc-accordion -->

        </div><!-- .ccc-row -->

    </div><!-- .ccc-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
