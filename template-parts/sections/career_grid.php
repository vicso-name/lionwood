<?php
/**
 * Block: Career Grid
 * Slug: acf/career-grid
 *
 * Heading + 3-col card grid from career CPT.
 * All posts rendered in HTML, first 6 visible, rest hidden.
 * Load More toggles visibility via JS — no AJAX needed.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: 'Current';
$title_bottom = get_field( 'title_bottom' ) ?: 'Openings at Lionwood';

// Count label: auto from CPT or manual override
$count_override = get_field( 'count_override' );
$career_count   = $count_override ?: ( wp_count_posts( 'career' )->publish ?? 0 );
$count_label    = sprintf( '(%d open positions)', $career_count );

// Source: relationship or query all
$source   = get_field( 'source' ) ?: 'query';
$selected = get_field( 'selected_posts' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

// Build posts array
if ( $source === 'selected' && ! empty( $selected ) ) {
    $posts = $selected;
} else {
    $posts = get_posts( [
        'post_type'      => 'career',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

// SVGs
$chevron_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 13L11 8L6 3" stroke="#111319" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$fire_svg    = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true"><path d="M11.0373 7C10.8936 6.8125 10.7186 6.65 10.5561 6.4875C10.1373 6.1125 9.66235 5.84375 9.26235 5.45C8.3311 4.5375 8.12485 3.03125 8.7186 1.875C8.12485 2.01875 7.6061 2.34375 7.16235 2.7C5.5436 4 4.9061 6.29375 5.6686 8.2625C5.6936 8.325 5.7186 8.3875 5.7186 8.46875C5.7186 8.60625 5.62485 8.73125 5.49985 8.78125C5.3561 8.84375 5.2061 8.80625 5.08735 8.70625C5.05188 8.67654 5.02221 8.64051 4.99985 8.6C4.2936 7.70625 4.1811 6.425 4.6561 5.4C3.61235 6.25 3.0436 7.6875 3.12485 9.04375C3.16235 9.35625 3.19985 9.66875 3.3061 9.98125C3.3936 10.3562 3.56235 10.7313 3.74985 11.0625C4.42485 12.1438 5.5936 12.9187 6.84985 13.075C8.18735 13.2437 9.6186 13 10.6436 12.075C11.7873 11.0375 12.1873 9.375 11.5998 7.95L11.5186 7.7875C11.3873 7.5 11.0373 7 11.0373 7ZM9.06235 10.9375C8.88735 11.0875 8.59985 11.25 8.37485 11.3125C7.67485 11.5625 6.97485 11.2125 6.56235 10.8C7.3061 10.625 7.74985 10.075 7.8811 9.51875C7.98735 9.01875 7.78735 8.60625 7.7061 8.125C7.6311 7.6625 7.6436 7.26875 7.81235 6.8375C7.9311 7.075 8.0561 7.3125 8.2061 7.5C8.68735 8.125 9.4436 8.4 9.6061 9.25C9.6311 9.3375 9.6436 9.425 9.6436 9.51875C9.66235 10.0312 9.43735 10.5938 9.06235 10.9375Z" fill="#F7F7F7"/></svg>';
$arrow_svg   = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true"><path d="M3.43547 9.56308L9.56372 3.43482M4.96753 3.43482L9.56372 3.43482L9.56372 8.03101" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$per_page = 6;
?>

<section
    class="cg-section"
    style="
        --cg-pt: <?php echo $pt; ?>px;
        --cg-pb: <?php echo $pb; ?>px;
        --cg-pt-mob: <?php echo $pt_mob; ?>px;
        --cg-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cg-section__container">

        <?php /* ── Heading row ── */ ?>
        <div class="cg-header">
            <h2 class="cg-heading">
                <span class="cg-heading__top"><?php echo esc_html( $title_top ); ?></span>
                <span class="cg-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </h2>
            <span class="cg-count"><?php echo esc_html( $count_label ); ?></span>
        </div>

        <?php if ( ! empty( $posts ) ) : ?>

            <?php /* ── Cards grid ── */ ?>
            <div class="cg-grid" data-cg-grid>
                <?php foreach ( $posts as $idx => $post ) :
                    $post_id  = is_object( $post ) ? $post->ID : $post;
                    $post_obj = is_object( $post ) ? $post : get_post( $post_id );
                    if ( ! $post_obj ) continue;

                    $title       = esc_html( $post_obj->post_title );
                    $permalink   = get_permalink( $post_id );
                    $is_hot      = get_field( 'is_hot',      $post_id );
                    $location    = get_field( 'location',    $post_id );
                    $flag        = get_field( 'location_flag', $post_id );
                    $emp_type    = get_field( 'employment_type', $post_id );
                    $domain      = get_field( 'domain',      $post_id );
                    $short_desc  = get_field( 'short_description', $post_id );
                    $short_out   = $short_desc ? wp_kses_post( $short_desc ) : '';

                    $hidden_class = $idx >= $per_page ? ' cg-card--hidden' : '';
                ?>
                <article class="cg-card<?php echo $hidden_class; ?>" data-cg-card>

                    <?php /* ── Title row ── */ ?>
                    <div class="cg-card__head">
                        <h3 class="cg-card__title"><?php echo $title; ?></h3>
                        <?php if ( $is_hot ) : ?>
                            <span class="cg-card__hot">
                                HOT <?php echo $fire_svg; ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php /* ── Meta items ── */ ?>
                    <ul class="cg-card__meta">

                        <?php if ( $location ) : ?>
                            <li class="cg-card__meta-item">
                                <span class="cg-card__meta-left">
                                    <?php echo $chevron_svg; ?>
                                    <?php esc_html_e( 'Project Location', 'lionwood' ); ?>
                                </span>
                                <span class="cg-card__meta-right">
                                    <?php echo esc_html( $location ); ?>
                                    <?php if ( $flag ) : ?>
                                        <img
                                            src="<?php echo esc_url( $flag['url'] ); ?>"
                                            alt="<?php echo esc_attr( $flag['alt'] ); ?>"
                                            width="20"
                                            height="14"
                                            loading="lazy"
                                        >
                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endif; ?>

                        <?php if ( $emp_type ) : ?>
                            <li class="cg-card__meta-item">
                                <span class="cg-card__meta-left">
                                    <?php echo $chevron_svg; ?>
                                    <?php esc_html_e( 'Employment Type', 'lionwood' ); ?>
                                </span>
                                <span class="cg-card__meta-right"><?php echo esc_html( $emp_type ); ?></span>
                            </li>
                        <?php endif; ?>

                        <?php if ( $domain ) : ?>
                            <li class="cg-card__meta-item">
                                <span class="cg-card__meta-left">
                                    <?php echo $chevron_svg; ?>
                                    <?php esc_html_e( 'Domain:', 'lionwood' ); ?>
                                </span>
                                <span class="cg-card__meta-right"><?php echo esc_html( $domain ); ?></span>
                            </li>
                        <?php endif; ?>

                    </ul>

                    <?php /* ── Short description ── */ ?>
                    <?php if ( $short_out ) : ?>
                        <div class="cg-card__desc"><?php echo $short_out; ?></div>
                    <?php endif; ?>

                    <?php /* ── View now link ── */ ?>
                    <a class="cg-card__link" href="<?php echo esc_url( $permalink ); ?>">
                        <span><?php esc_html_e( 'View now', 'lionwood' ); ?></span>
                        <span class="cg-card__link-icon"><?php echo $arrow_svg; ?></span>
                    </a>

                </article>
                <?php endforeach; ?>
            </div>

            <?php /* ── Load More ── */ ?>
            <?php if ( count( $posts ) > $per_page ) : ?>
                <div class="cg-loadmore-wrap">
                    <button class="cg-loadmore" data-cg-more data-per-page="<?php echo $per_page; ?>">
                        <?php esc_html_e( 'Load More', 'lionwood' ); ?>
                    </button>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div><!-- .cg-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
