<?php
/**
 * Block: Industries Section
 *
 * ACF block slug : acf/industries-section
 * Template file  : blocks/industries-section/industries-section.php
 *
 * Full-screen section (min-height: 100vh).
 * Active slide changes via IntersectionObserver on thumbnails
 * or direct thumbnail click.
 *
 * NOTE: For a true scroll-driven "sticky" experience where each industry
 * occupies its own scroll step, wrap .ind-section in a tall container
 * and set .ind-section to position:sticky; top:0. Currently uses
 * IntersectionObserver + click navigation.
 */

defined( 'ABSPATH' ) || exit;

// ── Fields ───────────────────────────────────────────────────────────────────
$pt      = absint( get_field( 'padding_top' )        ?: 100 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: __( 'Our Industry', 'lionwood' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'Expertise', 'lionwood' );
$desc_raw     = get_field( 'description' );
$description  = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$cta_label_raw = get_field( 'cta_label' ) ?: __( "Didn't find your industry or solution?", 'lionwood' );
$cta_link_raw  = get_field( 'cta_link' );
$cta_url       = ! empty( $cta_link_raw['url'] )    ? esc_url( $cta_link_raw['url'] )    : '';
$cta_label     = ! empty( $cta_link_raw['title'] )  ? esc_html( $cta_link_raw['title'] ) : __( 'Learn More', 'lionwood' );
$cta_target    = ! empty( $cta_link_raw['target'] ) ? $cta_link_raw['target']              : '_self';

$industries = get_field( 'industries' ) ?: [];
$total      = count( $industries );

if ( empty( $industries ) ) return;
?>

<section
    class="ind-section"
    style="
        --ind-pt: <?php echo $pt; ?>px;
        --ind-pb: <?php echo $pb; ?>px;
        --ind-pt-mob: <?php echo $pt_mob; ?>px;
        --ind-pb-mob: <?php echo $pb_mob; ?>px;
        --ind-total: <?php echo $total; ?>;
    "
    data-total="<?php echo esc_attr( $total ); ?>"
>
    <div class="ind-section__container">

        <?php /* ── Header row ───────────────────────────────────────────── */ ?>
        <div class="ind-section__header">

            <div class="ind-section__heading">
                <span class="ind-section__title-top"><?php echo esc_html( $title_top ); ?></span>
                <span class="ind-section__title-bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </div>

            <?php if ( $description ) : ?>
                <p class="ind-section__description"><?php echo $description; ?></p>
            <?php endif; ?>

            <?php /* Progress circle — SVG with animated stroke-dashoffset */ ?>
            <div class="ind-progress" aria-label="Industry progress">
                <svg class="ind-progress__svg" viewBox="0 0 60 60" fill="none" aria-hidden="true">
                    <?php
                    // r=27 → circumference = 2πr ≈ 169.6
                    $r = 27;
                    $cx = 30; $cy = 30;
                    $circum = round( 2 * M_PI * $r, 2 );
                    ?>
                    <?php /* Track circle */ ?>
                    <circle
                        class="ind-progress__track"
                        cx="<?php echo $cx; ?>"
                        cy="<?php echo $cy; ?>"
                        r="<?php echo $r; ?>"
                        stroke-width="2"
                        stroke="#FFF"
                        fill="none"
                        opacity="0.2"
                    />
                    <?php /* Fill circle — stroke-dashoffset driven by JS */ ?>
                    <circle
                        class="ind-progress__fill"
                        cx="<?php echo $cx; ?>"
                        cy="<?php echo $cy; ?>"
                        r="<?php echo $r; ?>"
                        stroke-width="2"
                        stroke="#FFF"
                        fill="none"
                        stroke-linecap="round"
                        stroke-dasharray="<?php echo $circum; ?>"
                        stroke-dashoffset="<?php echo $circum; ?>"
                        transform="rotate(-90 <?php echo $cx; ?> <?php echo $cy; ?>)"
                        style="transition: stroke-dashoffset 0.4s ease;"
                    />
                </svg>
                <span class="ind-progress__text">
                    <span class="ind-progress__current" data-progress-current>1</span>/<span class="ind-progress__total"><?php echo $total; ?></span>
                </span>
            </div>

        </div><!-- .ind-section__header -->

        <?php /* ── Main slider ─────────────────────────────────────────── */ ?>
        <div class="ind-slider" data-slider>

            <?php foreach ( $industries as $i => $post ) :
                $post_id   = $post->ID;
                $title     = esc_html( get_the_title( $post_id ) );
                $permalink = esc_url( get_permalink( $post_id ) );
                $thumb_id  = get_post_thumbnail_id( $post_id );
                $thumb     = $thumb_id ? wp_get_attachment_image_src( $thumb_id, 'large' ) : null;
                $thumb_alt = $thumb_id ? esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: $title ) : $title;
                $num       = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
                $is_first  = ( 0 === $i );
            ?>
            <div
                class="ind-slide<?php echo $is_first ? ' is-active' : ''; ?>"
                data-index="<?php echo esc_attr( $i ); ?>"
                aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
            >
                <?php /* Image card */ ?>
                <div class="ind-slide__card">

                    <?php if ( $thumb ) : ?>
                        <img
                            class="ind-slide__image"
                            src="<?php echo esc_url( $thumb[0] ); ?>"
                            alt="<?php echo $thumb_alt; ?>"
                            loading="<?php echo $is_first ? 'eager' : 'lazy'; ?>"
                        >
                    <?php else : ?>
                        <div class="ind-slide__image-placeholder"></div>
                    <?php endif; ?>

                    <?php /* Overlay: number + title top-left */ ?>
                    <div class="ind-slide__overlay-top">
                        <span class="ind-slide__num"><?php echo $num; ?></span>
                        <span class="ind-slide__name"><?php echo $title; ?></span>
                    </div>

                    <?php /* Arrow button bottom-right */ ?>
                    <a
                        class="ind-slide__arrow"
                        href="<?php echo $permalink; ?>"
                        aria-label="<?php echo esc_attr( sprintf( __( 'Learn more about %s', 'lionwood' ), $title ) ); ?>"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" aria-hidden="true">
                            <path d="M8.61056 17.2172L17.2188 8.60896M10.7626 8.60896L17.2188 8.60896L17.2188 15.0651" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                </div><!-- .ind-slide__card -->
            </div><!-- .ind-slide -->
            <?php endforeach; ?>

        </div><!-- .ind-slider -->

        <?php /* ── Bottom row: thumbnails (left) + CTA (right) ─────────── */ ?>
        <div class="ind-section__footer">

            <?php /* Thumbnails nav */ ?>
            <div class="ind-thumbs" role="tablist" aria-label="<?php esc_attr_e( 'Industry navigation', 'lionwood' ); ?>">
                <?php foreach ( $industries as $i => $post ) :
                    $post_id   = $post->ID;
                    $title     = esc_html( get_the_title( $post_id ) );
                    $thumb_id  = get_post_thumbnail_id( $post_id );
                    $thumb     = $thumb_id ? wp_get_attachment_image_src( $thumb_id, 'thumbnail' ) : null;
                    $thumb_alt = $thumb_id ? esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: $title ) : $title;
                    $is_first  = ( 0 === $i );
                ?>
                <button
                    class="ind-thumb<?php echo $is_first ? ' is-active' : ''; ?>"
                    data-thumb-index="<?php echo esc_attr( $i ); ?>"
                    role="tab"
                    aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
                    aria-label="<?php echo esc_attr( $title ); ?>"
                >
                    <?php if ( $thumb ) : ?>
                        <img
                            src="<?php echo esc_url( $thumb[0] ); ?>"
                            alt="<?php echo $thumb_alt; ?>"
                            width="80"
                            height="80"
                            loading="lazy"
                        >
                    <?php else : ?>
                        <div class="ind-thumb__placeholder"></div>
                    <?php endif; ?>
                </button>
                <?php endforeach; ?>
            </div><!-- .ind-thumbs -->

            <?php /* CTA block */ ?>
            <div class="ind-section__cta">
                <?php if ( $cta_label_raw ) : ?>
                    <p class="ind-section__cta-label"><?php echo esc_html( $cta_label_raw ); ?></p>
                <?php endif; ?>
                <?php if ( $cta_url ) : ?>
                    <a
                        class="ind-section__cta-btn"
                        href="<?php echo $cta_url; ?>"
                        target="<?php echo esc_attr( $cta_target ); ?>"
                        <?php echo '_blank' === $cta_target ? 'rel="noopener noreferrer"' : ''; ?>
                    ><?php echo $cta_label; ?></a>
                <?php endif; ?>
            </div>

        </div><!-- .ind-section__footer -->

    </div><!-- .ind-section__container -->

    <?php get_template_part('template-parts/partials/decor-bottom'); ?>

</section>
