<?php
/**
 * Block: Certified Section
 *
 * ACF block slug : acf/certified-section
 * Template file  : blocks/certified-section/certified-section.php
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: __( 'Certified', 'lionwood' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'Experts You Trust', 'lionwood' );

$logo_1 = get_field( 'logo_1' );
$logo_2 = get_field( 'logo_2' );
$logo_3 = get_field( 'logo_3' );

$text_1_raw = get_field( 'text_rect_1' );
$text_2_raw = get_field( 'text_rect_2' );
$text_1     = $text_1_raw ? wp_kses( $text_1_raw, [ 'br' => [] ] ) : '';
$text_2     = $text_2_raw ? wp_kses( $text_2_raw, [ 'br' => [] ] ) : '';

$cta_link = get_field( 'cta_link' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#C83030';

if ( ! function_exists( 'cert_logo_img' ) ) {
    function cert_logo_img( ?array $logo, string $alt = '' ): string {
        if ( ! $logo ) return '';
        return sprintf(
            '<img src="%s" alt="%s" width="%s" height="%s" loading="lazy">',
            esc_url( $logo['url'] ),
            esc_attr( $logo['alt'] ?: $alt ),
            esc_attr( $logo['width'] ),
            esc_attr( $logo['height'] )
        );
    }
}
?>

<section
    class="cert-section"
    style="
        --cert-pt: <?php echo $pt; ?>px;
        --cert-pb: <?php echo $pb; ?>px;
        --cert-pt-mob: <?php echo $pt_mob; ?>px;
        --cert-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cert-section__container">

        <?php /* ── Heading ── */ ?>
        <div class="cert-section__heading">
            <span class="cert-section__title-top"><?php echo esc_html( $title_top ); ?></span>
            <span class="cert-section__title-bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php /* ── Desktop Grid ── */ ?>
        <div class="cert-grid cert-grid--desktop" aria-hidden="false">

            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:2; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:3; grid-row:1;"></div>
            <div class="cert-cell cert-cell--large" style="grid-column:4/6; grid-row:1/3;"><?php echo cert_logo_img( $logo_1, $title_top ); ?></div>
            <div class="cert-cell cert-cell--small" style="grid-column:6; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:7; grid-row:1;"></div>
            <div class="cert-cell cert-cell--large" style="grid-column:8/10; grid-row:1/3;"><?php echo cert_logo_img( $logo_2, $title_top ); ?></div>
            <div class="cert-cell cert-cell--small" style="grid-column:10; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:11; grid-row:1;"></div>
            <div class="cert-cell cert-cell--large" style="grid-column:12/14; grid-row:1/3;"><?php echo cert_logo_img( $logo_3, $title_top ); ?></div>
            <div class="cert-cell cert-cell--rect"  style="grid-column:14/16; grid-row:1;">
                <?php if ( $text_2 ) : ?><p class="cert-cell__text"><?php echo $text_2; ?></p><?php endif; ?>
            </div>
            <div class="cert-cell cert-cell--small" style="grid-column:16; grid-row:1;"></div>

            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:2;"></div>
            <div class="cert-cell cert-cell--rect"  style="grid-column:2/4; grid-row:2;">
                <?php if ( $text_1 ) : ?><p class="cert-cell__text"><?php echo $text_1; ?></p><?php endif; ?>
            </div>
            <div class="cert-cell cert-cell--small" style="grid-column:6; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:7; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:10; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:11; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:14; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:15; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:16; grid-row:2;"></div>

        </div><!-- .cert-grid--desktop -->

        <?php /* ── Mobile Grid ── */ ?>
        <div class="cert-grid cert-grid--mobile" aria-hidden="true">

            <div class="cert-cell cert-cell--large" style="grid-column:1/3; grid-row:1/3;"><?php echo cert_logo_img( $logo_1, $title_top ); ?></div>
            <div class="cert-cell cert-cell--small" style="grid-column:3; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:4; grid-row:1;"></div>
            <div class="cert-cell cert-cell--rect"  style="grid-column:3/5; grid-row:2;">
                <?php if ( $text_1 ) : ?><p class="cert-cell__text"><?php echo $text_1; ?></p><?php endif; ?>
            </div>

            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:3;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:4;"></div>
            <div class="cert-cell cert-cell--large" style="grid-column:2/4; grid-row:3/5;"><?php echo cert_logo_img( $logo_2, $title_top ); ?></div>
            <div class="cert-cell cert-cell--small" style="grid-column:4; grid-row:3;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:4; grid-row:4;"></div>

            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:5;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:2; grid-row:5;"></div>
            <div class="cert-cell cert-cell--large" style="grid-column:3/5; grid-row:5/7;"><?php echo cert_logo_img( $logo_3, $title_top ); ?></div>
            <div class="cert-cell cert-cell--rect"  style="grid-column:1/3; grid-row:6;">
                <?php if ( $text_2 ) : ?><p class="cert-cell__text"><?php echo $text_2; ?></p><?php endif; ?>
            </div>

        </div><!-- .cert-grid--mobile -->

        <?php /* ── Optional CTA Button ── */ ?>
        <?php if ( $cta_link && $cta_link['url'] ) : ?>
            <div class="cert-section__cta">
                <a
                    class="cert-section__btn"
                    href="<?php echo esc_url( $cta_link['url'] ); ?>"
                    <?php if ( $cta_link['target'] ) : ?>target="<?php echo esc_attr( $cta_link['target'] ); ?>"<?php endif; ?>
                    <?php echo $cta_link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                >
                    <?php echo esc_html( $cta_link['title'] ); ?>
                </a>
            </div>
        <?php endif; ?>

    </div><!-- .cert-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>

</section>
