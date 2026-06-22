<?php
/**
 * Block: Industry Associations
 *
 * ACF block slug: acf/industry-associations
 *
 * Fields:
 *   padding_top / padding_bottom / padding_top_mob / padding_bottom_mob (number)
 *   title_top          (text)   — gray heading line, 8% left offset
 *   title_bottom       (text)   — black heading line
 *   items              (repeater) — image, link (ACF link), description
 *   decor_bottom_enabled (true_false, default: 1)
 *   decor_bottom_color   (color_picker, default: #111319)
 */

defined( 'ABSPATH' ) || exit;

// ── Padding ───────────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70  );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

// ── Heading ───────────────────────────────────────────────────────────────────
$title_top    = esc_html( get_field( 'title_top' )    ?: '' );
$title_bottom = esc_html( get_field( 'title_bottom' ) ?: '' );

// ── Items ─────────────────────────────────────────────────────────────────────
$items = get_field( 'items' ) ?: [];
$total = count( $items );

// ── Arrow SVG ─────────────────────────────────────────────────────────────────
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true"><path d="M5.92753 16.4891L16.4935 5.92318M8.56902 5.92318L16.4935 5.92318L16.4935 13.8477" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$section_style = sprintf(
    '--ina-pt:%dpx; --ina-pb:%dpx; --ina-pt-mob:%dpx; --ina-pb-mob:%dpx;',
    $pt, $pb, $pt_mob, $pb_mob
);
?>

<section class="ina-section" style="<?php echo esc_attr( $section_style ); ?>">
    <div class="ina-section__container">

        <?php /* ── Heading ───────────────────────────────────────────────── */ ?>
        <?php if ( $title_top || $title_bottom ) : ?>
        <div class="ina-heading">
            <?php if ( $title_top ) : ?>
                <div class="ina-heading__top"><?php echo $title_top; ?></div>
            <?php endif; ?>
            <?php if ( $title_bottom ) : ?>
                <div class="ina-heading__bottom"><?php echo $title_bottom; ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php /* ── List ──────────────────────────────────────────────────── */ ?>
        <?php if ( ! empty( $items ) ) : ?>
        <div class="ina-list" id="ina-list">
            <div class="ina-divider" aria-hidden="true"></div>
            <?php foreach ( $items as $idx => $item ) :
                $img     = $item['image'] ?? [];
                $img_url = ! empty( $img['url'] ) ? esc_url( $img['url'] ) : '';
                $img_alt = ! empty( $img['alt'] ) ? esc_attr( $img['alt'] ) : '';
                $link    = $item['link'] ?? [];
                $url     = ! empty( $link['url'] )    ? esc_url( $link['url'] )   : '#';
                $target  = ! empty( $link['target'] ) ? $link['target']            : '_self';
                $rel     = '_blank' === $target       ? 'noopener noreferrer'      : '';
                $title_r = esc_html( $link['title'] ?? '' );
                $desc    = esc_html( $item['description'] ?? '' );
                $num     = sprintf( '/ %02d', $idx + 1 );
                $hidden  = $idx >= 3 ? ' ina-item--hidden' : '';
            ?>
                <div class="ina-item<?php echo $hidden; ?>">
                    <a class="ina-link" href="<?php echo $url; ?>" target="<?php echo $target; ?>"<?php echo $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?> aria-label="<?php echo $title_r; ?>">

                        <div class="ina-link__meta">
                            <span class="ina-num" aria-hidden="true"><?php echo $num; ?></span>
                            <div class="ina-thumb">
                            <?php if ( $img_url ) : ?>
                                <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                            <?php endif; ?>
                            </div>
                        </div>

                        <div class="ina-content">
                            <?php if ( $title_r ) : ?>
                                <p class="ina-title"><?php echo $title_r; ?></p>
                            <?php endif; ?>
                            <?php if ( $desc ) : ?>
                                <p class="ina-desc"><?php echo $desc; ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="ina-icon"><?php echo $arrow_svg; ?></div>

                    </a>
                    <div class="ina-divider" aria-hidden="true"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ( $total > 3 ) : ?>
            <button class="ina-load-more" id="ina-load-more" type="button">
                <?php printf( esc_html__( 'Load more (+%d)', 'lionwood' ), $total - 3 ); ?>
            </button>
        <?php endif; ?>

        <?php endif; ?>

    </div>
    <?php get_template_part( 'template-parts/partials/decor-bottom' ); ?>
</section>
