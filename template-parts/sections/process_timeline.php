<?php
/**
 * Block: Process Timeline Section
 *
 * ACF block slug : acf/process-timeline
 *
 * Snake layout: milestones split into rows of N items.
 * Even rows go left→right, odd rows go right→left.
 * SVG path drawn by JS after render (uses getBoundingClientRect).
 * Animation: GSAP + ScrollTrigger (loaded via CDN or bundled).
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_top     = esc_html( get_field( 'title_top' )    ?: __( 'Process', 'theme' ) );
$title_bottom  = esc_html( get_field( 'title_bottom' ) ?: __( '& Timeline', 'theme' ) );
$items_per_row = absint( get_field( 'items_per_row' ) ?: 5 );
$milestones    = get_field( 'milestones' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

if ( empty( $milestones ) ) return;

// Split milestones into rows; visual order always L→R (JS reverses path order for odd rows)
$rows = array_chunk( $milestones, $items_per_row );
$uid  = 'pt-' . uniqid();
?>

<section
    class="pt-section"
    id="<?php echo esc_attr( $uid ); ?>"
    style="
        --pt-pt: <?php echo $pt; ?>px;
        --pt-pb: <?php echo $pb; ?>px;
        --pt-pt-mob: <?php echo $pt_mob; ?>px;
        --pt-pb-mob: <?php echo $pb_mob; ?>px;
    "
    data-items-per-row="<?php echo esc_attr( $items_per_row ); ?>"
>
    <div class="pt-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="pt-heading">
            <span class="pt-heading__top"><?php echo $title_top; ?></span>
            <span class="pt-heading__bottom"><?php echo $title_bottom; ?></span>
        </div>

        <?php /* ── Timeline wrapper ─────────────────────────────────────── */ ?>
        <div class="pt-timeline" data-pt-timeline>

            <?php /* SVG overlay — JS draws the path here */ ?>
            <svg class="pt-svg" aria-hidden="true">
                <?php /* Base line (always visible, low opacity) */ ?>
                <path class="pt-path--base" fill="none" stroke="#ffffff" stroke-width="1" opacity="0.2"/>
                <?php /* Progress line — animated */ ?>
                <path class="pt-path--progress" fill="none" stroke="#ffffff" stroke-width="1" opacity="1"/>
            </svg>

            <?php /* Milestone rows */ ?>
            <?php foreach ( $rows as $row_index => $row ) :
                $is_reversed = ( $row_index % 2 !== 0 );
                $display_row = $row;
            ?>
                <div
                    class="pt-row<?php echo $is_reversed ? ' pt-row--reversed' : ''; ?>"
                    data-row="<?php echo esc_attr( $row_index ); ?>"
                >
                    <?php foreach ( $display_row as $item ) :
                        $date = esc_html( $item['date'] ?? '' );
                        $desc = $item['description'] ?? '';
                        $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    ?>
                        <div class="pt-milestone" data-pt-milestone>
                            <div class="pt-milestone__dot" aria-hidden="true"></div>
                            <div class="pt-milestone__pill"><?php echo $date; ?></div>
                            <?php if ( $desc_out ) : ?>
                                <p class="pt-milestone__desc"><?php echo $desc_out; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

        </div><!-- .pt-timeline -->

    </div><!-- .pt-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
