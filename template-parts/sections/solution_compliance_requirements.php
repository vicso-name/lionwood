<?php
/**
 * Block: Solution Compliance Requirements
 *
 * ACF block slug : acf/solution-compliance-requirements
 * Two-line heading + equal-width row of red-badge compliance items
 */

defined( 'ABSPATH' ) || exit;

$pt           = absint( get_field( 'padding_top' )        ?: 100 );
$pb           = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob       = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob       = absint( get_field( 'padding_bottom_mob' ) ?: 140 );
$title_line_1 = esc_html( get_field( 'title_line_1' ) ?: __( 'Solution compliance', 'lionwood' ) );
$title_line_2 = esc_html( get_field( 'title_line_2' ) ?: __( 'Requirements', 'lionwood' ) );
$items        = get_field( 'items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
if ( $decor_enabled === null ) $decor_enabled = true;
$decor_color  = get_field( 'decor_bottom_color' ) ?: '#E9E9E9';

if ( empty( $items ) ) return;
?>

<section
    class="scr-section"
    style="
        --scr-pt: <?php echo $pt; ?>px;
        --scr-pb: <?php echo $pb; ?>px;
        --scr-pt-mob: <?php echo $pt_mob; ?>px;
        --scr-pb-mob: <?php echo $pb_mob; ?>px;
        --scr-cols: <?php echo count( $items ); ?>;
    "
>
    <div class="scr-section__container">

        <div class="scr-heading">
            <span class="scr-heading__line-1"><?php echo $title_line_1; ?></span>
            <span class="scr-heading__line-2"><?php echo $title_line_2; ?></span>
        </div>

        <div class="scr-items">
            <?php foreach ( $items as $item ) :
                $text = esc_html( $item['text'] ?? '' );
                if ( ! $text ) continue;
            ?>
                <div class="scr-item">
                    <span class="scr-badge"><?php echo $text; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

    </div><!-- .scr-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
