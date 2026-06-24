<?php
/**
 * Block: Solutions Grid
 * Slug: acf/solutions-grid
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top     = esc_html( get_field( 'title_top' )    ?: '' );
$title_bottom  = esc_html( get_field( 'title_bottom' ) ?: '' );
$marquee_text  = esc_html( get_field( 'marquee_text' ) ?: __( 'Solutions', 'lionwood' ) );
$per_page      = absint( get_field( 'per_page' ) ?: 6 );
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

// ── Active category from URL ──────────────────────────────────────────────────
$active_term_id = absint( $_GET['cat'] ?? 0 );

// ── Initial query ─────────────────────────────────────────────────────────────
$query_args = [
    'post_type'      => 'solution',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ( $active_term_id ) {
    $query_args['tax_query'] = [ [
        'taxonomy' => 'solution_category',
        'field'    => 'term_id',
        'terms'    => [ $active_term_id ],
        'operator' => 'IN',
    ] ];
}

$query    = new WP_Query( $query_args );
$posts    = $query->posts;
$total    = $query->found_posts;
$has_more = $total > $per_page;
wp_reset_postdata();

// ── Category pills ────────────────────────────────────────────────────────────
$terms = get_terms( [ 'taxonomy' => 'solution_category', 'hide_empty' => true ] );
$terms = ( $terms && ! is_wp_error( $terms ) ) ? $terms : [];
?>

<section
    class="sg-section"
    style="
        --sg-pt: <?php echo $pt; ?>px;
        --sg-pb: <?php echo $pb; ?>px;
        --sg-pt-mob: <?php echo $pt_mob; ?>px;
        --sg-pb-mob: <?php echo $pb_mob; ?>px;
    "
    data-sg-section
    data-nonce="<?php echo esc_attr( wp_create_nonce( 'sg_ajax' ) ); ?>"
    data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
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

    <div class="sg-section__container">

        <?php /* ── Heading ────────────────────────────────────────────────── */ ?>
        <?php if ( $title_top || $title_bottom ) : ?>
        <div class="ia-section__heading">
            <?php if ( $title_top ) : ?>
                <span class="ia-section__heading-top" aria-hidden="true"><?php echo $title_top; ?></span>
            <?php endif; ?>
            <?php if ( $title_bottom ) : ?>
                <span class="ia-section__heading-bottom" aria-hidden="true"><?php echo $title_bottom; ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php /* ── Filter ─────────────────────────────────────────────────── */ ?>
        <?php if ( ! empty( $terms ) ) : ?>
        <div class="sg-filter" data-sg-filter>

            <span class="sg-filter__label"><?php esc_html_e( 'CATEGORIES', 'lionwood' ); ?></span>
            <div class="sg-filter__border" aria-hidden="true"></div>

            <div class="sg-pills ccg-pills" data-sg-pills>
                <?php foreach ( $terms as $term ) :
                    $is_active = ( $term->term_id === $active_term_id );
                ?>
                    <a
                        class="ccg-pill<?php echo $is_active ? ' is-active' : ''; ?>"
                        href="#"
                        data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
                        <?php echo $is_active ? 'aria-current="true"' : ''; ?>
                    ><?php echo esc_html( $term->name ); ?></a>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endif; ?>

        <?php /* ── Grid ───────────────────────────────────────────────────── */ ?>
        <div
            class="sg-grid"
            data-sg-grid
            data-active-term-id="<?php echo esc_attr( $active_term_id ); ?>"
            data-offset="<?php echo esc_attr( $per_page ); ?>"
            data-total="<?php echo esc_attr( $total ); ?>"
            data-per-page="<?php echo esc_attr( $per_page ); ?>"
        >
            <?php foreach ( $posts as $post ) :
                get_template_part( 'template-parts/partials/solution-card', null, [ 'post_id' => $post->ID ] );
            endforeach; ?>
        </div>

        <?php /* ── Load More ─────────────────────────────────────────────── */ ?>
        <?php if ( $has_more ) : ?>
        <div class="ccg-loadmore-wrap" data-sg-loadmore-wrap>
            <button class="ccg-loadmore-btn" data-sg-loadmore>
                <?php esc_html_e( 'Load More', 'lionwood' ); ?>
            </button>
        </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
