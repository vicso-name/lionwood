<?php
/**
 * Block: About Author
 * Slug: acf/about-author
 *
 * Author data sources (priority order):
 *  - Photo:      ACF user field 'author_photo'        → Gravatar fallback
 *  - Name:       ACF block override                   → get_the_author_meta('display_name')
 *  - Role:       ACF block override                   → ACF user 'author_position_{lang}'
 *  - LinkedIn:   ACF user field 'author_linkedin_url' (shared, no lang suffix)
 *  - Experience: ACF block override repeater          → ACF user 'author_expertise_{lang}'
 *  - Bio:        ACF block override                   → ACF user 'author_bio_short_{lang}'
 *  - All posts:  ACF block link override              → get_author_posts_url()
 */

defined( 'ABSPATH' ) || exit;

// ── Current language (Polylang) ───────────────────────────────────────────────
$lang      = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
$lang_safe = in_array( $lang, [ 'en', 'uk' ] ) ? $lang : 'en'; // supported langs

// ── Author ID ─────────────────────────────────────────────────────────────────
$author_id  = get_the_author_meta( 'ID' );
$user_key   = 'user_' . $author_id;

// ── Section title ─────────────────────────────────────────────────────────────
$section_title = get_field( 'section_title' ) ?: __( 'About Author', 'lionwood' );

// ── Photo — ACF user field → Gravatar ─────────────────────────────────────────
$user_photo = get_field( 'author_photo', $user_key );
$photo_src  = $user_photo
    ? esc_url( $user_photo['url'] )
    : esc_url( get_avatar_url( $author_id, [ 'size' => 160 ] ) );
$photo_alt  = $user_photo
    ? esc_attr( $user_photo['alt'] ?: get_the_author_meta( 'display_name' ) )
    : esc_attr( get_the_author_meta( 'display_name' ) );

// ── Name — WP display_name ────────────────────────────────────────────────────
$author_name = get_the_author_meta( 'display_name' );

// ── Role — ACF user field (lang-aware) ───────────────────────────────────────
$author_role = get_field( "author_position_{$lang_safe}", $user_key )
            ?: get_field( 'author_position_en', $user_key );

// ── LinkedIn — ACF user field (shared) ────────────────────────────────────────
$linkedin_url = get_field( 'author_linkedin_url', $user_key );

// ── Experience — ACF user repeater (lang-aware) ───────────────────────────────
$experience = get_field( "author_expertise_{$lang_safe}", $user_key )
           ?: get_field( 'author_expertise_en', $user_key )
           ?: [];

// ── Bio — ACF user field (lang-aware) → WP bio ───────────────────────────────
$author_bio = get_field( "author_bio_short_{$lang_safe}", $user_key )
           ?: get_field( 'author_bio_short_en', $user_key )
           ?: get_the_author_meta( 'description' );

// ── All posts link — block override → author archive ─────────────────────────
$all_posts_link = get_field( 'all_posts_link' );
$all_posts_href = ! empty( $all_posts_link['url'] )
    ? esc_url( $all_posts_link['url'] )
    : esc_url( get_author_posts_url( $author_id ) );
$all_posts_lbl  = ! empty( $all_posts_link['title'] )
    ? esc_html( $all_posts_link['title'] )
    : __( 'All articles by author', 'lionwood' );
$all_posts_tgt  = ! empty( $all_posts_link['target'] ) ? $all_posts_link['target'] : '_self';

// ── Rating ────────────────────────────────────────────────────────────────────
$show_rating  = get_field( 'show_rating' );
$post_id      = get_the_ID();
$rating_count = 0;
$rating_avg   = 5.0;

if ( $show_rating !== false ) {
    $rating_count = (int) get_post_meta( $post_id, '_aa_rating_count', true );
    $rating_sum   = (int) get_post_meta( $post_id, '_aa_rating_sum',   true );
    $rating_avg   = $rating_count > 0 ? round( $rating_sum / $rating_count, 1 ) : 5.0;
}

// ── SVGs ──────────────────────────────────────────────────────────────────────
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true"><path d="M10.8702 3.30215C10.9686 3.4006 11.0239 3.53411 11.0239 3.67333C11.0239 3.81254 10.9686 3.94605 10.8702 4.0445L5.62019 9.2945C5.52174 9.39292 5.38823 9.44821 5.24902 9.44821C5.10981 9.44821 4.97629 9.39292 4.87784 9.2945L2.15721 6.57048 2.10429 6.43787 2.10549 6.30021C2.10668 6.16256 2.1619 6.03088 2.25924 5.93355C2.35658 5.83621 2.48825 5.78099 2.62591 5.7798C2.76356 5.7786 2.89618 5.83152 2.99519 5.92715L5.24902 8.18098L10.1278 3.30215C10.2263 3.20373 10.3598 3.14844 10.499 3.14844C10.6382 3.14844 10.7717 3.20373 10.8702 3.30215Z" fill="#F7F7F7"/></svg>';

$linkedin_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><g clip-path="url(#clip_li)"><path d="M17 0H3C1.34315 0 0 1.34315 0 3V17C0 18.6569 1.34315 20 3 20H17C18.6569 20 20 18.6569 20 17V3C20 1.34315 18.6569 0 17 0Z" fill="#0077B5"/><path d="M5.54688 6.83594C6.3451 6.83594 6.99219 6.18885 6.99219 5.39062C6.99219 4.5924 6.3451 3.94531 5.54688 3.94531C4.74865 3.94531 4.10156 4.5924 4.10156 5.39062C4.10156 6.18885 4.74865 6.83594 5.54688 6.83594Z" fill="white"/><path d="M9.53125 7.57812V15.3125M5.54688 7.57812V15.3125" stroke="white" stroke-width="2.57812"/><path d="M10.7812 11.0156C10.7812 10.2344 11.2891 9.45312 12.1875 9.45312C13.125 9.45312 13.4766 10.1562 13.4766 11.2109V15.3125H16.0547V10.8984C16.0547 8.51562 14.8047 7.42188 13.0859 7.42188C11.7578 7.42188 11.0938 8.16406 10.7812 8.67188" fill="white"/></g><defs><clipPath id="clip_li"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>';
?>

<section class="aa-section">
    <div class="aa-section__container">

        <h2 class="aa-title"><?php echo esc_html( $section_title ); ?></h2>

        <div class="aa-divider" aria-hidden="true"></div>

        <div class="aa-body">

            <?php /* ── Left col ── */ ?>
            <div class="aa-col aa-col--left">

                <div class="aa-author-head">
                    <?php if ( $photo_src ) : ?>
                        <img class="aa-author__photo" src="<?php echo $photo_src; ?>" alt="<?php echo $photo_alt; ?>" width="80" height="80" loading="lazy">
                    <?php endif; ?>
                    <div class="aa-author__info">
                        <?php if ( $author_name ) : ?>
                            <span class="aa-author__name"><?php echo esc_html( $author_name ); ?></span>
                        <?php endif; ?>
                        <div class="aa-author__role-row">
                            <?php if ( $linkedin_url ) : ?>
                                <a class="aa-author__linkedin" href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><?php echo $linkedin_svg; ?></a>
                            <?php endif; ?>
                            <?php if ( $author_role ) : ?>
                                <span class="aa-author__role"><?php echo esc_html( $author_role ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ( ! empty( $experience ) ) : ?>
                    <ul class="aa-exp-list">
                        <?php foreach ( $experience as $exp ) :
                            $text = esc_html( $exp['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="aa-exp-list__item">
                                <span class="aa-exp-list__icon"><?php echo $check_svg; ?></span>
                                <span class="aa-exp-list__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <div class="aa-body__divider" aria-hidden="true"></div>

            <?php /* ── Right col ── */ ?>
            <div class="aa-col aa-col--right">

                <?php if ( $author_bio ) : ?>
                    <p class="aa-bio"><?php echo wp_kses( $author_bio, [ 'br' => [], 'strong' => [], 'em' => [] ] ); ?></p>
                <?php endif; ?>

                <a
                    class="aa-cta-btn"
                    href="<?php echo $all_posts_href; ?>"
                    target="<?php echo esc_attr( $all_posts_tgt ); ?>"
                    <?php echo $all_posts_tgt === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $all_posts_lbl; ?></a>

            </div>

        </div>

        <?php /* ── Rating ── */ ?>
        <?php if ( $show_rating !== false ) : ?>
            <div class="aa-rating"
                 data-post-id="<?php echo esc_attr( $post_id ); ?>"
                 data-rest-url="<?php echo esc_url( rest_url( 'lionwood/v1/rating' ) ); ?>"
                 data-nonce="<?php echo wp_create_nonce( 'wp_rest' ); ?>">
                <div class="aa-rating__left">
                    <span class="aa-rating__label"><?php esc_html_e( 'Rate this article', 'lionwood' ); ?></span>
                    <span class="aa-rating__count">
                        <?php printf(
                            esc_html__( '%1$s ratings, average: %2$s out of 5', 'lionwood' ),
                            number_format_i18n( $rating_count ),
                            number_format( $rating_avg, 1 )
                        ); ?>
                    </span>
                </div>
                <div class="aa-rating__stars" role="group" aria-label="<?php esc_attr_e( 'Rate this article', 'lionwood' ); ?>">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <button class="aa-star" type="button" data-value="<?php echo $i; ?>" aria-label="<?php printf( esc_attr__( '%d out of 5 stars', 'lionwood' ), $i ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="25" viewBox="0 0 26 25" fill="none" aria-hidden="true"><path d="M11.8176 0.615874C12.1593 -0.204993 13.3222 -0.205007 13.664 0.615852L16.4619 7.33622C16.606 7.68217 16.9314 7.91856 17.3049 7.94863L24.5607 8.53271C25.447 8.60406 25.8064 9.71 25.1313 10.2887L19.6046 15.0264C19.3201 15.2703 19.1959 15.6528 19.2827 16.0173L20.9692 23.0986C21.1752 23.9635 20.2345 24.6471 19.4755 24.1838L13.2617 20.3916C12.9418 20.1963 12.5397 20.1963 12.2198 20.3916L6.00632 24.1838C5.24735 24.647 4.30657 23.9635 4.51257 23.0985L6.19904 16.0173C6.28586 15.6528 6.16158 15.2703 5.87708 15.0264L0.350441 10.2887C-0.324627 9.71 0.034743 8.60405 0.92104 8.53271L8.17682 7.94863C8.55036 7.91856 8.87574 7.68217 9.01977 7.3362L11.8176 0.615874Z" fill="white"/></svg>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
