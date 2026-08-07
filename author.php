<?php
/**
 * Template: Author Archive
 *
 * Sections:
 *  1. Author Hero  — dark bg, position + name + LinkedIn + bio + photo
 *  2. Career Values strip — author_highlights from user profile
 *  3. Posts Grid   — WP_Query paginated
 */

get_header();

// ── Author data ───────────────────────────────────────────────────────────────
$author_id   = get_queried_object_id();
$user_key    = 'user_' . $author_id;
$lang        = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
$lang_safe   = in_array( $lang, [ 'en', 'uk' ] ) ? $lang : 'en';

$author_name  = get_field( "author_name_{$lang_safe}", $user_key )
             ?: get_field( 'author_name_en', $user_key )
             ?: get_the_author_meta( 'display_name', $author_id );
$position     = get_field( "author_position_{$lang_safe}", $user_key )
             ?: get_field( 'author_position_en', $user_key );
$bio          = get_field( "author_bio_short_{$lang_safe}", $user_key )
             ?: get_field( 'author_bio_short_en', $user_key )
             ?: get_the_author_meta( 'description', $author_id );
$linkedin_url = get_field( 'author_linkedin_url', $user_key );
$highlights   = get_field( "author_highlights_{$lang_safe}", $user_key )
             ?: get_field( 'author_highlights_en', $user_key )
             ?: [];

// Photos
$hero_photo   = get_field( 'author_hero_photo', $user_key );
$avatar_photo = get_field( 'author_photo', $user_key );
$avatar_url   = $avatar_photo
    ? esc_url( $avatar_photo['url'] )
    : esc_url( get_avatar_url( $author_id, [ 'size' => 160 ] ) );

// SVGs
$li_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3.95833 1.5625C3.48868 1.5625 3.03826 1.74907 2.70617 2.08117C2.37407 2.41326 2.1875 2.86368 2.1875 3.33333C2.1875 3.80299 2.37407 4.25341 2.70617 4.5855C3.03826 4.9176 3.48868 5.10417 3.95833 5.10417C4.42799 5.10417 4.87841 4.9176 5.2105 4.5855C5.5426 4.25341 5.72917 3.80299 5.72917 3.33333C5.72917 2.86368 5.5426 2.41326 5.2105 2.08117C4.87841 1.74907 4.42799 1.5625 3.95833 1.5625ZM2.29167 6.5625H5.72917V17.6042H2.29167V6.5625ZM7.60417 6.5625H11.1458V8.67188C11.6035 7.92188 12.5 7.42188 14.0417 7.42188C16.0542 7.42188 17.8125 8.5 17.8125 11.6667V17.6042H14.375V12.0833C14.375 10.8333 13.5417 10.1042 12.7083 10.1042C11.875 10.1042 11.1458 10.8333 11.1458 12.0833V17.6042H7.60417V6.5625Z" fill="#F7F7F7"/></svg>';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" aria-hidden="true"><path d="M21.7404 6.60625C21.9372 6.80316 22.0478 7.07018 22.0478 7.3486C22.0478 7.62703 21.9372 7.89405 21.7404 8.09095L11.2404 18.591C11.0435 18.7878 10.7765 18.8984 10.498 18.8984C10.2196 18.8984 9.95259 18.7878 9.75569 18.591L4.50569 13.341C4.31442 13.1429 4.20858 12.8777 4.21098 12.6024C4.21337 12.3271 4.3238 12.0637 4.51848 11.869C4.71315 11.6744 4.97651 11.5639 5.25181 11.5615C5.52712 11.5592 5.79235 11.665 5.99039 11.8563L10.498 16.3639L20.2557 6.60625C20.4526 6.40941 20.7196 6.29883 20.998 6.29883C21.2765 6.29883 21.5435 6.40941 21.7404 6.60625Z" fill="#C83030"/></svg>';
?>

<?php get_template_part( 'template-parts/partials/breadcrumbs' ); ?>

<?php /* ══════════════════════════════════════════════════════
   SECTION 1 — AUTHOR HERO
══════════════════════════════════════════════════════ */ ?>
<section class="auh-section">
    <div class="auh-section__inner">

        <?php /* ── Left: text ── */ ?>
        <div class="auh-col auh-col--text">

            <?php /* Position + name */ ?>
            <div class="auh-heading">
                <?php if ( $position ) : ?>
                    <span class="auh-heading__position auh-anim" data-delay="0"><?php
                        $pos_parts = preg_split( '/<br\s*\/?>/i', (string) $position, 2 );
                        echo esc_html( trim( wp_strip_all_tags( $pos_parts[0] ) ) );
                        if ( ! empty( $pos_parts[1] ) ) :
                    ?><br><span class="auh-pos-line--lower"><?php echo esc_html( trim( wp_strip_all_tags( $pos_parts[1] ) ) ); ?></span><?php
                        endif;
                    ?></span>
                <?php endif; ?>
                <div class="auh-heading__name-row auh-anim" data-delay="150">
                    <h1 class="auh-heading__name"><?php echo esc_html( $author_name ); ?></h1>
                    <?php if ( $linkedin_url ) : ?>
                        <a class="auh-linkedin" href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <span class="auh-linkedin__icon"><?php echo $li_svg; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php /* Bio */ ?>
            <?php if ( $bio ) : ?>
                <p class="auh-bio auh-anim" data-delay="300"><?php echo wp_kses( $bio, [ 'br' => [], 'strong' => [], 'em' => [] ] ); ?></p>
            <?php endif; ?>

        </div>

        <?php /* ── Right: hero photo ── */ ?>
        <?php if ( $hero_photo ) : ?>
            <div class="auh-col auh-col--photo auh-anim" data-delay="100">
                <img
                    class="auh-photo"
                    src="<?php echo esc_url( $hero_photo['url'] ); ?>"
                    alt="<?php echo esc_attr( $hero_photo['alt'] ?: $author_name ); ?>"
                    width="550"
                    height="701"
                    loading="eager"
                >
            </div>
        <?php endif; ?>

    </div>
</section>

<?php /* ══════════════════════════════════════════════════════
   SECTION 2 — HIGHLIGHTS (cvs-section clone)
══════════════════════════════════════════════════════ */ ?>
<?php if ( ! empty( $highlights ) ) : ?>
<section class="auh-highlights">
    <div class="auh-highlights__row">
        <?php foreach ( $highlights as $i => $item ) :
            $text = esc_html( $item['text'] ?? '' );
            if ( ! $text ) continue;
        ?>
            <?php if ( $i > 0 ) : ?>
                <div class="auh-highlights__divider" aria-hidden="true"></div>
            <?php endif; ?>
            <div class="auh-highlights__item">
                <span class="auh-highlights__icon" aria-hidden="true"><?php echo $check_svg; ?></span>
                <p class="auh-highlights__text"><?php echo $text; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php /* Animated bars */ ?>
    <div class="auh-bars" aria-hidden="true">
        <span class="auh-bar" data-base="51.8"></span>
        <span class="auh-bar" data-base="70.1"></span>
        <span class="auh-bar" data-base="83.2"></span>
        <span class="auh-bar" data-base="92.6"></span>
        <span class="auh-bar" data-base="97.9"></span>
    </div>
</section>
<?php endif; ?>

<?php /* ══════════════════════════════════════════════════════
   SECTION 3 — POSTS GRID
══════════════════════════════════════════════════════ */ ?>
<?php
$posts_query = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'author'         => $author_id,
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
$post_count = $posts_query->found_posts;
?>
<section class="auh-posts">
    <div class="auh-posts__container">

        <div class="auh-posts__heading">
            <div class="auh-posts__heading-name"><?php echo esc_html( $author_name ); ?></div>
            <div class="auh-posts__heading-bottom">
                <h2 class="auh-posts__heading-label"><?php esc_html_e( 'Published works', 'lionwood' ); ?></h2>
                <span class="auh-posts__count">(<?php echo absint( $post_count ); ?> <?php esc_html_e( 'posts', 'lionwood' ); ?>)</span>
            </div>
        </div>

        <?php if ( $posts_query->have_posts() ) : ?>
            <div class="auh-posts__grid" id="auh-posts-grid">
                <?php
                $idx = 0;
                while ( $posts_query->have_posts() ) :
                    $posts_query->the_post();
                    $hidden_cls = $idx >= 6 ? ' apc-card-wrap--hidden' : '';
                ?>
                    <div class="apc-card-wrap<?php echo $hidden_cls; ?>">
                        <?php get_template_part( 'template-parts/partials/author-post-card', null, [ 'post_id' => get_the_ID() ] ); ?>
                    </div>
                <?php
                    $idx++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <?php if ( $post_count > 6 ) : ?>
                <button class="auh-load-more" id="auh-load-more" type="button">
                    <?php esc_html_e( 'Load more', 'lionwood' ); ?>
                </button>
            <?php endif; ?>

        <?php else : ?>
            <p class="auh-posts__empty"><?php esc_html_e( 'No publications yet.', 'lionwood' ); ?></p>
        <?php endif; ?>

    </div>
</section>

<?php /* ══════════════════════════════════════════════════════
   SECTION 4 — EXTERNAL RESOURCES
══════════════════════════════════════════════════════ */ ?>
<?php
$ext_title_1   = get_field( 'author_ext_title_1',        $user_key ) ?: __( "Explore author's posts", 'lionwood' );
$ext_title_2   = get_field( 'author_ext_title_2',        $user_key ) ?: __( 'in the external resources', 'lionwood' );
$ext_resources = get_field( 'author_external_resources', $user_key ) ?: [];
$ext_total     = count( $ext_resources );

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true"><path d="M5.92753 16.4891L16.4935 5.92318M8.56902 5.92318L16.4935 5.92318L16.4935 13.8477" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<?php if ( ! empty( $ext_resources ) ) : ?>
<section class="auh-ext">
    <div class="auh-ext__container">

        <div class="auh-ext__heading">
            <div class="auh-ext__heading-top"><?php echo esc_html( $ext_title_1 ); ?></div>
            <div class="auh-ext__heading-bottom"><?php echo esc_html( $ext_title_2 ); ?></div>
        </div>

        <div class="auh-ext__list" id="auh-ext-list">
            <div class="auh-ext__divider" aria-hidden="true"></div>
            <?php foreach ( $ext_resources as $idx => $item ) :
                $img     = $item['image'] ?? [];
                $img_url = ! empty( $img['url'] ) ? esc_url( $img['url'] ) : '';
                $img_alt = ! empty( $img['alt'] ) ? esc_attr( $img['alt'] ) : '';
                $url     = ! empty( $item['url'] ) ? esc_url( $item['url'] ) : '#';
                $target  = ! empty( $item['target_blank'] ) ? '_blank' : '_self';
                $rel     = ! empty( $item['target_blank'] ) ? 'noopener noreferrer' : '';
                $c_type  = esc_html( $item[ "content_type_{$lang_safe}" ] ?: ( $item['content_type_en'] ?? '' ) );
                $title_r = esc_html( $item[ "title_{$lang_safe}" ] ?: ( $item['title_en'] ?? '' ) );
                $hidden  = $idx >= 3 ? ' auh-ext__item--hidden' : '';
            ?>
                <div class="auh-ext__item<?php echo $hidden; ?>">
                    <a class="auh-ext__link" href="<?php echo $url; ?>" target="<?php echo $target; ?>"<?php echo $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?> aria-label="<?php echo $title_r; ?>">

                        <div class="auh-ext__thumb">
                            <?php if ( $img_url ) : ?>
                                <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                            <?php endif; ?>
                        </div>

                        <div class="auh-ext__content">
                            <?php if ( $c_type ) : ?>
                                <span class="auh-ext__type">[ <?php echo $c_type; ?> ]</span>
                            <?php endif; ?>
                            <?php if ( $title_r ) : ?>
                                <p class="auh-ext__title"><?php echo $title_r; ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="auh-ext__icon"><?php echo $arrow_svg; ?></div>

                    </a>
                    <div class="auh-ext__divider" aria-hidden="true"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ( $ext_total > 3 ) : ?>
            <button class="auh-load-more auh-ext__load-more" id="auh-ext-load-more" type="button">
                <?php printf( esc_html__( 'Load more (+%d)', 'lionwood' ), $ext_total - 3 ); ?>
            </button>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<?php /* ══════════════════════════════════════════════════════
   SECTION 5 — CONTACT
══════════════════════════════════════════════════════ */ ?>
<?php
get_template_part( 'template-parts/partials/contact-section', null, [
    'title_top'      => get_field( 'contact_form_title_top',    'option' ) ?: '',
    'title_bottom'   => get_field( 'contact_form_title_bottom', 'option' ) ?: __( "Ready to work\ntogether?", 'lionwood' ),
    'description'    => get_field( 'contact_form_description',  'option' ) ?: '',
    'form_shortcode' => get_field( 'contact_form_shortcode',    'option' ) ?: '',
    'terms_link'     => get_field( 'contact_terms_link',        'option' ),
    'grid_labels'    => [
        1 => get_field( 'contact_grid_label_1', 'option' ) ?: '',
        2 => get_field( 'contact_grid_label_2', 'option' ) ?: '',
        3 => get_field( 'contact_grid_label_3', 'option' ) ?: '',
        4 => get_field( 'contact_grid_label_4', 'option' ) ?: '',
        5 => get_field( 'contact_grid_label_5', 'option' ) ?: '',
    ],
    'decor_enabled'  => true,
    'decor_color'    => '#F7F7F7',
] );
?>

<?php get_footer(); ?>
