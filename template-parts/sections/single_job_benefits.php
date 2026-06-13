<?php
/**
 * Block: Single Job Benefits
 * Slug: acf/single-job-benefits
 *
 * Dark bg, two-line heading + description, 4-col benefits grid.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: 'What You';
$title_bottom = get_field( 'title_bottom' ) ?: 'Will Get';
$description  = get_field( 'description' )  ?: '';
$desc_out     = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';
$benefits     = get_field( 'benefits' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="sjb-section"
    style="
        --sjb-pt: <?php echo $pt; ?>px;
        --sjb-pb: <?php echo $pb; ?>px;
        --sjb-pt-mob: <?php echo $pt_mob; ?>px;
        --sjb-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sjb-section__container">

        <?php /* ── Header row: heading + description ── */ ?>
        <div class="sjb-header">
            <div class="sjb-heading">
                <span class="sjb-heading__top"><?php echo esc_html( $title_top ); ?></span>
                <span class="sjb-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
            </div>
            <?php if ( $desc_out ) : ?>
                <p class="sjb-description"><?php echo $desc_out; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Benefits grid ── */ ?>
        <?php if ( ! empty( $benefits ) ) : ?>
            <div class="sjb-grid">
                <?php foreach ( $benefits as $benefit ) :
                    $icon        = $benefit['icon']        ?? null;
                    $title       = esc_html( $benefit['title']       ?? '' );
                    $description = esc_html( mb_substr( $benefit['description'] ?? '', 0, 120 ) );
                ?>
                    <div class="sjb-card">
                        <div class="sjb-card__icon">
                            <?php if ( $icon ) : ?>
                                <img
                                    src="<?php echo esc_url( $icon['url'] ); ?>"
                                    alt=""
                                    width="26"
                                    height="26"
                                    loading="lazy"
                                >
                            <?php endif; ?>
                        </div>
                        <?php if ( $title ) : ?>
                            <p class="sjb-card__title"><?php echo $title; ?></p>
                        <?php endif; ?>
                        <?php if ( $description ) : ?>
                            <p class="sjb-card__desc"><?php echo $description; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
