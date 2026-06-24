<?php
/**
 * Block: Case Client Story
 *
 * ACF block slug : acf/case-client-story
 * bg #E9E9E9, staircase heading, video preview + description + two CTAs
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 20 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 150 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 10 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 110 );
$title_top     = esc_html( get_field( 'title_top' )    ?: __( 'Client Story In', 'lionwood' ) );
$title_bottom  = esc_html( get_field( 'title_bottom' ) ?: __( 'Their Own Words', 'lionwood' ) );
$preview_image = get_field( 'preview_image' );
$video_url     = esc_url( get_field( 'video_url' ) ?: '' );
$description   = get_field( 'description' ) ? wp_kses( get_field( 'description' ), [ 'br' => [] ] ) : '';
$watch_link    = get_field( 'watch_link' );
$more_link     = get_field( 'more_link' );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$watch_url   = ! empty( $watch_link['url'] )    ? esc_url( $watch_link['url'] )    : $video_url;
$watch_lbl   = ! empty( $watch_link['title'] )  ? esc_html( $watch_link['title'] ) : __( 'Watch Webinar', 'lionwood' );
$watch_tgt   = ! empty( $watch_link['target'] ) ? $watch_link['target']             : '_blank';

$more_url    = ! empty( $more_link['url'] )    ? esc_url( $more_link['url'] )    : '';
$more_lbl    = ! empty( $more_link['title'] )  ? esc_html( $more_link['title'] ) : __( 'More', 'lionwood' );
$more_tgt    = ! empty( $more_link['target'] ) ? $more_link['target']             : '_self';

// Play icon
$play_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <path d="M5.1795 3.40277C5.11118 3.36226 5.03335 3.34055 4.95393 3.33986C4.87451 3.33917 4.79631 3.35951 4.7273 3.39882C4.65828 3.43812 4.60089 3.495 4.56097 3.56367C4.52105 3.63233 4.50001 3.71034 4.5 3.78977V14.2103C4.50001 14.2897 4.52105 14.3677 4.56097 14.4364C4.60089 14.505 4.65828 14.5619 4.7273 14.6012C4.79631 14.6405 4.87451 14.6609 4.95393 14.6602C5.03335 14.6595 5.11118 14.6378 5.1795 14.5973L13.9718 9.38701C14.0389 9.34717 14.0945 9.29054 14.1332 9.22269C14.1718 9.15484 14.1921 9.0781 14.1921 9.00002C14.1921 8.92193 14.1718 8.8452 14.1332 8.77734C14.0945 8.70949 14.0389 8.65286 13.9718 8.61302L5.1795 3.40277Z" fill="#F7F7F7" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

// YouTube play badge
$yt_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46" fill="none" aria-hidden="true">
    <path d="M19.1654 28.7507L29.1129 23.0007L19.1654 17.2507V28.7507ZM41.322 13.7432C41.5712 14.644 41.7437 15.8515 41.8587 17.3848C41.9929 18.9182 42.0504 20.2407 42.0504 21.3907L42.1654 23.0007C42.1654 27.1982 41.8587 30.284 41.322 32.2582C40.8429 33.9832 39.7312 35.0948 38.0062 35.574C37.1054 35.8232 35.457 35.9957 32.927 36.1107C30.4354 36.2448 28.1545 36.3023 26.0462 36.3023L22.9987 36.4173C14.9679 36.4173 9.96536 36.1107 7.9912 35.574C6.2662 35.0948 5.15453 33.9832 4.67536 32.2582C4.4262 31.3573 4.2537 30.1498 4.1387 28.6165C4.00453 27.0832 3.94703 25.7607 3.94703 24.6107L3.83203 23.0007C3.83203 18.8032 4.1387 15.7173 4.67536 13.7432C5.15453 12.0182 6.2662 10.9065 7.9912 10.4273C8.89203 10.1782 10.5404 10.0057 13.0704 9.89065C15.562 9.75648 17.8429 9.69898 19.9512 9.69898L22.9987 9.58398C31.0295 9.58398 36.032 9.89065 38.0062 10.4273C39.7312 10.9065 40.8429 12.0182 41.322 13.7432Z" fill="#C83030"/>
</svg>';
?>

<section
    class="cls-section"
    style="
        --cls-pt: <?php echo $pt; ?>px;
        --cls-pb: <?php echo $pb; ?>px;
        --cls-pt-mob: <?php echo $pt_mob; ?>px;
        --cls-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cls-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="cls-heading">
            <span class="cls-heading__top"><?php echo $title_top; ?></span>
            <span class="cls-heading__bottom"><?php echo $title_bottom; ?></span>
        </div>

        <?php /* ── Row: video + content ───────────────────────────────── */ ?>
        <div class="cls-row">

            <?php /* Video preview */ ?>
            <div class="cls-video">
                <?php if ( $preview_image ) : ?>
                    <div
                        class="cls-video__thumb"
                        style="background-image: url('<?php echo esc_url( $preview_image['url'] ); ?>');"
                        aria-label="<?php echo esc_attr( $preview_image['alt'] ?: $title_top ); ?>"
                    ></div>
                <?php else : ?>
                    <div class="cls-video__thumb cls-video__thumb--empty"></div>
                <?php endif; ?>

                <?php if ( $video_url || $watch_url ) : ?>
                    <a
                        class="cls-video__play"
                        href="<?php echo $video_url ?: $watch_url; ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php esc_attr_e( 'Play video', 'lionwood' ); ?>"
                    ><?php echo $yt_icon; ?></a>
                <?php else : ?>
                    <span class="cls-video__play" aria-hidden="true"><?php echo $yt_icon; ?></span>
                <?php endif; ?>
            </div>

            <?php /* Right content */ ?>
            <div class="cls-content">
                <?php if ( $description ) : ?>
                    <p class="cls-content__desc"><?php echo $description; ?></p>
                <?php endif; ?>

                <div class="cls-content__btns">
                    <?php if ( $watch_url ) : ?>
                        <a
                            class="cls-btn cls-btn--red"
                            href="<?php echo $watch_url; ?>"
                            target="<?php echo esc_attr( $watch_tgt ); ?>"
                            rel="noopener noreferrer"
                        >
                            <?php echo $play_icon; ?>
                            <?php echo $watch_lbl; ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( $more_url ) : ?>
                        <a
                            class="cls-btn cls-btn--outline"
                            href="<?php echo $more_url; ?>"
                            target="<?php echo esc_attr( $more_tgt ); ?>"
                            <?php echo '_blank' === $more_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                        ><?php echo $more_lbl; ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- .cls-row -->

    </div><!-- .cls-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
