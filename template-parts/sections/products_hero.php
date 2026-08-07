<?php
/**
 * Block: Products Hero Section
 * Slug: acf/products-hero
 *
 * Based on career_hero.php — same layout, no socials.
 * Left col: H1 title + optional product count badge + description
 * Right col: image
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70  );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70  );

$title_line1 = get_field( 'title_line1' ) ?: 'Our';
$title_line2 = get_field( 'title_line2' ) ?: 'Products';
$show_count  = get_field( 'show_count' );
$description = get_field( 'description' );
$desc_out    = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';
$image       = get_field( 'image' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$product_count = 0;
if ( $show_count ) {
    $product_count = wp_count_posts( 'product' )->publish ?? 0;
}
?>

<section
    class="prh-section"
    style="
        --prh-pt: <?php echo $pt; ?>px;
        --prh-pb: <?php echo $pb; ?>px;
        --prh-pt-mob: <?php echo $pt_mob; ?>px;
        --prh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="prh-section__container">
        <div class="prh-row">

            <?php /* ── Left column ── */ ?>
            <div class="prh-col prh-col--left">

                <h1 class="prh-title prh-anim" data-delay="0">
                    <span class="prh-title__part1"><?php echo esc_html( $title_line1 ); ?></span>
                    <span class="prh-title__part2"><?php echo esc_html( $title_line2 ); ?>
                        <?php if ( $show_count && $product_count ) : ?>
                            <sup class="prh-title__count">(<?php echo $product_count; ?>)</sup>
                        <?php endif; ?>
                    </span>
                </h1>

                <?php if ( $desc_out ) : ?>
                    <div class="prh-bottom prh-anim" data-delay="160">
                        <p class="prh-description"><?php echo $desc_out; ?></p>
                    </div>
                <?php endif; ?>

            </div>

            <?php /* ── Right column: image ── */ ?>
            <?php if ( $image ) : ?>
                <div class="prh-col prh-col--right prh-anim prh-anim--image" data-delay="80">
                    <div
                        class="prh-image"
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title_line1 . ' ' . $title_line2 ); ?>"
                    ></div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
