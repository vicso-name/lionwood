<?php
/**
 * Block: Achievements by Years
 *
 * ACF block slug: acf/achievements-by-years
 *
 * Fields:
 *   padding_top / padding_bottom / padding_top_mob / padding_bottom_mob
 *   decor_bottom_enabled / decor_bottom_color
 *   awards_label, awards_title_1, awards_title_2, awards_items (repeater)
 *   certs_label,  certs_title_1,  certs_title_2,  certs_items  (repeater)
 *
 *   Repeater sub-fields: year (text), logo (image), title (text),
 *                        description (textarea), link (link, optional)
 */

defined( 'ABSPATH' ) || exit;

// ── Padding ───────────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )        ?: 160 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 250 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70  );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 180 );

$section_style = sprintf(
    '--aby-pt:%dpx; --aby-pb:%dpx; --aby-pt-mob:%dpx; --aby-pb-mob:%dpx;',
    $pt, $pb, $pt_mob, $pb_mob
);

// ── Tab labels ────────────────────────────────────────────────────────────────
$awards_label = esc_html( get_field( 'awards_label' ) ?: __( 'Awards', 'lionwood' ) );
$certs_label  = esc_html( get_field( 'certs_label' )  ?: __( 'Certifications', 'lionwood' ) );

// ── Awards data ───────────────────────────────────────────────────────────────
$awards_title_1 = esc_html( get_field( 'awards_title_1' ) ?: '' );
$awards_title_2 = esc_html( get_field( 'awards_title_2' ) ?: '' );
$awards_items   = get_field( 'awards_items' ) ?: [];

// ── Certifications data ───────────────────────────────────────────────────────
$certs_title_1 = esc_html( get_field( 'certs_title_1' ) ?: '' );
$certs_title_2 = esc_html( get_field( 'certs_title_2' ) ?: '' );
$certs_items   = get_field( 'certs_items' ) ?: [];

// ── Group items by year (newest first) ───────────────────────────────────────
if ( ! function_exists( 'aby_group_by_year' ) ) {
    function aby_group_by_year( array $items ): array {
        $grouped = [];
        foreach ( $items as $item ) {
            $year = trim( (string) ( $item['year'] ?? '' ) );
            if ( ! $year ) continue;
            $grouped[ $year ][] = $item;
        }
        krsort( $grouped );
        return $grouped;
    }
}

$awards_by_year = aby_group_by_year( $awards_items );
$certs_by_year  = aby_group_by_year( $certs_items );

// ── Panel definitions (makes foreach clean) ───────────────────────────────────
$panels = [
    [
        'id'      => 'aby-panel-awards',
        'title_1' => $awards_title_1,
        'title_2' => $awards_title_2,
        'by_year' => $awards_by_year,
        'hidden'  => false,
    ],
    [
        'id'      => 'aby-panel-certs',
        'title_1' => $certs_title_1,
        'title_2' => $certs_title_2,
        'by_year' => $certs_by_year,
        'hidden'  => true,
    ],
];
?>

<section class="aby-section" style="<?php echo esc_attr( $section_style ); ?>">
    <div class="aby-section__container">

        <?php /* ── Main tabs navigation ──────────────────────────────────── */ ?>
        <nav class="aby-tabs" aria-label="Achievement categories">
            <button class="aby-tab aby-tab--active"
                    data-target="aby-panel-awards"
                    role="tab" aria-selected="true">
                <?php echo esc_html( $awards_label ); ?>
            </button>
            <button class="aby-tab"
                    data-target="aby-panel-certs"
                    role="tab" aria-selected="false">
                <?php echo esc_html( $certs_label ); ?>
            </button>
        </nav>

        <?php /* ── Tab panels ───────────────────────────────────────────── */ ?>
        <?php foreach ( $panels as $panel ) : ?>

        <div class="aby-panel<?php echo $panel['hidden'] ? ' aby-panel--hidden' : ''; ?>"
             id="<?php echo esc_attr( $panel['id'] ); ?>"
             role="tabpanel"
             aria-hidden="<?php echo $panel['hidden'] ? 'true' : 'false'; ?>">

            <?php /* ── Panel header: heading + year pills ── */ ?>
            <div class="aby-panel__header">

                <div class="aby-heading">
                    <?php if ( $panel['title_1'] ) : ?>
                        <span class="aby-heading__line1"><?php echo esc_html( $panel['title_1'] ); ?></span>
                    <?php endif; ?>
                    <?php if ( $panel['title_2'] ) : ?>
                        <span class="aby-heading__line2"><?php echo esc_html( $panel['title_2'] ); ?></span>
                    <?php endif; ?>
                </div>

                <?php if ( ! empty( $panel['by_year'] ) ) : ?>
                <div class="aby-years" role="tablist">
                    <?php $yr_i = 0; foreach ( $panel['by_year'] as $year => $year_items ) : ?>
                        <button
                            class="aby-year-pill<?php echo $yr_i === 0 ? ' aby-year-pill--active' : ''; ?>"
                            data-year="<?php echo esc_attr( $year ); ?>"
                            role="tab"
                            aria-selected="<?php echo $yr_i === 0 ? 'true' : 'false'; ?>"
                        ><?php echo esc_html( $year ); ?><sup class="aby-year-pill__count"><?php echo count( $year_items ); ?></sup></button>
                    <?php $yr_i++; endforeach; ?>
                </div>
                <?php endif; ?>

            </div>

            <?php /* ── Year blocks ── */ ?>
            <?php $yr_i = 0; foreach ( $panel['by_year'] as $year => $year_items ) : ?>
            <div class="aby-year-block<?php echo $yr_i > 0 ? ' aby-year-block--hidden' : ''; ?>"
                 data-year-block="<?php echo esc_attr( $year ); ?>">

                <?php /* Item detail rows — all rendered, first visible */ ?>
                <?php foreach ( $year_items as $item_i => $item ) :
                    $logo        = $item['logo'] ?? [];
                    $logo_url    = ! empty( $logo['url'] )    ? esc_url( $logo['url'] )    : '';
                    $logo_alt    = ! empty( $logo['alt'] )    ? esc_attr( $logo['alt'] )   : '';
                    $title_r     = esc_html( $item['title']       ?? '' );
                    $desc        = esc_html( $item['description'] ?? '' );
                    $link        = $item['link'] ?? [];
                    $link_url    = ! empty( $link['url'] )    ? esc_url( $link['url'] )    : '';
                    $link_label  = ! empty( $link['title'] )  ? esc_html( $link['title'] ) : esc_html__( 'See More', 'lionwood' );
                    $link_target = ! empty( $link['target'] ) ? $link['target']             : '_self';
                    $link_rel    = '_blank' === $link_target  ? 'noopener noreferrer'        : '';
                ?>
                <div class="aby-item<?php echo $item_i > 0 ? ' aby-item--hidden' : ''; ?>"
                     data-item="<?php echo $item_i; ?>">
                    <div class="aby-item__row">

                        <div class="aby-item__logo-card">
                            <?php if ( $logo_url ) : ?>
                                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" loading="lazy">
                            <?php endif; ?>
                        </div>

                        <div class="aby-item__detail">
                            <?php if ( $title_r ) : ?>
                                <h3 class="aby-item__title"><?php echo esc_html( $title_r ); ?></h3>
                            <?php endif; ?>
                            <?php if ( $desc ) : ?>
                                <p class="aby-item__desc"><?php echo esc_html( $desc ); ?></p>
                            <?php endif; ?>
                            <?php if ( $link_url ) : ?>
                                <a class="aby-item__link"
                                   href="<?php echo esc_url( $link_url ); ?>"
                                   target="<?php echo esc_attr( $link_target ); ?>"
                                   <?php echo $link_rel ? 'rel="' . esc_attr( $link_rel ) . '"' : ''; ?>>
                                    <?php echo esc_html( $link_label ); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true"><path d="M5.92753 16.4891L16.4935 5.92318M8.56902 5.92318L16.4935 5.92318L16.4935 13.8477" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

                <?php /* Grid of all items for this year */ ?>
                <div class="aby-grid">
                    <?php foreach ( $year_items as $grid_i => $item ) :
                        $logo     = $item['logo'] ?? [];
                        $logo_url = ! empty( $logo['url'] ) ? esc_url( $logo['url'] ) : '';
                        $logo_alt = ! empty( $logo['alt'] ) ? esc_attr( $logo['alt'] ) : '';
                    ?>
                        <button class="aby-grid__cell<?php echo $grid_i === 0 ? ' aby-grid__cell--active' : ''; ?>"
                                data-item="<?php echo $grid_i; ?>"
                                aria-label="<?php echo esc_attr( $item['title'] ?? '' ); ?>">
                            <?php if ( $logo_url ) : ?>
                                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" loading="lazy">
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

            </div>
            <?php $yr_i++; endforeach; ?>

            <?php if ( empty( $panel['by_year'] ) ) : ?>
                <p class="aby-empty"><?php esc_html_e( 'No items yet.', 'lionwood' ); ?></p>
            <?php endif; ?>

        </div>

        <?php endforeach; ?>

    </div>
    <?php get_template_part( 'template-parts/partials/decor-bottom' ); ?>
</section>
