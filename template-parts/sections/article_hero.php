<?php
/**
 * Block: Article Hero Section
 * Slug: acf/article-hero
 *
 * Single post hero: meta + title + excerpt + author + CTA banner | featured image with category badges
 *
 * Notes:
 * - Title: ACF field OR falls back to post title (get_the_title())
 * - Description: ACF field OR falls back to post excerpt (get_the_excerpt()), max 160 chars
 * - Meta: post date (formatted) + reading time (auto-calculated, Polylang-aware)
 * - Author: from post author (photo via Simple Local Avatars / WP avatar + ACF override)
 * - Categories: post categories shown as badge pills on the image
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 40 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 20 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 100 );

// ── Content fields (with fallbacks to post data) ──────────────────────────────
$custom_title = get_field( 'custom_title' );
$title        = $custom_title ?: get_the_title();

$custom_desc  = get_field( 'description' );
$excerpt      = $custom_desc ?: get_the_excerpt();
$excerpt      = mb_substr( wp_strip_all_tags( $excerpt ), 0, 160 );

// ── Featured image ────────────────────────────────────────────────────────────
$custom_image = get_field( 'hero_image' );
$image_url    = $custom_image
    ? esc_url( $custom_image['url'] )
    : esc_url( get_the_post_thumbnail_url( null, 'large' ) );
$image_alt    = $custom_image
    ? esc_attr( $custom_image['alt'] ?: $title )
    : esc_attr( get_the_title() );

// ── Categories ────────────────────────────────────────────────────────────────
$categories = get_the_category();

// ── Author ────────────────────────────────────────────────────────────────────
$author_id       = get_the_author_meta( 'ID' );
$author_name     = get_field( 'author_name_override' ) ?: get_the_author_meta( 'display_name' );
$author_position = get_field( 'author_position' ) ?: get_the_author_meta( 'description' );
$author_photo    = get_field( 'author_photo_override' );
$author_avatar   = $author_photo
    ? esc_url( $author_photo['url'] )
    : esc_url( get_avatar_url( $author_id, [ 'size' => 134 ] ) );

// ── Reading time (Polylang-aware) ─────────────────────────────────────────────
$content        = get_the_content();
$word_count     = str_word_count( wp_strip_all_tags( $content ) );
$minutes        = max( 1, (int) ceil( $word_count / 200 ) );
$current_lang   = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';

if ( in_array( $current_lang, [ 'ru', 'uk' ] ) ) {
    // Russian/Ukrainian: count by characters (~1000 chars/min)
    $char_count = mb_strlen( wp_strip_all_tags( $content ) );
    $minutes    = max( 1, (int) ceil( $char_count / 1000 ) );
    $read_label = $minutes . '-' . ( $minutes + 2 ) . ' МИН ЧТЕНИЯ';
} else {
    $min_lo     = $minutes;
    $min_hi     = $minutes + 2;
    $read_label = $min_lo . '-' . $min_hi . ' MIN READ';
}

// ── Date ──────────────────────────────────────────────────────────────────────
$date_format = in_array( $current_lang, [ 'ru', 'uk' ] ) ? 'j M, Y' : 'j M, Y';
$post_date   = get_the_date( $date_format );

// ── CTA banner ────────────────────────────────────────────────────────────────
$cta_text    = get_field( 'cta_text' ) ?: '';
$cta_link    = get_field( 'cta_link' );
$cta_url     = ! empty( $cta_link['url'] )    ? esc_url( $cta_link['url'] )    : '';
$cta_label   = ! empty( $cta_link['title'] )  ? esc_html( $cta_link['title'] ) : '';
$cta_target  = ! empty( $cta_link['target'] ) ? $cta_link['target']             : '_self';
$cta_text_out = $cta_text ? esc_html( mb_substr( $cta_text, 0, 70 ) ) : '';

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#E9E9E9';
?>

<section
    class="ah2-section"
    style="
        --ah2-pt: <?php echo $pt; ?>px;
        --ah2-pb: <?php echo $pb; ?>px;
        --ah2-pt-mob: <?php echo $pt_mob; ?>px;
        --ah2-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ah2-section__container">
        <div class="ah2-row">

            <?php /* ── Left column ── */ ?>
            <div class="ah2-col ah2-col--left">

                <?php /* Top block: meta + title + description + author */ ?>
                <div class="ah2-top">

                    <?php /* Meta: date + reading time */ ?>
                    <div class="ah2-meta">
                        <span class="ah2-meta__date"><?php echo esc_html( $post_date ); ?></span>
                        <span class="ah2-meta__sep" aria-hidden="true">|</span>
                        <span class="ah2-meta__read"><?php echo esc_html( $read_label ); ?></span>
                    </div>

                    <?php /* Title */ ?>
                    <h1 class="ah2-title"><?php echo esc_html( $title ); ?></h1>

                    <?php /* Description / excerpt */ ?>
                    <?php if ( $excerpt ) : ?>
                        <p class="ah2-description"><?php echo esc_html( $excerpt ); ?></p>
                    <?php endif; ?>

                    <?php /* Author */ ?>
                    <div class="ah2-author">
                        <?php if ( $author_avatar ) : ?>
                            <img
                                class="ah2-author__photo"
                                src="<?php echo $author_avatar; ?>"
                                alt="<?php echo esc_attr( $author_name ); ?>"
                                width="67"
                                height="67"
                                loading="lazy"
                            >
                        <?php endif; ?>
                        <div class="ah2-author__info">
                            <?php if ( $author_name ) : ?>
                                <span class="ah2-author__name">By <?php echo esc_html( $author_name ); ?></span>
                            <?php endif; ?>
                            <?php if ( $author_position ) : ?>
                                <span class="ah2-author__position"><?php echo esc_html( $author_position ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div><!-- .ah2-top -->

                <?php /* CTA banner — pushed to bottom via margin-top: auto */ ?>
                <?php if ( $cta_text_out || $cta_url ) : ?>
                    <div class="ah2-cta">
                        <?php if ( $cta_text_out ) : ?>
                            <p class="ah2-cta__text"><?php echo $cta_text_out; ?></p>
                        <?php endif; ?>
                        <?php if ( $cta_url && $cta_label ) : ?>
                            <a
                                class="ah2-cta__btn"
                                href="<?php echo $cta_url; ?>"
                                target="<?php echo esc_attr( $cta_target ); ?>"
                                <?php echo $cta_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                            ><?php echo $cta_label; ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div><!-- .ah2-col--left -->

            <?php /* ── Right column: image ── */ ?>
            <div class="ah2-col ah2-col--right">
                <?php if ( $image_url ) : ?>
                    <div class="ah2-image-wrap">
                        <?php /* Category badges */ ?>
                        <?php if ( ! empty( $categories ) ) : ?>
                            <div class="ah2-badges">
                                <?php foreach ( $categories as $cat ) : ?>
                                    <a
                                        class="ah2-badge"
                                        href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                                    >#<?php echo esc_html( $cat->name ); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <img
                            class="ah2-image"
                            src="<?php echo $image_url; ?>"
                            alt="<?php echo $image_alt; ?>"
                            width="670"
                            height="660"
                            loading="eager"
                        >
                    </div>
                <?php endif; ?>
            </div><!-- .ah2-col--right -->

        </div><!-- .ah2-row -->
    </div><!-- .ah2-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
