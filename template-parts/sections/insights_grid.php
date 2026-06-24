<?php
/**
 * Block: Insights Grid
 *
 * ACF block slug : acf/insights-grid
 *
 * Tabs: Articles (post) | News (news) | Whitepapers (whitepaper)
 * Pills: category terms per active tab
 * Grid: server-rendered on load, AJAX on interaction
 * URL state: ?type=news&cat=5 (history.pushState on interaction)
 */

defined( 'ABSPATH' ) || exit;

// ── Padding ───────────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

// ── Heading ───────────────────────────────────────────────────────────────────
$title_top    = esc_html( get_field( 'title_top' )    ?: __( 'Latest', 'lionwood' ) );
$title_bottom = esc_html( get_field( 'title_bottom' ) ?: __( 'Insights & Articles', 'lionwood' ) );

// ── Tab labels (configurable) ─────────────────────────────────────────────────
$label_articles    = esc_html( get_field( 'label_articles' )    ?: __( 'Articles', 'lionwood' ) );
$label_news        = esc_html( get_field( 'label_news' )        ?: __( 'News', 'lionwood' ) );
$label_whitepapers = esc_html( get_field( 'label_whitepapers' ) ?: __( 'Whitepapers', 'lionwood' ) );

$per_page = absint( get_field( 'per_page' ) ?: 6 );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// ── Post type ↔ taxonomy map ──────────────────────────────────────────────────
$type_map = [
    'articles'    => [ 'post_type' => 'post',      'taxonomy' => 'category' ],
    'news'        => [ 'post_type' => 'news',       'taxonomy' => 'news_category' ],
    'whitepapers' => [ 'post_type' => 'whitepaper', 'taxonomy' => 'whitepaper_category' ],
];

$tabs = [
    'articles'    => $label_articles,
    'news'        => $label_news,
    'whitepapers' => $label_whitepapers,
];

// ── URL state — initial server-side render ────────────────────────────────────
$active_type   = sanitize_key( $_GET['type'] ?? 'articles' );
$active_cat_id = absint( $_GET['cat'] ?? 0 );

if ( ! isset( $type_map[ $active_type ] ) ) {
    $active_type = 'articles';
}
$active_config   = $type_map[ $active_type ];
$active_taxonomy = $active_config['taxonomy'];

// ── Initial WP_Query ──────────────────────────────────────────────────────────
$query_args = [
    'post_type'      => $active_config['post_type'],
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ( $active_cat_id ) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => $active_taxonomy,
            'field'    => 'term_id',
            'terms'    => [ $active_cat_id ],
            'operator' => 'IN',
        ],
    ];
}

$query    = new WP_Query( $query_args );
$posts    = $query->posts;
$total    = $query->found_posts;
$has_more = $total > $per_page;
wp_reset_postdata();

// ── Terms for pills (load all tabs upfront) ────────────────────────────────────
$all_terms = [];
foreach ( $type_map as $key => $cfg ) {
    $terms = get_terms( [ 'taxonomy' => $cfg['taxonomy'], 'hide_empty' => true ] );
    $all_terms[ $key ] = ( $terms && ! is_wp_error( $terms ) ) ? $terms : [];
}
?>

<section
    class="ig-section ia-section"
    style="
        --ia-pt: <?php echo $pt; ?>px;
        --ia-pb: <?php echo $pb; ?>px;
        --ia-pt-mob: <?php echo $pt_mob; ?>px;
        --ia-pb-mob: <?php echo $pb_mob; ?>px;
    "
    data-ig-section
    data-nonce="<?php echo esc_attr( wp_create_nonce( 'ig_ajax' ) ); ?>"
    data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
>
    <div class="ia-section__container ig-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────────── */ ?>
        <div class="ia-section__heading">
            <span class="ia-section__heading-top" aria-hidden="true"><?php echo $title_top; ?></span>
            <span class="ia-section__heading-bottom" aria-hidden="true"><?php echo $title_bottom; ?></span>
        </div>

        <?php /* ── Filter ──────────────────────────────────────────────────── */ ?>
        <div class="ig-filter" data-ig-filter>

            <?php /* Tabs */ ?>
            <div class="ccg-tabs" role="tablist">
                <?php foreach ( $tabs as $key => $label ) :
                    $is_active = ( $key === $active_type );
                ?>
                    <button
                        class="ccg-tab<?php echo $is_active ? ' is-active' : ''; ?>"
                        role="tab"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        data-tab="<?php echo esc_attr( $key ); ?>"
                    >
                        <?php echo $label; ?>
                        <span class="ccg-tab__plus" aria-hidden="true">+</span>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="ccg-tabs__border" aria-hidden="true"></div>

            <?php /* Pills per tab */ ?>
            <?php foreach ( $type_map as $key => $cfg ) :
                $is_active_group = ( $key === $active_type );
                $terms           = $all_terms[ $key ];
            ?>
                <?php if ( ! empty( $terms ) ) : ?>
                    <div
                        class="ccg-pills<?php echo ! $is_active_group ? ' ccg-pills--hidden' : ''; ?>"
                        data-pills="<?php echo esc_attr( $key ); ?>"
                    >
                        <?php foreach ( $terms as $term ) :
                            $is_active_pill = ( $is_active_group && $term->term_id === $active_cat_id );
                        ?>
                            <a
                                class="ccg-pill<?php echo $is_active_pill ? ' is-active' : ''; ?>"
                                href="#"
                                data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
                                data-type="<?php echo esc_attr( $key ); ?>"
                                <?php echo $is_active_pill ? 'aria-current="true"' : ''; ?>
                            ><?php echo esc_html( $term->name ); ?><span class="ccg-pill__count"><?php echo (int) $term->count; ?></span></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div><!-- .ig-filter -->

        <?php /* ── Grid ────────────────────────────────────────────────────── */ ?>
        <div
            class="ia-section__grid ig-grid"
            data-ig-grid
            data-type="<?php echo esc_attr( $active_type ); ?>"
            data-active-term-id="<?php echo esc_attr( $active_cat_id ); ?>"
            data-offset="<?php echo esc_attr( $per_page ); ?>"
            data-total="<?php echo esc_attr( $total ); ?>"
            data-per-page="<?php echo esc_attr( $per_page ); ?>"
        >
            <?php foreach ( $posts as $i => $post ) :
                get_template_part( 'template-parts/partials/insights-card', null, [
                    'post_id'  => $post->ID,
                    'featured' => ( $i === 0 ),
                ] );
            endforeach; ?>
        </div><!-- .ig-grid -->

        <?php /* ── Load More ───────────────────────────────────────────────── */ ?>
        <?php if ( $has_more ) : ?>
            <div class="ccg-loadmore-wrap" data-ig-loadmore-wrap>
                <button class="ccg-loadmore-btn" data-ig-loadmore>
                    <?php esc_html_e( 'Load More', 'lionwood' ); ?>
                </button>
            </div>
        <?php endif; ?>

    </div><!-- .ia-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
