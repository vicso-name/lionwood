<?php
/**
 * Block: Publications About
 *
 * ACF block slug: acf/publications-about
 *
 * Fields:
 *   padding_top / padding_bottom / padding_top_mob / padding_bottom_mob (number)
 *   title_top          (text)   — gray heading line, 8% left offset
 *   title_bottom       (text)   — black heading line
 *   resources          (repeater) — image, url, target_blank, content_type_en/uk, title_en/uk
 *   decor_bottom_enabled (true_false, default: 1)
 *   decor_bottom_color   (color_picker, default: #F7F7F7)
 */

defined( 'ABSPATH' ) || exit;

// ── Padding ───────────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )         ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )      ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )     ?: 70  );
$pb_mob = absint( get_field( 'padding_bottom_mob' )  ?: 140 );

// ── Heading ───────────────────────────────────────────────────────────────────
$title_top    = esc_html( get_field( 'title_top' )    ?: '' );
$title_bottom = esc_html( get_field( 'title_bottom' ) ?: '' );

// ── Resources ─────────────────────────────────────────────────────────────────
$resources = get_field( 'resources' ) ?: [];
$total     = count( $resources );

// ── Arrow SVG ─────────────────────────────────────────────────────────────────
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true"><path d="M5.92753 16.4891L16.4935 5.92318M8.56902 5.92318L16.4935 5.92318L16.4935 13.8477" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$section_style = sprintf(
    '--pab-pt:%dpx; --pab-pb:%dpx; --pab-pt-mob:%dpx; --pab-pb-mob:%dpx;',
    $pt, $pb, $pt_mob, $pb_mob
);
?>

<section class="pab-section" style="<?php echo esc_attr( $section_style ); ?>">
    <div class="pab-section__container">

        <?php /* ── Heading ───────────────────────────────────────────────── */ ?>
        <?php if ( $title_top || $title_bottom ) : ?>
        <div class="pab-heading">
            <?php if ( $title_top ) : ?>
                <div class="pab-heading__top"><?php echo $title_top; ?></div>
            <?php endif; ?>
            <?php if ( $title_bottom ) : ?>
                <div class="pab-heading__bottom"><?php echo $title_bottom; ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php /* ── List ──────────────────────────────────────────────────── */ ?>
        <?php if ( ! empty( $resources ) ) : ?>
        <div class="pab-list" id="pab-list">
            <div class="pab-divider" aria-hidden="true"></div>
            <?php foreach ( $resources as $idx => $item ) :
                $img     = $item['image'] ?? [];
                $img_url = ! empty( $img['url'] ) ? esc_url( $img['url'] ) : '';
                $img_alt = ! empty( $img['alt'] ) ? esc_attr( $img['alt'] ) : '';
                $link    = $item['link'] ?? [];
                $url     = ! empty( $link['url'] )    ? esc_url( $link['url'] )   : '#';
                $target  = ! empty( $link['target'] ) ? $link['target']            : '_self';
                $rel     = '_blank' === $target       ? 'noopener noreferrer'      : '';
                $c_type  = esc_html( $item['content_type'] ?? '' );
                $title_r = esc_html( $link['title'] ?? '' );
                $hidden  = $idx >= 3 ? ' pab-item--hidden' : '';
            ?>
                <div class="pab-item<?php echo $hidden; ?>">
                    <a class="pab-link" href="<?php echo $url; ?>" target="<?php echo $target; ?>"<?php echo $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?> aria-label="<?php echo $title_r; ?>">

                        <div class="pab-thumb">
                            <?php if ( $img_url ) : ?>
                                <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                            <?php endif; ?>
                        </div>

                        <div class="pab-content">
                            <?php if ( $c_type ) : ?>
                                <span class="pab-type"><?php echo $c_type; ?></span>
                            <?php endif; ?>
                            <?php if ( $title_r ) : ?>
                                <p class="pab-title"><?php echo $title_r; ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="pab-icon"><?php echo $arrow_svg; ?></div>

                    </a>
                    <div class="pab-divider" aria-hidden="true"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ( $total > 3 ) : ?>
            <button class="pab-load-more" id="pab-load-more" type="button">
                <?php printf( esc_html__( 'Load more (+%d)', 'lionwood' ), $total - 3 ); ?>
            </button>
        <?php endif; ?>

        <?php endif; ?>

    </div>
    <?php get_template_part( 'template-parts/partials/decor-bottom' ); ?>
</section>
