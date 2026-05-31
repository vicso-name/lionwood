<?php
/**
 * Block: Certified Section
 *
 * ACF block slug : acf/certified-section
 * Template file  : blocks/certified-section/certified-section.php
 *
 * Two separate grid markups:
 *   .cert-grid--desktop  — 16-col grid, hidden on mobile
 *   .cert-grid--mobile   — 4-col grid, hidden on desktop
 */

defined( 'ABSPATH' ) || exit;

// ── Fields ───────────────────────────────────────────────────────────────────
$pt      = absint( get_field( 'padding_top' )        ?: 80 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 80 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: __( 'Certified', 'theme' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'Experts You Trust', 'theme' );

$logo_1 = get_field( 'logo_1' );
$logo_2 = get_field( 'logo_2' );
$logo_3 = get_field( 'logo_3' );

$text_1_raw = get_field( 'text_rect_1' );
$text_2_raw = get_field( 'text_rect_2' );
$text_1     = $text_1_raw ? wp_kses( $text_1_raw, [ 'br' => [] ] ) : '';
$text_2     = $text_2_raw ? wp_kses( $text_2_raw, [ 'br' => [] ] ) : '';

// ── Logo helper ───────────────────────────────────────────────────────────────
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

        <?php /* ── Heading ────────────────────────────────────────────────── */ ?>
        <div class="cert-section__heading">
            <span class="cert-section__title-top"><?php echo esc_html( $title_top ); ?></span>
            <span class="cert-section__title-bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php /* ══════════════════════════════════════════════════════════════
               DESKTOP GRID  (visible ≥ 769px)
               16 columns × 2 rows, unit = 81px, gap = 4px

               Row 1: s s s | L1(2×2) | s s | L2(2×2) | s s | L3(2×2) | R2(2×1) s
               Row 2: s | R1(2×1) | — | s s | — | s s | — | s s s
               (s = small 1×1, L = large logo 2×2, R = rect 2×1)
               ══════════════════════════════════════════════════════════════ */ ?>
        <div class="cert-grid cert-grid--desktop" aria-hidden="false">

            <?php /* ── Row 1 ─────────────────────────────────────────────── */ ?>
            <?php /* cols 1–3: three small cells */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:2; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:3; grid-row:1;"></div>

            <?php /* col 4–5, row 1–2: Logo 1 (2×2) */ ?>
            <div class="cert-cell cert-cell--large" style="grid-column:4/6; grid-row:1/3;">
                <?php echo cert_logo_img( $logo_1, $title_top ); ?>
            </div>

            <?php /* cols 6–7: two small cells row 1 */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:6; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:7; grid-row:1;"></div>

            <?php /* col 8–9, row 1–2: Logo 2 (2×2) */ ?>
            <div class="cert-cell cert-cell--large" style="grid-column:8/10; grid-row:1/3;">
                <?php echo cert_logo_img( $logo_2, $title_top ); ?>
            </div>

            <?php /* cols 10–11: two small cells row 1 */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:10; grid-row:1;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:11; grid-row:1;"></div>

            <?php /* col 12–13, row 1–2: Logo 3 (2×2) */ ?>
            <div class="cert-cell cert-cell--large" style="grid-column:12/14; grid-row:1/3;">
                <?php echo cert_logo_img( $logo_3, $title_top ); ?>
            </div>

            <?php /* col 14–15, row 1: Text rect 2 (2×1) */ ?>
            <div class="cert-cell cert-cell--rect" style="grid-column:14/16; grid-row:1;">
                <?php if ( $text_2 ) : ?>
                    <p class="cert-cell__text"><?php echo $text_2; ?></p>
                <?php endif; ?>
            </div>

            <?php /* col 16, row 1: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:16; grid-row:1;"></div>

            <?php /* ── Row 2 ─────────────────────────────────────────────── */ ?>
            <?php /* col 1, row 2: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:2;"></div>

            <?php /* col 2–3, row 2: Text rect 1 (2×1) */ ?>
            <div class="cert-cell cert-cell--rect" style="grid-column:2/4; grid-row:2;">
                <?php if ( $text_1 ) : ?>
                    <p class="cert-cell__text"><?php echo $text_1; ?></p>
                <?php endif; ?>
            </div>

            <?php /* Logo 1 occupies col 4–5 row 2 (already rendered above) */ ?>

            <?php /* cols 6–7, row 2: two small cells */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:6; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:7; grid-row:2;"></div>

            <?php /* Logo 2 occupies col 8–9 row 2 */ ?>

            <?php /* cols 10–11, row 2: two small cells */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:10; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:11; grid-row:2;"></div>

            <?php /* Logo 3 occupies col 12–13 row 2 */ ?>

            <?php /* cols 14–16, row 2: three small cells */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:14; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:15; grid-row:2;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:16; grid-row:2;"></div>

        </div><!-- .cert-grid--desktop -->


        <?php /* ══════════════════════════════════════════════════════════════
               MOBILE GRID  (visible < 769px)
               4 columns × 8 rows, unit = 81px, gap = 4px

               Row 1–2: L1(2×2) | s s
               Row 1:   —       | s
               Row 2:   —       | R1(2×1)
               Row 3–4: s | L2(2×2) | s
               Row 3:   s | —       | s
               Row 4:   s | —       | s — wait, 4 cols only...

               Per design (image 3):
               Row 1–2: cols 1–2 = L1  |  col 3 = s  |  col 4 = s
               Row 2:   —              |  col 3–4 = R1(2×1)
               Row 3–4: col 1 = s  |  cols 2–3 = L2  |  col 4 = s
               Row 5–6: col 1 = s  |  col 2 = s  |  cols 3–4 = L3
               Row 7:   col 1–2 = R2(2×1)  |  col 3 = s  |  col 4 = s (part of L3)
               Row 7–8: —  wait — L3 is 2×2 so row 5–6
               Row 7:   cols 1–2 = R2  |  — L3 done
               Row 8:   col 1 = s  |  col 2 = s  |  col 3 = s  |  col 4 = s
               ══════════════════════════════════════════════════════════════ */ ?>
        <div class="cert-grid cert-grid--mobile" aria-hidden="true">

            <?php /* Logo 1: cols 1–2, rows 1–2 */ ?>
            <div class="cert-cell cert-cell--large" style="grid-column:1/3; grid-row:1/3;">
                <?php echo cert_logo_img( $logo_1, $title_top ); ?>
            </div>

            <?php /* col 3, row 1: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:3; grid-row:1;"></div>
            <?php /* col 4, row 1: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:4; grid-row:1;"></div>

            <?php /* cols 3–4, row 2: Text rect 1 (2×1) */ ?>
            <div class="cert-cell cert-cell--rect" style="grid-column:3/5; grid-row:2;">
                <?php if ( $text_1 ) : ?>
                    <p class="cert-cell__text"><?php echo $text_1; ?></p>
                <?php endif; ?>
            </div>

            <?php /* col 1, rows 3–4: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:3;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:4;"></div>

            <?php /* Logo 2: cols 2–3, rows 3–4 */ ?>
            <div class="cert-cell cert-cell--large" style="grid-column:2/4; grid-row:3/5;">
                <?php echo cert_logo_img( $logo_2, $title_top ); ?>
            </div>

            <?php /* col 4, rows 3–4: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:4; grid-row:3;"></div>
            <div class="cert-cell cert-cell--small" style="grid-column:4; grid-row:4;"></div>

            <?php /* col 1, row 5: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:1; grid-row:5;"></div>
            <?php /* col 2, row 5: small */ ?>
            <div class="cert-cell cert-cell--small" style="grid-column:2; grid-row:5;"></div>

            <?php /* Logo 3: cols 3–4, rows 5–6 */ ?>
            <div class="cert-cell cert-cell--large" style="grid-column:3/5; grid-row:5/7;">
                <?php echo cert_logo_img( $logo_3, $title_top ); ?>
            </div>

            <?php /* cols 1–2, row 6: Text rect 2 (2×1) */ ?>
            <div class="cert-cell cert-cell--rect" style="grid-column:1/3; grid-row:6;">
                <?php if ( $text_2 ) : ?>
                    <p class="cert-cell__text"><?php echo $text_2; ?></p>
                <?php endif; ?>
            </div>

        </div><!-- .cert-grid--mobile -->

    </div><!-- .cert-section__container -->
</section>
