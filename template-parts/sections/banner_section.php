<?php
/**
 * Block: Banner Section
 *
 * ACF block slug : acf/banner-section
 * Standalone banner — same design as sps-banner but configurable bg color.
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$section_bg    = get_field( 'section_bg_color' ) ?: '#F7F7F7';
$bg_color      = get_field( 'bg_color' )  ?: '#C83030';
$bg_image      = get_field( 'bg_image' );
$text_raw      = get_field( 'text' ) ?: '';
$text          = $text_raw ? wp_kses( $text_raw, [ 'br' => [] ] ) : '';
$link_raw      = get_field( 'link' );
$link_url      = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
$link_lbl      = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'Book a Meeting', 'lionwood' );
$link_tgt      = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Calendar icon
$cal_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <rect x="1.5" y="3" width="15" height="13.5" rx="2" stroke="currentColor" stroke-width="1.2"/>
    <path d="M1.5 7H16.5" stroke="currentColor" stroke-width="1.2"/>
    <path d="M6 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M12 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
</svg>';

// Build banner bg style
$bg_style = 'background-color: ' . esc_attr( $bg_color ) . ';';
if ( $bg_image ) {
    $bg_style .= ' background-image: url(\'' . esc_url( $bg_image['url'] ) . '\');';
}
?>

<section
    class="ban-section"
    style="
        --ban-pt: <?php echo $pt; ?>px;
        --ban-pb: <?php echo $pb; ?>px;
        --ban-pt-mob: <?php echo $pt_mob; ?>px;
        --ban-pb-mob: <?php echo $pb_mob; ?>px;
        --ban-bg: <?php echo esc_attr( $bg_color ); ?>;
        --ban-section-bg: <?php echo esc_attr( $section_bg ); ?>;
    "
>
    <div class="ban-section__container">

        <div class="ban-banner" style="<?php echo $bg_style; ?>">
            <?php if ( $bg_image ) : ?>
                <div class="ban-banner__overlay" aria-hidden="true"></div>
            <?php endif; ?>

            <?php if ( $text ) : ?>
                <p class="ban-banner__text"><?php echo $text; ?></p>
            <?php endif; ?>

            <?php if ( $link_url ) : ?>
                <a
                    class="ban-banner__btn"
                    href="<?php echo $link_url; ?>"
                    target="<?php echo esc_attr( $link_tgt ); ?>"
                    <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                >
                    <?php echo $cal_icon; ?>
                    <?php echo $link_lbl; ?>
                </a>
            <?php endif; ?>
        </div>

    </div><!-- .ban-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
