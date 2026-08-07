<?php
/**
 * Block: Partnership Technologies Section
 * Slug: acf/partnership-technologies
 *
 * Based on technologies-section (ts2-) but:
 * — No tabs, no marquee
 * — Label badge (opacity 0.5) instead of marquee
 * — Max 9 technologies
 * — CTA link button below grid
 * — No top border-radius
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$label       = get_field( 'label' )        ?: '';
$technologies = array_slice( get_field( 'technologies' ) ?: [], 0, 9 );
$cta_link    = get_field( 'cta_link' );
$cta_url     = ! empty( $cta_link['url'] )    ? esc_url( $cta_link['url'] )    : '';
$cta_label   = ! empty( $cta_link['title'] )  ? esc_html( $cta_link['title'] ) : __( 'View All Technologies', 'lionwood' );
$cta_target  = ! empty( $cta_link['target'] ) ? $cta_link['target']             : '_self';

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

// Build tech data for JS
$techs_data = [];
foreach ( $technologies as $tech ) {
    $icon = $tech['icon'] ?? null;
    $techs_data[] = [
        'name'     => esc_html( $tech['name'] ?? '' ),
        'icon_url' => $icon ? esc_url( $icon['url'] ) : '',
        'icon_alt' => $icon ? esc_attr( $icon['alt'] ?: $tech['name'] ) : '',
    ];
}
$marquee = esc_html( $label );
?>

<section
    class="pt2-section"
    style="
        --pt2-pt: <?php echo $pt; ?>px;
        --pt2-pb: <?php echo $pb; ?>px;
        --pt2-pt-mob: <?php echo $pt_mob; ?>px;
        --pt2-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Marquee ── */ ?>
    <?php if ( $marquee ) : ?>
        <div class="pt2-marquee" aria-hidden="true">
            <div class="pt2-marquee__track">
                <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                    <span class="pt2-marquee__item"><?php echo $marquee; ?></span>
                <?php endfor; ?>
                <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                    <span class="pt2-marquee__item"><?php echo $marquee; ?></span>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="pt2-section__container">

        <?php /* ── Grid — filled by JS ── */ ?>
        <div
            class="pt2-grid"
            data-technologies="<?php echo esc_attr( wp_json_encode( $techs_data ) ); ?>"
            aria-live="polite"
        ></div>

        <?php /* ── CTA button ── */ ?>
        <?php if ( $cta_url ) : ?>
            <div class="pt2-cta">
                <a
                    class="pt2-btn"
                    href="<?php echo $cta_url; ?>"
                    target="<?php echo esc_attr( $cta_target ); ?>"
                    <?php echo $cta_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $cta_label; ?></a>
            </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
