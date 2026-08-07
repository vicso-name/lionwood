<?php
/**
 * Block: Two Column Images
 *
 * ACF block slug : acf/two-column-images
 * One image left only → full width (1360×700 ratio)
 * Two images → 50/50 split (670×700 ratio each)
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 80 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 50 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 50 );
$image_left    = get_field( 'image_left' );
$image_right   = get_field( 'image_right' );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$has_two = $image_left && $image_right;

if ( ! $image_left && ! $image_right ) return;
?>

<section
    class="tci-section"
    style="
        --tci-pt: <?php echo $pt; ?>px;
        --tci-pb: <?php echo $pb; ?>px;
        --tci-pt-mob: <?php echo $pt_mob; ?>px;
        --tci-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="tci-section__container">
        <div class="tci-row<?php echo $has_two ? ' tci-row--two' : ' tci-row--one'; ?>">

            <?php if ( $image_left ) : ?>
                <div
                    class="tci-img"
                    style="background-image: url('<?php echo esc_url( $image_left['url'] ); ?>');"
                    role="img"
                    aria-label="<?php echo esc_attr( $image_left['alt'] ?: '' ); ?>"
                ></div>
            <?php endif; ?>

            <?php if ( $image_right ) : ?>
                <div
                    class="tci-img"
                    style="background-image: url('<?php echo esc_url( $image_right['url'] ); ?>');"
                    role="img"
                    aria-label="<?php echo esc_attr( $image_right['alt'] ?: '' ); ?>"
                ></div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
