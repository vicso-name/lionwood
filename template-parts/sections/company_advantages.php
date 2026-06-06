<?php
/**
 * Block: Company Advantages
 *
 * ACF block slug : acf/company-advantages
 *
 * Layout:
 *   - Large title (1195px): first word indented right, rest flows normally
 *   - Two equal columns: left = badge + checklist + rating; right = image
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_raw     = get_field( 'title' ) ?: '';
$badge_label   = get_field( 'badge_label' ) ?: __( 'Why choose us', 'theme' );
$items         = get_field( 'items' ) ?: [];
$rating_value  = get_field( 'rating_value' ) ?: '4.9';
$rating_stars  = absint( get_field( 'rating_stars' ) ?: 5 );
$rating_link   = get_field( 'rating_link' );
$image         = get_field( 'image' );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$link_url   = ! empty( $rating_link['url'] )    ? esc_url( $rating_link['url'] )    : '';
$link_label = ! empty( $rating_link['title'] )  ? esc_html( $rating_link['title'] ) : __( 'Rating on Clutch', 'theme' );
$link_tgt   = ! empty( $rating_link['target'] ) ? $rating_link['target']             : '_blank';

// Split title: first word gets indent class, rest flows inline
$title_words = explode( ' ', trim( $title_raw ), 2 );
$first_word  = esc_html( $title_words[0] ?? '' );
$rest_words  = esc_html( $title_words[1] ?? '' );

// Check icon SVG (18×18 circle with checkmark)
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
    <path d="M10.8702 3.30215C10.9686 3.4006 11.0239 3.53411 11.0239 3.67333C11.0239 3.81254 10.9686 3.94605 10.8702 4.0445L5.62019 9.2945C5.52174 9.39292 5.38823 9.44821 5.24902 9.44821C5.10981 9.44821 4.97629 9.39292 4.87784 9.2945L2.25284 6.6695C2.15721 6.57048 2.10429 6.43787 2.10549 6.30021C2.10668 6.16256 2.1619 6.03088 2.25924 5.93355C2.35658 5.83621 2.48825 5.78099 2.62591 5.7798C2.76356 5.7786 2.89618 5.83152 2.99519 5.92715L5.24902 8.18098L10.1278 3.30215C10.2263 3.20373 10.3598 3.14844 10.499 3.14844C10.6382 3.14844 10.7717 3.20373 10.8702 3.30215Z" fill="#F7F7F7"/>
</svg>';

// Star SVG (Clutch-style red star)
$star_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
    <path d="M8.99648 13.1013L5.36648 15.2933C5.25982 15.3459 5.16082 15.3673 5.06948 15.3573C4.97882 15.3466 4.89048 15.3153 4.80448 15.2633C4.71782 15.2099 4.65248 15.1346 4.60848 15.0373C4.56448 14.9399 4.56048 14.8336 4.59648 14.7183L5.56248 10.6083L2.36748 7.83828C2.27748 7.76495 2.21815 7.67728 2.18948 7.57528C2.16082 7.47328 2.16715 7.37561 2.20848 7.28228C2.24982 7.18895 2.30482 7.11228 2.37348 7.05228C2.44282 6.99428 2.53615 6.95495 2.65348 6.93428L6.86948 6.56628L8.51348 2.67428C8.55882 2.56428 8.62415 2.48495 8.70949 2.43628C8.79482 2.38761 8.89048 2.36328 8.99648 2.36328C9.10248 2.36328 9.19848 2.38761 9.28448 2.43628C9.37048 2.48495 9.43548 2.56428 9.47948 2.67428L11.1235 6.56628L15.3385 6.93428C15.4565 6.95428 15.5502 6.99395 15.6195 7.05328C15.6888 7.11195 15.7442 7.18828 15.7855 7.28228C15.8262 7.37561 15.8322 7.47328 15.8035 7.57528C15.7748 7.67728 15.7155 7.76495 15.6255 7.83828L12.4305 10.6083L13.3965 14.7183C13.4338 14.8323 13.4302 14.9383 13.3855 15.0363C13.3408 15.1343 13.2752 15.2096 13.1885 15.2623C13.1032 15.3156 13.0148 15.3473 12.9235 15.3573C12.8328 15.3673 12.7342 15.3459 12.6275 15.2933L8.99648 13.1013Z" fill="#FC0000"/>
</svg>';
?>

<section
    class="ca-section"
    style="
        --ca-pt: <?php echo $pt; ?>px;
        --ca-pb: <?php echo $pb; ?>px;
        --ca-pt-mob: <?php echo $pt_mob; ?>px;
        --ca-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ca-section__container">

        <?php /* ── Large title ───────────────────────────────────────────── */ ?>
        <?php if ( $title_raw ) : ?>
            <p class="ca-title">
                <?php if ( $first_word ) : ?>
                    <span class="ca-title__first"><?php echo $first_word; ?></span>
                <?php endif; ?>
                <?php if ( $rest_words ) : ?>
                    <span class="ca-title__rest"><?php echo $rest_words; ?></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php /* ── Two columns ──────────────────────────────────────────── */ ?>
        <div class="ca-row">

            <?php /* Left column */ ?>
            <div class="ca-col ca-col--left">

                <?php if ( $badge_label ) : ?>
                    <span class="ca-badge"><?php echo esc_html( $badge_label ); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $items ) ) : ?>
                    <ul class="ca-list">
                        <?php foreach ( $items as $item ) :
                            $text = esc_html( $item['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="ca-list__item">
                                <span class="ca-list__icon" aria-hidden="true"><?php echo $check_svg; ?></span>
                                <span class="ca-list__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php /* Rating block */ ?>
                <div class="ca-rating">
                    <span class="ca-rating__value"><?php echo esc_html( $rating_value ); ?></span>
                    <div class="ca-rating__right">
                        <div class="ca-rating__stars" aria-label="<?php echo esc_attr( $rating_stars . ' out of 5 stars' ); ?>">
                            <?php for ( $s = 0; $s < $rating_stars; $s++ ) : ?>
                                <?php echo $star_svg; ?>
                            <?php endfor; ?>
                        </div>
                        <?php if ( $link_url ) : ?>
                            <a
                                class="ca-rating__link"
                                href="<?php echo $link_url; ?>"
                                target="<?php echo esc_attr( $link_tgt ); ?>"
                                rel="noopener noreferrer"
                            ><?php echo $link_label; ?></a>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- .ca-col--left -->

            <?php /* Right column: image */ ?>
            <div class="ca-col ca-col--right">
                <?php if ( $image ) : ?>
                    <div
                        class="ca-image"
                        style="background-image: linear-gradient(90deg, rgba(17,10,3,0.00) 0%, rgba(17,10,3,0.40) 100%), url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: '' ); ?>"
                    ></div>
                <?php else : ?>
                    <div class="ca-image ca-image--empty" aria-hidden="true"></div>
                <?php endif; ?>
            </div><!-- .ca-col--right -->

        </div><!-- .ca-row -->

    </div><!-- .ca-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
