<?php
/**
 * Block: Call to Action
 *
 * ACF block slug : acf/call-to-action
 * Template file  : blocks/call-to-action/call-to-action.php
 *
 * Layout:
 *   - Dark bg #111319
 *   - Centered heading block (698px)
 *   - 14×4 fixed grid with 3 text rectangles at fixed positions:
 *       Row 1 cols 1-2  : grid_text_1
 *       Row 2 cols 13-14: grid_text_2
 *       Row 3           : all squares
 *       Row 4 cols 2-3  : grid_text_3
 *   - Red CTA card centered over grid (position: absolute)
 */

defined( 'ABSPATH' ) || exit;

// $args takes priority over ACF block fields — used by archive/taxonomy templates
// to pass values from the Options Page without an ACF block context.
$pt     = absint( $args['padding_top']        ?? get_field( 'padding_top' )        ?? 100 );
$pb     = absint( $args['padding_bottom']     ?? get_field( 'padding_bottom' )     ?? 100 );
$pt_mob = absint( $args['padding_top_mob']    ?? get_field( 'padding_top_mob' )    ?? 70 );
$pb_mob = absint( $args['padding_bottom_mob'] ?? get_field( 'padding_bottom_mob' ) ?? 70 );

$title_top     = $args['title_top'] ?? get_field( 'title_top' ) ?? __( 'Ready to Accelerate', 'theme' );
$title_bot_raw = $args['title_bottom'] ?? get_field( 'title_bottom' ) ?? '';
$title_bottom  = $title_bot_raw ? wp_kses( $title_bot_raw, [ 'br' => [] ] ) : 'Your Business Growth?<br>Contact Us.';

$text1 = $args['grid_text_1'] ?? get_field( 'grid_text_1' ) ?? '';
$text2 = $args['grid_text_2'] ?? get_field( 'grid_text_2' ) ?? '';
$text3 = $args['grid_text_3'] ?? get_field( 'grid_text_3' ) ?? '';

$card_bg   = $args['card_bg']   ?? get_field( 'card_bg' )   ?? null;
$card_text = $args['card_text'] ?? get_field( 'card_text' ) ?? '';
$card_link = $args['card_link'] ?? get_field( 'card_link' ) ?? null;
$link_url  = ! empty( $card_link['url'] )    ? esc_url( $card_link['url'] )    : '';
$link_lbl  = ! empty( $card_link['title'] )  ? esc_html( $card_link['title'] ) : __( 'Fill the Form', 'theme' );
$link_tgt  = ! empty( $card_link['target'] ) ? $card_link['target']             : '_self';

$decor_enabled = $args['decor_bottom_enabled'] ?? get_field( 'decor_bottom_enabled' ) ?? false;
$decor_color   = $args['decor_bottom_color']   ?? get_field( 'decor_bottom_color' )   ?? '#ffffff';

// Grid: 14 cols × 4 rows = 56 cells
// Rectangle positions (1-indexed, col range inclusive):
//   Row 1, cols 1-2  → text1
//   Row 2, cols 13-14 → text2
//   Row 4, cols 2-3  → text3

$COLS = 14;
$ROWS = 4;

// Build a map of which cells are rectangles: key = "row-col" (1-indexed start col)
$rectangles = [
    '1-1'  => [ 'text' => $text1, 'span_end' => 2  ],
    '2-13' => [ 'text' => $text2, 'span_end' => 14 ],
    '4-2'  => [ 'text' => $text3, 'span_end' => 3  ],
];
// Cells to skip (second column of each rectangle)
$skip_cells = [
    '1-2'  => true,
    '2-14' => true,
    '4-3'  => true,
];
?>

<section
    class="cta-section"
    style="
        --cta-pt: <?php echo $pt; ?>px;
        --cta-pb: <?php echo $pb; ?>px;
        --cta-pt-mob: <?php echo $pt_mob; ?>px;
        --cta-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cta-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="cta-heading">
            <span class="cta-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="cta-heading__bottom"><?php echo $title_bottom; ?></span>
        </div>

        <?php /* ── Grid + Card wrapper ───────────────────────────────────── */ ?>
        <div class="cta-grid-wrap">

            <?php /* Grid ------------------------------------------------ */ ?>
            <div class="cta-grid" aria-hidden="true">
                <?php for ( $r = 1; $r <= $ROWS; $r++ ) : ?>
                    <?php for ( $c = 1; $c <= $COLS; $c++ ) : ?>
                        <?php
                        $cell_key  = $r . '-' . $c;
                        $rect_data = $rectangles[ $cell_key ] ?? null;
                        $is_skip   = $skip_cells[ $cell_key ] ?? false;

                        if ( $is_skip ) continue;

                        if ( $rect_data ) :
                            $cell_text = $rect_data['text']
                                ? wp_kses( $rect_data['text'], [ 'br' => [] ] )
                                : '';
                        ?>
                            <div class="cta-cell cta-cell--rect" style="grid-column: <?php echo $c; ?> / span 2; grid-row: <?php echo $r; ?>;">
                                <?php if ( $cell_text ) : ?>
                                    <span class="cta-cell__text"><?php echo $cell_text; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="cta-cell" style="grid-column: <?php echo $c; ?>; grid-row: <?php echo $r; ?>;"></div>
                        <?php endif; ?>
                    <?php endfor; ?>
                <?php endfor; ?>
            </div><!-- .cta-grid -->

            <?php /* CTA Card overlaid on grid ─────────────────────────── */ ?>
            <div class="cta-card">
                <?php if ( $card_bg ) : ?>
                    <div
                        class="cta-card__bg"
                        style="background-image: url('<?php echo esc_url( $card_bg['url'] ); ?>');"
                        aria-hidden="true"
                    ></div>
                <?php endif; ?>
                <div class="cta-card__gradient" aria-hidden="true"></div>

                <?php if ( $card_text ) : ?>
                    <p class="cta-card__text"><?php echo wp_kses( $card_text, [ 'br' => [] ] ); ?></p>
                <?php endif; ?>

                <?php if ( $link_url ) : ?>
                    <a
                        class="cta-card__btn"
                        href="<?php echo $link_url; ?>"
                        target="<?php echo esc_attr( $link_tgt ); ?>"
                        <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                    ><?php echo $link_lbl; ?></a>
                <?php endif; ?>
            </div><!-- .cta-card -->

        </div><!-- .cta-grid-wrap -->

    </div><!-- .cta-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
