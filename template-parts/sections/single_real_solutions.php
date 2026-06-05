<?php
/**
 * Block: Single Real Solutions
 *
 * ACF block slug : acf/single-real-solutions
 *
 * With image: alternating left/right (chess). Image 670×600 + content block.
 * No image: consecutive no-image rows are paired side by side with vertical divider.
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 70 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 70 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 50 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 50 );
$title_top     = get_field( 'title_top' )    ?: '';
$title_bottom  = get_field( 'title_bottom' ) ?: '';
$rows          = get_field( 'rows' )         ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

if ( empty( $rows ) ) return;

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="6" viewBox="0 0 8 6" fill="none" aria-hidden="true">
    <path d="M7.79087 0.136634C7.87835 0.224147 7.9275 0.342824 7.9275 0.466567C7.9275 0.59031 7.87835 0.708987 7.79087 0.7965L3.1242 5.46317C3.03669 5.55065 2.91801 5.5998 2.79427 5.5998C2.67052 5.5998 2.55185 5.55065 2.46433 5.46317L0.130999 3.12983C0.045992 3.04182 -0.00104562 2.92394 1.76412e-05 2.80158C0.0010809 2.67922 0.0501599 2.56218 0.136684 2.47565C0.223207 2.38913 0.340253 2.34005 0.462612 2.33899C0.58497 2.33792 0.702851 2.38496 0.790866 2.46997L2.79427 4.47337L7.131 0.136634C7.21851 0.0491473 7.33719 0 7.46093 0C7.58468 0 7.70335 0.0491473 7.79087 0.136634Z" fill="#F7F7F7"/>
</svg>';

// Helper: render content block (badge + desc + checklist)
function smplfy_srs_content( array $row, string $check_svg ): void {
    $badge = esc_html( $row['badge'] ?? '' );
    $desc  = $row['description'] ?? '';
    $items = $row['items']       ?? [];
    ?>
    <div class="srs-content">
        <?php if ( $badge ) : ?>
            <span class="srs-badge"><?php echo $badge; ?></span>
        <?php endif; ?>
        <?php if ( $desc ) : ?>
            <div class="srs-desc"><?php echo wp_kses_post( $desc ); ?></div>
        <?php endif; ?>
        <?php if ( ! empty( $items ) ) : ?>
            <ul class="srs-checklist">
                <?php foreach ( $items as $item ) :
                    $text = esc_html( $item['text'] ?? '' );
                    if ( ! $text ) continue;
                ?>
                    <li class="srs-checklist__item">
                        <span class="srs-checklist__icon" aria-hidden="true"><?php echo $check_svg; ?></span>
                        <span class="srs-checklist__text"><?php echo $text; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}

// Track which rows have been rendered (for no-image pairing)
$rendered = [];
$img_row_count = 0; // separate counter for chess pattern on image rows
?>

<section
    class="srs-section"
    style="
        --srs-pt: <?php echo $pt; ?>px;
        --srs-pb: <?php echo $pb; ?>px;
        --srs-pt-mob: <?php echo $pt_mob; ?>px;
        --srs-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="srs-section__container">

        <?php if ( $title_top || $title_bottom ) : ?>
            <div class="srs-heading">
                <?php if ( $title_top ) : ?>
                    <span class="srs-heading__top"><?php echo esc_html( $title_top ); ?></span>
                <?php endif; ?>
                <?php if ( $title_bottom ) : ?>
                    <span class="srs-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="srs-rows">
            <?php foreach ( $rows as $i => $row ) :
                if ( in_array( $i, $rendered, true ) ) continue;

                $has_img = ! empty( $row['image'] );

                if ( $has_img ) :
                    // Chess pattern based on image-row count
                    $img_right = ( $img_row_count % 2 !== 0 );
                    $img_row_count++;
                    $rendered[] = $i;
                    $image = $row['image'];
            ?>
                    <div class="srs-row srs-row--has-image<?php echo $img_right ? ' srs-row--img-right' : ''; ?>">
                        <div class="srs-col srs-col--image">
                            <div class="srs-image-wrap">
                                <img
                                    src="<?php echo esc_url( $image['url'] ); ?>"
                                    alt="<?php echo esc_attr( $image['alt'] ?: '' ); ?>"
                                    loading="lazy"
                                >
                            </div>
                        </div>
                        <div class="srs-col srs-col--content">
                            <?php smplfy_srs_content( $row, $check_svg ); ?>
                        </div>
                    </div>

                <?php else :
                    // No image — look for next no-image row to pair with
                    $next_index = null;
                    for ( $j = $i + 1; $j < count( $rows ); $j++ ) {
                        if ( ! in_array( $j, $rendered, true ) && empty( $rows[$j]['image'] ) ) {
                            $next_index = $j;
                            break;
                        }
                    }
                    $rendered[] = $i;
                    if ( $next_index !== null ) $rendered[] = $next_index;
                ?>
                    <div class="srs-row srs-row--no-image">
                        <div class="srs-col srs-col--text-only">
                            <?php smplfy_srs_content( $row, $check_svg ); ?>
                        </div>
                        <div class="srs-col-divider" aria-hidden="true"></div>
                        <div class="srs-col srs-col--text-only">
                            <?php if ( $next_index !== null ) : ?>
                                <?php smplfy_srs_content( $rows[ $next_index ], $check_svg ); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    </div><!-- .srs-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
