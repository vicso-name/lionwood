<?php
/**
 * Block: About Partners & Associations
 * ACF block slug : acf/about-partners
 *
 * Desktop grid 16 cols × 4 rows:
 *   Upper tier (row 1–2):
 *     L1 = col 4–5  / row 1–2
 *     L2 = col 7–8  / row 1–2
 *     L3 = col 11–12 / row 1–2
 *   Lower tier (row 3–4):
 *     L4 = col 1–2  / row 3–4
 *     L5 = col 5–6  / row 3–4
 *     L6 = col 9–10 / row 3–4
 *     L7 = col 15–16 / row 3–4
 *   Text cells:
 *     T1 = col 1–2  / row 2   "/ Government partner"
 *     T2 = col 13–14 / row 1  "/ Innovation partner"
 *     T3 = col 7–8  / row 4   "/ Strategic partner"
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: 'Partners';
$title_bottom = get_field( 'title_bottom' ) ?: 'and Associations';

$logo_1 = get_field( 'logo_1' );
$logo_2 = get_field( 'logo_2' );
$logo_3 = get_field( 'logo_3' );
$logo_4 = get_field( 'logo_4' );
$logo_5 = get_field( 'logo_5' );
$logo_6 = get_field( 'logo_6' );
$logo_7 = get_field( 'logo_7' );

$allowed = [ 'br' => [] ];
$text_1  = get_field( 'text_1' ) ? wp_kses( get_field( 'text_1' ), $allowed ) : '';
$text_2  = get_field( 'text_2' ) ? wp_kses( get_field( 'text_2' ), $allowed ) : '';
$text_3  = get_field( 'text_3' ) ? wp_kses( get_field( 'text_3' ), $allowed ) : '';

$cta_link      = get_field( 'cta_link' );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

if ( ! function_exists( 'apm_logo_img' ) ) {
    function apm_logo_img( ?array $logo, string $alt = '' ): string {
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
    class="apm-section"
    style="
        --apm-pt: <?php echo $pt; ?>px;
        --apm-pb: <?php echo $pb; ?>px;
        --apm-pt-mob: <?php echo $pt_mob; ?>px;
        --apm-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="apm-section__container">

        <div class="apm-heading">
            <span class="apm-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="apm-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php /* ══════════════════════════════════════════════════════
               DESKTOP GRID — 16 cols × 4 rows, unit=81px, gap=4px
               ══════════════════════════════════════════════════════ */ ?>
        <div class="apm-grid apm-grid--desktop">

            <?php /* ── ROW 1 ── */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:1;     grid-row:1;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:2;     grid-row:1;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;     grid-row:1;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:4/6;   grid-row:1/3;"><?php echo apm_logo_img( $logo_1 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:6;     grid-row:1;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:7;     grid-row:1;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:8/10;  grid-row:1/3;"><?php echo apm_logo_img( $logo_2 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:10;    grid-row:1;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:11;    grid-row:1;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:12/14; grid-row:1/3;"><?php echo apm_logo_img( $logo_3 ); ?></div>
            <div class="apm-cell apm-cell--rect"  style="grid-column:14/16; grid-row:1;">
                <?php if ( $text_2 ) : ?><p class="apm-cell__text"><?php echo $text_2; ?></p><?php endif; ?>
            </div>
            <div class="apm-cell apm-cell--small" style="grid-column:16;    grid-row:1;"></div>

            <?php /* ── ROW 2 (L1/L2/L3 continue) ── */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:1;     grid-row:2;"></div>
            <div class="apm-cell apm-cell--rect"  style="grid-column:2/4;   grid-row:2;">
                <?php if ( $text_1 ) : ?><p class="apm-cell__text"><?php echo $text_1; ?></p><?php endif; ?>
            </div>
            <?php /* col 4–5: L1 continues */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:6;     grid-row:2;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:7;     grid-row:2;"></div>
            <?php /* col 8–9: L2 continues */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:10;    grid-row:2;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:11;    grid-row:2;"></div>
            <?php /* col 12–13: L3 continues */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:14;    grid-row:2;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:15;    grid-row:2;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:16;    grid-row:2;"></div>

            <?php /* ── ROW 3 ── */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:1;     grid-row:3;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:2/4;   grid-row:3/5;"><?php echo apm_logo_img( $logo_4 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;     grid-row:3;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:5;     grid-row:3;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:6/8;   grid-row:3/5;"><?php echo apm_logo_img( $logo_5 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:8;     grid-row:3;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:9;     grid-row:3;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:10/12; grid-row:3/5;"><?php echo apm_logo_img( $logo_6 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:12;    grid-row:3;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:13;    grid-row:3;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:14/16; grid-row:3/5;"><?php echo apm_logo_img( $logo_7 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:16;    grid-row:3;"></div>

            <?php /* ── ROW 4 (L4/L5/L6/L7 continue) ── */ ?>
            <?php /* col 1: freed by L4 shift */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:1;     grid-row:4;"></div>
            <?php /* col 2–3: L4 continues */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:4;     grid-row:4;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:5;     grid-row:4;"></div>
            <?php /* col 6–7: L5 continues */ ?>
            <div class="apm-cell apm-cell--rect"  style="grid-column:8/10;  grid-row:4;">
                <?php if ( $text_3 ) : ?><p class="apm-cell__text"><?php echo $text_3; ?></p><?php endif; ?>
            </div>
            <?php /* col 10–11: L6 continues */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:12;    grid-row:4;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:13;    grid-row:4;"></div>
            <?php /* col 14–15: L7 continues */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:16;    grid-row:4;"></div>

        </div><!-- .apm-grid--desktop -->


        <?php /* ══════════════════════════════════════════════════════
               MOBILE GRID — 4 cols × fluid rows
               Logos zigzag: odd = left (cols 1–2), even = right (cols 3–4)
               ══════════════════════════════════════════════════════ */ ?>
        <div class="apm-grid apm-grid--mobile">

            <?php /* L1: cols 1–2, rows 1–2 */ ?>
            <div class="apm-cell apm-cell--large" style="grid-column:1/3; grid-row:1/3;"><?php echo apm_logo_img( $logo_1 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;   grid-row:1;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;   grid-row:1;"></div>
            <div class="apm-cell apm-cell--rect"  style="grid-column:3/5; grid-row:2;">
                <?php if ( $text_1 ) : ?><p class="apm-cell__text"><?php echo $text_1; ?></p><?php endif; ?>
            </div>

            <?php /* L2: cols 3–4, rows 3–4 */ ?>
            <div class="apm-cell apm-cell--rect"  style="grid-column:1/3; grid-row:3;">
                <?php if ( $text_2 ) : ?><p class="apm-cell__text"><?php echo $text_2; ?></p><?php endif; ?>
            </div>
            <div class="apm-cell apm-cell--large" style="grid-column:3/5; grid-row:3/5;"><?php echo apm_logo_img( $logo_2 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:1;   grid-row:4;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:2;   grid-row:4;"></div>

            <?php /* L3: cols 1–2, rows 5–6 */ ?>
            <div class="apm-cell apm-cell--large" style="grid-column:1/3; grid-row:5/7;"><?php echo apm_logo_img( $logo_3 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;   grid-row:5;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;   grid-row:5;"></div>
            <div class="apm-cell apm-cell--rect"  style="grid-column:3/5; grid-row:6;">
                <?php if ( $text_3 ) : ?><p class="apm-cell__text"><?php echo $text_3; ?></p><?php endif; ?>
            </div>

            <?php /* L4: cols 3–4, rows 7–8 */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:1;   grid-row:7;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:2;   grid-row:7;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:3/5; grid-row:7/9;"><?php echo apm_logo_img( $logo_4 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:1;   grid-row:8;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:2;   grid-row:8;"></div>

            <?php /* L5: cols 1–2, rows 9–10 */ ?>
            <div class="apm-cell apm-cell--large" style="grid-column:1/3; grid-row:9/11;"><?php echo apm_logo_img( $logo_5 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;   grid-row:9;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;   grid-row:9;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;   grid-row:10;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;   grid-row:10;"></div>

            <?php /* L6: cols 3–4, rows 11–12 */ ?>
            <div class="apm-cell apm-cell--small" style="grid-column:1;   grid-row:11;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:2;   grid-row:11;"></div>
            <div class="apm-cell apm-cell--large" style="grid-column:3/5; grid-row:11/13;"><?php echo apm_logo_img( $logo_6 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:1;   grid-row:12;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:2;   grid-row:12;"></div>

            <?php /* L7: cols 1–2, rows 13–14 */ ?>
            <div class="apm-cell apm-cell--large" style="grid-column:1/3; grid-row:13/15;"><?php echo apm_logo_img( $logo_7 ); ?></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;   grid-row:13;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;   grid-row:13;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:3;   grid-row:14;"></div>
            <div class="apm-cell apm-cell--small" style="grid-column:4;   grid-row:14;"></div>

        </div><!-- .apm-grid--mobile -->

        <?php if ( $cta_link ) : ?>
            <div class="apm-cta">
                <a
                    class="apm-cta__btn"
                    href="<?php echo esc_url( $cta_link['url'] ); ?>"
                    <?php if ( $cta_link['target'] ) : ?>target="<?php echo esc_attr( $cta_link['target'] ); ?>"<?php endif; ?>
                >
                    <?php echo esc_html( $cta_link['title'] ); ?>
                </a>
            </div>
        <?php endif; ?>

    </div><!-- .apm-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>

</section>
