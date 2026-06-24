<?php
/**
 * Block: Choose Cases Grid
 *
 * ACF block slug : acf/choose-cases-grid
 *
 * - CSS marquee at top
 * - Two tabs: Industries / Services
 * - Filter pills from taxonomies (case_study_category / case_study_service)
 * - 3-col grid, 6 per page, AJAX load more
 * - AJAX filter resets grid and updates load more state
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$marquee_text  = esc_html( get_field( 'marquee_text' ) ?: __( 'Successful Products', 'lionwood' ) );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$per_page = 6;

// Load initial cases (no filter)
$initial_query = new WP_Query( [
    'post_type'      => 'case_study',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

$initial_cases = $initial_query->posts;
$total_cases   = $initial_query->found_posts;
$has_more      = $total_cases > $per_page;

// Get all taxonomy terms for filter pills
$industry_terms = get_terms( [
    'taxonomy'   => 'case_study_category',
    'hide_empty' => true,
] );

$service_terms = get_terms( [
    'taxonomy'   => 'case_study_service',
    'hide_empty' => true,
] );

$block_id = 'ccg-' . uniqid();
?>

<section
    class="ccg-section"
    id="<?php echo esc_attr( $block_id ); ?>"
    style="
        --ccg-pt: <?php echo $pt; ?>px;
        --ccg-pb: <?php echo $pb; ?>px;
        --ccg-pt-mob: <?php echo $pt_mob; ?>px;
        --ccg-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
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

        <?php /* ── Filter tabs ─────────────────────────────────────────── */ ?>
        <div class="ccg-filter" data-ccg-filter>

            <?php /* Tab switcher */ ?>
            <div class="ccg-tabs" role="tablist">
                <button
                    class="ccg-tab is-active"
                    role="tab"
                    aria-selected="true"
                    data-tab="industries"
                    data-taxonomy="case_study_category"
                >
                    <?php esc_html_e( 'Industries', 'lionwood' ); ?>
                    <span class="ccg-tab__plus" aria-hidden="true">+</span>
                </button>
                <button
                    class="ccg-tab"
                    role="tab"
                    aria-selected="false"
                    data-tab="services"
                    data-taxonomy="case_study_service"
                >
                    <?php esc_html_e( 'Services', 'lionwood' ); ?>
                    <span class="ccg-tab__plus" aria-hidden="true">+</span>
                </button>
            </div>
            <div class="ccg-tabs__border" aria-hidden="true"></div>

            <?php /* Filter pills — Industries */ ?>
            <?php if ( ! empty( $industry_terms ) && ! is_wp_error( $industry_terms ) ) : ?>
                <div class="ccg-pills" data-pills="industries">
                    <?php foreach ( $industry_terms as $term ) :
                        $term_url = get_term_link( $term );
                    ?>
                        <a
                            class="ccg-pill"
                            href="<?php echo esc_url( is_wp_error( $term_url ) ? '#' : $term_url ); ?>"
                            data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
                            data-taxonomy="case_study_category"
                        ><?php echo esc_html( $term->name ); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php /* Filter pills — Services */ ?>
            <?php if ( ! empty( $service_terms ) && ! is_wp_error( $service_terms ) ) : ?>
                <div class="ccg-pills ccg-pills--hidden" data-pills="services">
                    <?php foreach ( $service_terms as $term ) :
                        $term_url = get_term_link( $term );
                    ?>
                        <a
                            class="ccg-pill"
                            href="<?php echo esc_url( is_wp_error( $term_url ) ? '#' : $term_url ); ?>"
                            data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
                            data-taxonomy="case_study_service"
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
            data-total="<?php echo esc_attr( $total_cases ); ?>"
            data-per-page="<?php echo esc_attr( $per_page ); ?>"
            data-active-taxonomy=""
            data-active-term=""
        >
            <?php foreach ( $initial_cases as $case ) :
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
