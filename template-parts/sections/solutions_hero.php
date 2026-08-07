<?php
/**
 * Block: Solutions Hero Section
 * Slug: acf/solutions-hero
 *
 * Based on products_hero.php — same layout, count uses 'solution' CPT.
 * Left col: H1 title + optional solution count badge + description
 * Right col: image
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70  );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70  );

$title_line1 = get_field( 'title_line1' ) ?: 'Our';
$title_line2 = get_field( 'title_line2' ) ?: 'Solutions';
$description = get_field( 'description' );
$desc_out    = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';
$image       = get_field( 'image' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$solution_count = wp_count_posts( 'solution' )->publish ?? 0;
?>

<section
    class="slh-section"
    style="
        --slh-pt: <?php echo $pt; ?>px;
        --slh-pb: <?php echo $pb; ?>px;
        --slh-pt-mob: <?php echo $pt_mob; ?>px;
        --slh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="slh-section__container">
        <div class="slh-row">

            <?php /* ── Left column ── */ ?>
            <div class="slh-col slh-col--left">

                <h1 class="slh-title slh-anim" data-delay="0">
                    <span class="slh-title__part1"><?php echo esc_html( $title_line1 ); ?></span>
                    <span class="slh-title__part2"><?php echo esc_html( $title_line2 ); ?>
                        <?php if ( $solution_count ) : ?>
                            <sup class="slh-title__count">(<?php echo $solution_count; ?>)</sup>
                        <?php endif; ?>
                    </span>
                </h1>

                <?php if ( $desc_out ) : ?>
                    <div class="slh-bottom slh-anim" data-delay="160">
                        <p class="slh-description"><?php echo $desc_out; ?></p>
                    </div>
                <?php endif; ?>

            </div>

            <?php /* ── Right column: image ── */ ?>
            <?php if ( $image ) : ?>
                <div class="slh-col slh-col--right slh-anim slh-anim--image" data-delay="80">
                    <div
                        class="slh-image"
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
