<?php
/**
 * Block: Our Cases
 *
 * ACF block slug : acf/our-cases
 * Template file  : blocks/our-cases/our-cases.php
 */

defined( 'ABSPATH' ) || exit;

// ── Block fields ─────────────────────────────────────────────────────────────
$pt      = absint( get_field( 'padding_top' )        ?: 80 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 80 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 80 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 80 );

$title_top    = get_field( 'title_top' )    ?: __( 'Our Cases:', 'lionwood' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'Real Impact, Real Results', 'lionwood' );
$desc_raw     = get_field( 'description' );
$description  = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$cases         = get_field( 'cases' )           ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';
$link_raw     = get_field( 'all_cases_link' );
$link_url     = ! empty( $link_raw['url'] )    ? esc_url( $link_raw['url'] )    : '';
$link_label   = ! empty( $link_raw['title'] )  ? esc_html( $link_raw['title'] ) : __( 'All Cases', 'lionwood' );
$link_target  = ! empty( $link_raw['target'] ) ? $link_raw['target']             : '_self';

// ── Fallback chart icon SVG ───────────────────────────────────────────────────
$chart_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
    <path d="M1 11.3418L5.44144 6.90035L8.77047 10.2294L15 3.99985" stroke="#688D4B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M14.9988 7.78125V4.00125H11.2188" stroke="#688D4B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

// ── Allowed HTML for stat text ────────────────────────────────────────────────
$allowed_stat = [ 'strong' => [] ];
?>

<section
    class="oc-section"
    style="
        --oc-pt: <?php echo $pt; ?>px;
        --oc-pb: <?php echo $pb; ?>px;
        --oc-pt-mob: <?php echo $pt_mob; ?>px;
        --oc-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="oc-section__container">

        <?php /* ── Header ───────────────────────────────────────────────── */ ?>
        <div class="oc-section__header">
            <div class="oc-section__heading">
                <span class="oc-section__title-top"><?php echo esc_html( $title_top ); ?></span>
                <span class="oc-section__title-bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </div>
            <?php if ( $description ) : ?>
                <p class="oc-section__description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Cases grid ───────────────────────────────────────────── */ ?>
        <?php if ( ! empty( $cases ) ) : ?>
            <div class="oc-grid">
                <?php foreach ( $cases as $case ) :
                    $case_id   = $case->ID;
                    $permalink = esc_url( get_permalink( $case_id ) );
                    $title     = esc_html( get_the_title( $case_id ) );

                    // Featured image
                    $thumb_id  = get_post_thumbnail_id( $case_id );
                    $thumb     = $thumb_id ? wp_get_attachment_image_src( $thumb_id, 'large' ) : null;
                    $thumb_alt = $thumb_id ? esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: $title ) : $title;

                    // Industries taxonomy — first term = tag, rest = services line
                    $terms        = get_the_terms( $case_id, 'case_study_category' );
                    $primary_term = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0] : null;
                    $all_terms    = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms : [];

                    // Services text — if multiple terms join them, else fallback to ACF field
                    $services_text = get_field( 'services_text', $case_id ) ?: '';
                    if ( ! $services_text && count( $all_terms ) > 1 ) {
                        $services_text = implode( ', ', array_map( fn( $t ) => $t->name, $all_terms ) );
                    } elseif ( ! $services_text && $primary_term ) {
                        $services_text = $primary_term->name;
                    }

                    // Stats repeater
                    $stats   = get_field( 'stats', $case_id ) ?: [];

                    // CTA link
                    $cta_raw    = get_field( 'cta_link', $case_id );
                    $cta_url    = ! empty( $cta_raw['url'] )    ? esc_url( $cta_raw['url'] )    : $permalink;
                    $cta_label  = ! empty( $cta_raw['title'] )  ? esc_html( $cta_raw['title'] ) : '';
                    $cta_target = ! empty( $cta_raw['target'] ) ? $cta_raw['target']              : '_self';
                ?>
                <article class="oc-card">
                    <a class="oc-card__link" href="<?php echo $permalink; ?>" aria-label="<?php echo $title; ?>">

                        <?php /* Image */ ?>
                        <div class="oc-card__image-wrap">
                            <?php if ( $thumb ) : ?>
                                <img
                                    class="oc-card__image"
                                    src="<?php echo esc_url( $thumb[0] ); ?>"
                                    width="<?php echo esc_attr( $thumb[1] ); ?>"
                                    height="<?php echo esc_attr( $thumb[2] ); ?>"
                                    alt="<?php echo $thumb_alt; ?>"
                                    loading="lazy"
                                >
                            <?php endif; ?>

                            <?php if ( $primary_term ) : ?>
                                <span class="oc-card__tag">
                                    #<?php echo esc_html( $primary_term->name ); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php /* Body */ ?>
                        <div class="oc-card__body">

                            <h3 class="oc-card__title"><?php echo $title; ?></h3>

                            <?php if ( $services_text ) : ?>
                                <p class="oc-card__services"><?php echo esc_html( $services_text ); ?></p>
                            <?php endif; ?>

                            <?php if ( ! empty( $stats ) ) : ?>
                                <ul class="oc-card__stats">
                                    <?php foreach ( $stats as $stat ) :
                                        $stat_text = ! empty( $stat['text'] ) ? wp_kses( $stat['text'], $allowed_stat ) : '';
                                        $stat_icon = $stat['icon'] ?? null;
                                        if ( ! $stat_text ) continue;
                                    ?>
                                        <li class="oc-card__stat">
                                            <span class="oc-card__stat-icon" aria-hidden="true">
                                                <?php if ( $stat_icon ) : ?>
                                                    <img
                                                        src="<?php echo esc_url( $stat_icon['url'] ); ?>"
                                                        width="16" height="16"
                                                        alt=""
                                                        loading="lazy"
                                                    >
                                                <?php else : ?>
                                                    <?php echo $chart_icon; ?>
                                                <?php endif; ?>
                                            </span>
                                            <span class="oc-card__stat-text"><?php echo $stat_text; ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                        </div><!-- .oc-card__body -->

                    </a><!-- .oc-card__link -->
                </article>

                <?php endforeach; ?>
            </div><!-- .oc-grid -->
        <?php endif; ?>

        <?php /* ── All Cases CTA ─────────────────────────────────────────── */ ?>
        <?php if ( $link_url ) : ?>
            <div class="oc-section__cta">
                <a
                    class="oc-section__all-btn"
                    href="<?php echo $link_url; ?>"
                    target="<?php echo esc_attr( $link_target ); ?>"
                    <?php echo '_blank' === $link_target ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $link_label; ?></a>
            </div>
        <?php endif; ?>

    </div><!-- .oc-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
