<?php
/**
 * Shared partial: Cases listing grid with filter pills
 *
 * Used by archive-case_study.php, taxonomy-case_study_category.php,
 * taxonomy-case_study_service.php.
 *
 * Differences from choose_cases_grid.php:
 *  - Pills are <a> links → navigation, not AJAX filter
 *  - Active pill/tab resolved from get_queried_object() by PHP
 *  - data-active-taxonomy + data-active-term-id on .ccg-grid for Load More AJAX
 *
 * Optional $args:
 *  'marquee_text' (string) — overrides default marquee label
 */

defined( 'ABSPATH' ) || exit;

// ── Context detection ──────────────────────────────────────────────────────────
$is_industry  = is_tax( 'case_study_category' );
$is_service   = is_tax( 'case_study_service' );
$current_term = ( $is_industry || $is_service ) ? get_queried_object() : null;
$current_tax  = $is_industry ? 'case_study_category'
              : ( $is_service ? 'case_study_service' : '' );

// ── Initial query ──────────────────────────────────────────────────────────────
$per_page   = 6;
$query_args = [
    'post_type'      => 'case_study',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ( $current_term instanceof WP_Term ) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => $current_tax,
            'field'    => 'term_id',
            'terms'    => $current_term->term_id,
            'operator' => 'IN',
        ],
    ];
}

$query    = new WP_Query( $query_args );
$cases    = $query->posts;
$total    = $query->found_posts;
$has_more = $total > $per_page;
wp_reset_postdata();

// ── Terms for pills ───────────────────────────────────────────────────────────
$industry_terms = get_terms( [ 'taxonomy' => 'case_study_category', 'hide_empty' => true ] );
$service_terms  = get_terms( [ 'taxonomy' => 'case_study_service',  'hide_empty' => true ] );
$industry_terms = ( $industry_terms && ! is_wp_error( $industry_terms ) ) ? $industry_terms : [];
$service_terms  = ( $service_terms  && ! is_wp_error( $service_terms )  ) ? $service_terms  : [];

// ── Active state ───────────────────────────────────────────────────────────────
$active_tab     = $is_service ? 'services' : 'industries';
$active_term_id = $current_term ? $current_term->term_id : 0;

// ── Padding + marquee + decor from $args (populated by archive templates from Options Page) ────
$pt            = absint( $args['padding_top']          ?? 100 );
$pb            = absint( $args['padding_bottom']       ?? 100 );
$pt_mob        = absint( $args['padding_top_mob']      ?? 70 );
$pb_mob        = absint( $args['padding_bottom_mob']   ?? 70 );
$marquee_text  = esc_html( $args['marquee_text']       ?? __( 'Successful Products', 'lionwood' ) );
$decor_enabled = $args['decor_bottom_enabled']         ?? false;
$decor_color   = $args['decor_bottom_color']           ?? '#ffffff';
?>

<section class="ccg-section" style="--ccg-pt:<?php echo $pt; ?>px;--ccg-pb:<?php echo $pb; ?>px;--ccg-pt-mob:<?php echo $pt_mob; ?>px;--ccg-pb-mob:<?php echo $pb_mob; ?>px;">

    <?php /* ── Marquee ─────────────────────────────────────────────────── */ ?>
    <div class="ccg-marquee" aria-hidden="true">
        <div class="ccg-marquee__track">
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
                <span class="ccg-marquee__item">— <?php echo $marquee_text; ?></span>
            <?php endfor; ?>
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
                <span class="ccg-marquee__item">— <?php echo $marquee_text; ?></span>
            <?php endfor; ?>
        </div>
    </div>

    <div class="ccg-section__container">

        <?php /* ── Filter tabs (show/hide pill groups — no AJAX) ──────── */ ?>
        <div class="ccg-filter" data-ccg-filter>

            <div class="ccg-tabs" role="tablist">
                <button
                    class="ccg-tab<?php echo $active_tab === 'industries' ? ' is-active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $active_tab === 'industries' ? 'true' : 'false'; ?>"
                    data-tab="industries"
                    data-taxonomy="case_study_category"
                >
                    <?php esc_html_e( 'Industries', 'lionwood' ); ?>
                    <span class="ccg-tab__plus" aria-hidden="true">+</span>
                </button>
                <button
                    class="ccg-tab<?php echo $active_tab === 'services' ? ' is-active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $active_tab === 'services' ? 'true' : 'false'; ?>"
                    data-tab="services"
                    data-taxonomy="case_study_service"
                >
                    <?php esc_html_e( 'Services', 'lionwood' ); ?>
                    <span class="ccg-tab__plus" aria-hidden="true">+</span>
                </button>
            </div>
            <div class="ccg-tabs__border" aria-hidden="true"></div>

            <?php /* Pills — Industries */ ?>
            <?php if ( ! empty( $industry_terms ) ) : ?>
                <div
                    class="ccg-pills<?php echo $active_tab !== 'industries' ? ' ccg-pills--hidden' : ''; ?>"
                    data-pills="industries"
                >
                    <?php foreach ( $industry_terms as $term ) :
                        $is_active_pill = ( $is_industry && (int) $term->term_id === $active_term_id );
                        $term_url       = get_term_link( $term );
                    ?>
                        <a
                            class="ccg-pill<?php echo $is_active_pill ? ' is-active' : ''; ?>"
                            href="<?php echo esc_url( is_wp_error( $term_url ) ? '#' : $term_url ); ?>"
                            data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
                            data-taxonomy="case_study_category"
                            <?php echo $is_active_pill ? 'aria-current="page"' : ''; ?>
                        ><?php echo esc_html( $term->name ); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php /* Pills — Services */ ?>
            <?php if ( ! empty( $service_terms ) ) : ?>
                <div
                    class="ccg-pills<?php echo $active_tab !== 'services' ? ' ccg-pills--hidden' : ''; ?>"
                    data-pills="services"
                >
                    <?php foreach ( $service_terms as $term ) :
                        $is_active_pill = ( $is_service && (int) $term->term_id === $active_term_id );
                        $term_url       = get_term_link( $term );
                    ?>
                        <a
                            class="ccg-pill<?php echo $is_active_pill ? ' is-active' : ''; ?>"
                            href="<?php echo esc_url( is_wp_error( $term_url ) ? '#' : $term_url ); ?>"
                            data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
                            data-taxonomy="case_study_service"
                            <?php echo $is_active_pill ? 'aria-current="page"' : ''; ?>
                        ><?php echo esc_html( $term->name ); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div><!-- .ccg-filter -->

        <?php /* ── Grid ────────────────────────────────────────────────── */ ?>
        <div
            class="ccg-grid"
            data-grid
            data-offset="<?php echo esc_attr( $per_page ); ?>"
            data-total="<?php echo esc_attr( $total ); ?>"
            data-per-page="<?php echo esc_attr( $per_page ); ?>"
            data-active-taxonomy="<?php echo esc_attr( $current_tax ); ?>"
            data-active-term-id="<?php echo esc_attr( $active_term_id ); ?>"
        >
            <?php foreach ( $cases as $case ) :
                get_template_part( 'template-parts/partials/case-card', null, [ 'case_id' => $case->ID ] );
            endforeach; ?>
        </div><!-- .ccg-grid -->

        <?php /* ── Load More ───────────────────────────────────────────── */ ?>
        <?php if ( $has_more ) : ?>
            <div class="ccg-loadmore-wrap" data-loadmore-wrap>
                <button
                    class="ccg-loadmore-btn"
                    data-loadmore
                    data-nonce="<?php echo esc_attr( wp_create_nonce( 'ccg_ajax' ) ); ?>"
                ><?php esc_html_e( 'Load More', 'lionwood' ); ?></button>
            </div>
        <?php endif; ?>

    </div><!-- .ccg-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
