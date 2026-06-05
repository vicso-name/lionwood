<?php
/**
 * Block: Single Deliver Solutions
 *
 * ACF block slug : acf/single-deliver-solutions
 * Template file  : blocks/single-deliver-solutions/single-deliver-solutions.php
 *
 * Desktop: 14-column grid, 93px cells, 4px gap.
 * Content blocks (3×3 cells = 287px) placed in a fixed chess pattern:
 *
 * Pattern repeats every 2 items (one group of 3 rows):
 *   Group row A (items 1,2): cols 2-4 and 7-9
 *   Group row B (items 3,4): cols 4-6 and 9-11
 *   Group row C (items 5,6): cols 2-4 and 7-9  (same as A)
 *   etc.
 *
 * Each group occupies 3 grid rows. Empty cells fill the rest.
 *
 * Mobile: Swiper slider with numeric counter (same as Single Service Definition).
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_top     = get_field( 'title_top' )    ?: __( 'How We', 'theme' );
$title_bottom  = get_field( 'title_bottom' ) ?: '';
$items         = get_field( 'items' )        ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$total = count( $items );

if ( empty( $items ) ) return;

// ── Chess pattern: col start per item index (0-based) ─────────────────────────
// Pattern repeats every 4 items (2 rows of 2):
//   index 0 → col 2  (group A left)
//   index 1 → col 7  (group A right)
//   index 2 → col 4  (group B left — shifted +2)
//   index 3 → col 9  (group B right — shifted +2)
//   index 4 → col 2  (group C = same as A)
//   index 5 → col 7  (group C right)
//   etc.

$col_pattern = [ 2, 8, 5, 11, 2, 8 ]; // 1-indexed grid columns — chess pattern

// Calculate number of grid rows needed (3 rows per pair of items)
$pairs     = ceil( $total / 2 );
$grid_rows = $pairs * 3;

// Build a map: [row][col] = item_index (1-indexed for grid)
// Each content block spans: col → col+2, row → row+2 (3×3)
$item_map = []; // 'row-col' => item_index

foreach ( $items as $i => $item ) {
    $pair_index  = intdiv( $i, 2 );       // which pair (0,1,2…)
    $in_pair     = $i % 2;                // 0=left, 1=right
    $pattern_idx = ( $pair_index % 3 ) * 2 + $in_pair; // 0–5 cycling over 3 row groups

    $col_start = $col_pattern[ $pattern_idx ];
    $row_start = $pair_index * 3 + 1;     // 1-indexed

    $item_map[ $row_start . '-' . $col_start ] = $i;
}

// Cells to skip (occupied by content block span > 1×1)
$skip_cells = [];
foreach ( $item_map as $key => $idx ) {
    [ $rs, $cs ] = explode( '-', $key );
    $rs = (int) $rs; $cs = (int) $cs;
    for ( $dr = 0; $dr < 3; $dr++ ) {
        for ( $dc = 0; $dc < 3; $dc++ ) {
            if ( $dr === 0 && $dc === 0 ) continue; // origin rendered as content block
            $skip_cells[ ( $rs + $dr ) . '-' . ( $cs + $dc ) ] = true;
        }
    }
}
?>

<section
    class="sds-section"
    style="
        --sds-pt: <?php echo $pt; ?>px;
        --sds-pb: <?php echo $pb; ?>px;
        --sds-pt-mob: <?php echo $pt_mob; ?>px;
        --sds-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sds-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="sds-heading">
            <span class="sds-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <?php if ( $title_bottom ) : ?>
                <span class="sds-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
            <?php endif; ?>
        </div>

        <?php /* ── Desktop grid ─────────────────────────────────────────── */ ?>
        <div class="sds-grid" style="--sds-grid-rows: <?php echo $grid_rows; ?>;">
            <?php for ( $r = 1; $r <= $grid_rows; $r++ ) : ?>
                <?php for ( $c = 1; $c <= 14; $c++ ) : ?>
                    <?php
                    $cell_key   = $r . '-' . $c;
                    $item_index = $item_map[ $cell_key ] ?? null;
                    $is_skip    = $skip_cells[ $cell_key ] ?? false;

                    if ( $is_skip ) continue;
                    ?>

                    <?php if ( $item_index !== null ) :
                        $item  = $items[ $item_index ];
                        $num   = str_pad( $item_index + 1, 2, '0', STR_PAD_LEFT );
                        $title = esc_html( $item['title'] ?? '' );
                        $desc  = $item['description'] ?? '';
                        $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    ?>
                        <div
                            class="sds-cell sds-cell--content"
                            style="grid-column: <?php echo $c; ?> / span 3; grid-row: <?php echo $r; ?> / span 3;"
                        >
                            <div class="sds-card">
                                <span class="sds-card__num"><?php echo esc_html( $num ); ?></span>
                                <?php if ( $title ) : ?>
                                    <h3 class="sds-card__title"><?php echo $title; ?></h3>
                                <?php endif; ?>
                                <?php if ( $desc_out ) : ?>
                                    <p class="sds-card__desc"><?php echo $desc_out; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php else : ?>
                        <div
                            class="sds-cell"
                            style="grid-column: <?php echo $c; ?>; grid-row: <?php echo $r; ?>;"
                        ></div>
                    <?php endif; ?>

                <?php endfor; ?>
            <?php endfor; ?>
        </div><!-- .sds-grid -->

        <?php /* ── Mobile Swiper ─────────────────────────────────────────── */ ?>
        <div class="sds-swiper-wrap">
            <div class="swiper sds-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $items as $i => $item ) :
                        $num  = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
                        $title = esc_html( $item['title'] ?? '' );
                        $desc  = $item['description'] ?? '';
                        $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    ?>
                        <div class="swiper-slide sds-slide">
                            <div class="sds-card">
                                <span class="sds-card__num"><?php echo esc_html( $num ); ?></span>
                                <?php if ( $title ) : ?>
                                    <h3 class="sds-card__title"><?php echo $title; ?></h3>
                                <?php endif; ?>
                                <?php if ( $desc_out ) : ?>
                                    <p class="sds-card__desc"><?php echo $desc_out; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="sds-counter" aria-live="polite">
                <span class="sds-counter__current">01</span>
                <span class="sds-counter__sep">/</span>
                <span class="sds-counter__total"><?php echo str_pad( $total, 2, '0', STR_PAD_LEFT ); ?></span>
            </div>
        </div>

    </div><!-- .sds-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
