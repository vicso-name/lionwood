<?php
/**
 * Block: Case Testimonial
 *
 * ACF block slug : acf/case-testimonial
 * bg #F7F7F7, author row, star rating, large review, highlights row
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$avatar        = get_field( 'author_avatar' );
$author_name   = esc_html( get_field( 'author_name' )     ?: '' );
$author_pos    = esc_html( get_field( 'author_position' ) ?: '' );
$rating        = absint( get_field( 'rating' ) ?: 5 );
$clutch_link   = get_field( 'clutch_link' );
$review        = get_field( 'review' ) ?: '';
$highlights    = get_field( 'highlights' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$clutch_url    = ! empty( $clutch_link['url'] )    ? esc_url( $clutch_link['url'] )    : '';
$clutch_lbl    = ! empty( $clutch_link['title'] )  ? esc_html( $clutch_link['title'] ) : __( 'Read full review on Clutch', 'lionwood' );
$clutch_tgt    = ! empty( $clutch_link['target'] ) ? $clutch_link['target']             : '_blank';

// Star SVG
$star_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <path d="M8.99648 13.1023L5.36648 15.2943C5.25982 15.3469 5.16082 15.3683 5.06948 15.3583C4.97882 15.3476 4.89048 15.3163 4.80448 15.2643C4.71782 15.2109 4.65248 15.1356 4.60848 15.0383C4.56448 14.9409 4.56048 14.8346 4.59648 14.7193L5.56248 10.6093L2.36748 7.83926C2.27748 7.76592 2.21815 7.67826 2.18948 7.57626C2.16082 7.47426 2.16715 7.37659 2.20848 7.28326C2.24982 7.18992 2.30482 7.11326 2.37348 7.05326C2.44282 6.99526 2.53615 6.95592 2.65348 6.93526L6.86948 6.56726L8.51348 2.67526C8.55882 2.56526 8.62415 2.48592 8.70949 2.43726C8.79482 2.38859 8.89048 2.36426 8.99648 2.36426C9.10248 2.36426 9.19848 2.38859 9.28448 2.43726C9.37048 2.48592 9.43548 2.56526 9.47948 2.67526L11.1235 6.56726L15.3385 6.93526C15.4565 6.95526 15.5502 6.99492 15.6195 7.05426C15.6888 7.11292 15.7442 7.18926 15.7855 7.28326C15.8262 7.37659 15.8322 7.47426 15.8035 7.57626C15.7748 7.67826 15.7155 7.76592 15.6255 7.83926L12.4305 10.6093L13.3965 14.7193C13.4338 14.8333 13.4302 14.9393 13.3855 15.0373C13.3408 15.1353 13.2752 15.2106 13.1885 15.2633C13.1032 15.3166 13.0148 15.3483 12.9235 15.3583C12.8328 15.3683 12.7342 15.3469 12.6275 15.2943L8.99648 13.1023Z" fill="#FC0000"/>
</svg>';

// Check icon
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none" aria-hidden="true">
    <path d="M8.76473 0.153713C8.86315 0.252165 8.91844 0.385677 8.91844 0.524888C8.91844 0.664099 8.86315 0.797611 8.76473 0.896063L3.51472 6.14606C3.41627 6.24449 3.28276 6.29978 3.14355 6.29978C3.00434 6.29978 2.87083 6.24449 2.77237 6.14606L0.147374 3.52106C0.0517409 3.42205 -0.00117632 3.28943 1.98463e-05 3.15178C0.00121602 3.01412 0.0564298 2.88245 0.153769 2.78511C0.251108 2.68777 0.382785 2.63256 0.520438 2.63136C0.658091 2.63016 0.790708 2.68308 0.889724 2.77871L3.14355 5.03254L8.02237 0.153713C8.12083 0.0552907 8.25434 0 8.39355 0C8.53276 0 8.66627 0.0552907 8.76473 0.153713Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="ctm-section"
    style="
        --ctm-pt: <?php echo $pt; ?>px;
        --ctm-pb: <?php echo $pb; ?>px;
        --ctm-pt-mob: <?php echo $pt_mob; ?>px;
        --ctm-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ctm-section__container">

        <?php /* ── Author row ───────────────────────────────────────────── */ ?>
        <div class="ctm-author-row">
            <div class="ctm-author">
                <?php if ( $avatar ) : ?>
                    <img
                        class="ctm-author__avatar"
                        src="<?php echo esc_url( $avatar['url'] ); ?>"
                        alt="<?php echo esc_attr( $avatar['alt'] ?: $author_name ); ?>"
                        width="80"
                        height="80"
                        loading="lazy"
                    >
                <?php else : ?>
                    <div class="ctm-author__avatar ctm-author__avatar--placeholder" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#E9E9E9"/>
                            <circle cx="40" cy="30" r="12" fill="#848588"/>
                            <ellipse cx="40" cy="62" rx="20" ry="14" fill="#848588"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <div class="ctm-author__info">
                    <?php if ( $author_name ) : ?>
                        <p class="ctm-author__name"><?php echo $author_name; ?></p>
                    <?php endif; ?>
                    <?php if ( $author_pos ) : ?>
                        <p class="ctm-author__position"><?php echo $author_pos; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="ctm-rating">
                <div class="ctm-rating__stars" aria-label="<?php echo esc_attr( $rating . ' out of 5 stars' ); ?>">
                    <?php for ( $s = 0; $s < $rating; $s++ ) : ?>
                        <?php echo $star_svg; ?>
                    <?php endfor; ?>
                </div>
                <?php if ( $clutch_url ) : ?>
                    <a
                        class="ctm-rating__link"
                        href="<?php echo $clutch_url; ?>"
                        target="<?php echo esc_attr( $clutch_tgt ); ?>"
                        rel="noopener noreferrer"
                    ><?php echo $clutch_lbl; ?></a>
                <?php endif; ?>
            </div>
        </div>

        <?php /* ── Review text ──────────────────────────────────────────── */ ?>
        <?php if ( $review ) : ?>
            <div class="ctm-review"><?php echo wp_kses_post( $review ); ?></div>
        <?php endif; ?>

        <?php /* ── Highlights row ─────────────────────────────────────── */ ?>
        <?php if ( ! empty( $highlights ) ) : ?>
            <div class="ctm-highlights" role="list">
                <div class="ctm-highlights__line ctm-highlights__line--top" aria-hidden="true"></div>
                <div class="ctm-highlights__items">
                    <?php foreach ( $highlights as $i => $highlight ) :
                        $text = esc_html( $highlight['text'] ?? '' );
                        if ( ! $text ) continue;
                    ?>
                        <?php if ( $i > 0 ) : ?>
                            <span class="ctm-highlights__sep" aria-hidden="true"></span>
                        <?php endif; ?>
                        <div class="ctm-highlights__item" role="listitem">
                            <span class="ctm-highlights__icon"><?php echo $check_svg; ?></span>
                            <span class="ctm-highlights__text"><?php echo $text; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ctm-highlights__line ctm-highlights__line--bottom" aria-hidden="true"></div>
            </div>
        <?php endif; ?>

    </div><!-- .ctm-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
