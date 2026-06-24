<?php
/**
 * Block: Single Service Explore
 *
 * ACF block slug : acf/single-service-explore
 * Template file  : blocks/single-service-explore/single-service-explore.php
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_top     = get_field( 'title_top' )    ?: __( 'Explore', 'lionwood' );
$title_bottom  = get_field( 'title_bottom' ) ?: '';
$initial_count = absint( get_field( 'initial_count' ) ?: 3 );
$items         = get_field( 'items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$total    = count( $items );
$has_more = $total > $initial_count;
$hidden   = $has_more ? $total - $initial_count : 0;

// Arrow SVG
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true">
    <path d="M5.92557 16.4891L16.4915 5.92318M8.56706 5.92318L16.4915 5.92318L16.4915 13.8477" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    class="sse-section"
    style="
        --sse-pt: <?php echo $pt; ?>px;
        --sse-pb: <?php echo $pb; ?>px;
        --sse-pt-mob: <?php echo $pt_mob; ?>px;
        --sse-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sse-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="sse-heading">
            <span class="sse-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <?php if ( $title_bottom ) : ?>
                <span class="sse-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
            <?php endif; ?>
        </div>

        <?php /* ── Items list ───────────────────────────────────────────── */ ?>
        <div
            class="sse-list"
            data-initial="<?php echo esc_attr( $initial_count ); ?>"
        >
            <?php foreach ( $items as $i => $item ) :
                $num     = '/ ' . str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
                $title   = esc_html( $item['title']       ?? '' );
                $desc    = $item['description'] ?? '';
                $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                $link     = $item['link'] ?? null;
                $link_url = ! empty( $link['url'] )    ? esc_url( $link['url'] )    : '';
                $link_tgt = ! empty( $link['target'] ) ? $link['target']             : '_self';

                // Hidden class for items beyond initial count
                $hidden_class = $i >= $initial_count ? ' sse-item--hidden' : '';
            ?>
                <div class="sse-item<?php echo $hidden_class; ?>" data-index="<?php echo $i; ?>">
                    <span class="sse-item__num"><?php echo esc_html( $num ); ?></span>
                    <span class="sse-item__title"><?php echo $title; ?></span>
                    <?php if ( $desc_out ) : ?>
                        <p class="sse-item__desc"><?php echo $desc_out; ?></p>
                    <?php endif; ?>
                    <?php if ( $link_url ) : ?>
                        <a
                            class="sse-item__arrow"
                            href="<?php echo $link_url; ?>"
                            target="<?php echo esc_attr( $link_tgt ); ?>"
                            <?php echo '_blank' === $link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                            aria-label="<?php echo esc_attr( $title ); ?>"
                        ><?php echo $arrow_svg; ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div><!-- .sse-list -->

        <?php /* ── Load More button ─────────────────────────────────────── */ ?>
        <?php if ( $has_more ) : ?>
            <div class="sse-loadmore-wrap">
                <button
                    class="sse-loadmore-btn"
                    data-loadmore
                    aria-expanded="false"
                >
                    <?php
                    printf(
                        /* translators: %d: number of hidden items */
                        esc_html__( 'Load More (+%d)', 'lionwood' ),
                        $hidden
                    );
                    ?>
                </button>
            </div>
        <?php endif; ?>

    </div><!-- .sse-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
