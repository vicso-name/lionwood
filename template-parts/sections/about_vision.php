<?php
/**
 * Block: About Vision
 * Slug: acf/about-vision
 *
 * Dark section with marquee + two-column layout (text left, image right).
 */

defined( 'ABSPATH' ) || exit;

$pt        = absint( get_field('padding_top')        ?: 100 );
$pb        = absint( get_field('padding_bottom')     ?: 100 );
$pt_mob    = absint( get_field('padding_top_mob')    ?: 70 );
$pb_mob    = absint( get_field('padding_bottom_mob') ?: 70 );

$marquee_text  = get_field('marquee_text')  ?: 'Our Vision';
$badge_text    = get_field('badge_text');
$description   = get_field('description');
$image         = get_field('image');

$decor_enabled = get_field('decor_enabled');
$decor_color   = get_field('decor_color') ?: '#F7F7F7';
?>

<section
    class="av-section"
    style="
        --av-pt: <?php echo $pt; ?>px;
        --av-pb: <?php echo $pb; ?>px;
        --av-pt-mob: <?php echo $pt_mob; ?>px;
        --av-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Marquee ── */ ?>
    <div class="av-marquee">
        <div class="av-marquee__track">
            <?php for ( $i = 0; $i < 10; $i++ ) : ?>
                <span class="av-marquee__item"><?php echo esc_html( $marquee_text ); ?></span>
            <?php endfor; ?>
        </div>
    </div>

    <div class="av-section__container">

        <?php /* ── Top divider ── */ ?>
        <div class="av-divider" aria-hidden="true"></div>

        <?php /* ── Body row ── */ ?>
        <div class="av-body">

            <?php /* ── Text column ── */ ?>
            <div class="av-col--text">

                <?php if ( $badge_text ) : ?>
                    <span class="av-badge"><?php echo esc_html( $badge_text ); ?></span>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <p class="av-description"><?php echo nl2br( esc_html( $description ) ); ?></p>
                <?php endif; ?>

            </div>

            <?php /* ── Vertical divider ── */ ?>
            <div class="av-body__divider" aria-hidden="true"></div>

            <?php /* ── Image column ── */ ?>
            <div class="av-col--image">
                <?php if ( $image ) : ?>
                    <div
                        class="av-image"
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: '' ); ?>"
                    ></div>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
