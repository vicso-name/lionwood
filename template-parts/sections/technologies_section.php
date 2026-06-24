<?php
/**
 * Block: Technologies Section
 *
 * ACF block slug : acf/technologies-section
 * Template file  : blocks/technologies-section/technologies-section.php
 *
 * All tab data is embedded as JSON in data-technologies attribute.
 * JS handles tab switching, random grid placement, and stagger animation.
 */

defined( 'ABSPATH' ) || exit;

$pt          = absint( get_field( 'padding_top' )        ?: 100 );
$pb          = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob      = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob      = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$marquee_raw = get_field( 'marquee_text' ) ?: __( 'Technologies', 'lionwood' );
$marquee     = esc_html( $marquee_raw );
$tabs        = get_field( 'tabs' ) ?: [];

if ( empty( $tabs ) ) return;

// Build JSON data for JS — all tabs and their technologies
$tabs_data = [];
foreach ( $tabs as $tab ) {
    $techs = [];
    foreach ( $tab['technologies'] ?? [] as $tech ) {
        $icon = $tech['icon'] ?? null;
        $techs[] = [
            'name'     => esc_html( $tech['name'] ?? '' ),
            'icon_url' => $icon ? esc_url( $icon['url'] ) : '',
            'icon_alt' => $icon ? esc_attr( $icon['alt'] ?: $tech['name'] ) : '',
        ];
    }
    $tabs_data[] = [
        'name'         => esc_html( $tab['name'] ?? '' ),
        'technologies' => $techs,
    ];
}
?>

<section
    class="ts2-section"
    style="
        --ts2-pt: <?php echo $pt; ?>px;
        --ts2-pb: <?php echo $pb; ?>px;
        --ts2-pt-mob: <?php echo $pt_mob; ?>px;
        --ts2-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Marquee ───────────────────────────────────────────────────── */ ?>
    <div class="ts2-marquee" aria-hidden="true">
        <div class="ts2-marquee__track">
            <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                <span class="ts2-marquee__item"><?php echo $marquee; ?></span>
            <?php endfor; ?>
            <?php /* Duplicate for seamless loop */ ?>
            <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                <span class="ts2-marquee__item"><?php echo $marquee; ?></span>
            <?php endfor; ?>
        </div>
    </div>

    <div class="ts2-section__container">

        <?php /* ── Tabs ─────────────────────────────────────────────────── */ ?>
        <div class="ts2-tabs-scroll">
            <div class="ts2-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Technology categories', 'lionwood' ); ?>">
                <?php foreach ( $tabs as $i => $tab ) :
                    $is_first = ( 0 === $i );
                    $tab_id   = 'ts2-tab-' . $i;
                ?>
                    <button
                        class="ts2-tab<?php echo $is_first ? ' is-active' : ''; ?>"
                        role="tab"
                        aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
                        data-tab-index="<?php echo esc_attr( $i ); ?>"
                        id="<?php echo esc_attr( $tab_id ); ?>"
                    >
                        <?php echo esc_html( $tab['name'] ?? '' ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php /* ── Grid — rendered and managed by JS ──────────────────── */ ?>
        <div
            class="ts2-grid"
            data-technologies="<?php echo esc_attr( wp_json_encode( $tabs_data ) ); ?>"
            role="tabpanel"
            aria-live="polite"
        >
            <?php /* JS fills this with cells */ ?>
        </div>

    </div>

    <?php get_template_part('template-parts/partials/decor-bottom'); ?>

</section>
