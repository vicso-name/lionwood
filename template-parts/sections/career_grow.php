<?php
/**
 * Block: Career Grow With
 * Slug: acf/career-grow
 *
 * Identical structure to about-vision (av-) but:
 *  - marquee replaced with two-line heading
 *  - badge removed
 *  - description + checklist items repeater
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field('padding_top')        ?: 100 );
$pb     = absint( get_field('padding_bottom')     ?: 100 );
$pt_mob = absint( get_field('padding_top_mob')    ?: 70 );
$pb_mob = absint( get_field('padding_bottom_mob') ?: 70 );

$title_top    = get_field('title_top')    ?: 'Grow with';
$title_bottom = get_field('title_bottom') ?: 'Lionwood Team Today';
$description  = get_field('description');
$items        = get_field('items') ?: [];
$image        = get_field('image');

$decor_enabled = get_field('decor_bottom_enabled');
$decor_color   = get_field('decor_bottom_color') ?: '#F7F7F7';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
    <path d="M10.8702 3.30215C10.9686 3.4006 11.0239 3.53411 11.0239 3.67333C11.0239 3.81254 10.9686 3.94605 10.8702 4.0445L5.62019 9.2945C5.52174 9.39292 5.38823 9.44821 5.24902 9.44821C5.10981 9.44821 4.97629 9.39292 4.87784 9.2945L2.25284 6.6695C2.15721 6.57048 2.10429 6.43787 2.10549 6.30021C2.10668 6.16256 2.1619 6.03088 2.25924 5.93355C2.35658 5.83621 2.48825 5.78099 2.62591 5.7798C2.76356 5.7786 2.89618 5.83152 2.99519 5.92715L5.24902 8.18098L10.1278 3.30215C10.2263 3.20373 10.3598 3.14844 10.499 3.14844C10.6382 3.14844 10.7717 3.20373 10.8702 3.30215Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="cgw-section"
    style="
        --cgw-pt: <?php echo $pt; ?>px;
        --cgw-pb: <?php echo $pb; ?>px;
        --cgw-pt-mob: <?php echo $pt_mob; ?>px;
        --cgw-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Two-line heading (replaces marquee) ── */ ?>
    <div class="cgw-section__container">
        <div class="cgw-heading">
            <span class="cgw-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="cgw-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>
    </div>

    <div class="cgw-section__container">

        <?php /* ── Top divider ── */ ?>
        <div class="cgw-divider" aria-hidden="true"></div>

        <?php /* ── Body row ── */ ?>
        <div class="cgw-body">

            <?php /* ── Text column ── */ ?>
            <div class="cgw-col--text">

                <?php if ( $description ) : ?>
                    <p class="cgw-description"><?php echo nl2br( esc_html( $description ) ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $items ) ) : ?>
                    <ul class="cgw-list">
                        <?php foreach ( $items as $item ) :
                            $text = esc_html( $item['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="cgw-list__item">
                                <span class="cgw-list__icon"><?php echo $check_svg; ?></span>
                                <span class="cgw-list__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <?php /* ── Vertical divider ── */ ?>
            <div class="cgw-body__divider" aria-hidden="true"></div>

            <?php /* ── Image column ── */ ?>
            <div class="cgw-col--image">
                <?php if ( $image ) : ?>
                    <div
                        class="cgw-image"
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title_bottom ); ?>"
                    ></div>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
