<?php
/**
 * Block: Solutions Section
 *
 * ACF block slug : acf/solutions-section
 * Template file  : blocks/solutions-section/solutions-section.php
 *
 * Layout: two columns
 *   Left  — 478px: heading + description + CTA
 *   Right — 463px: repeater of solution cards
 * Gap between columns: 260px
 * Vertical decorative line through center of right column
 */

defined( 'ABSPATH' ) || exit;

// ── Fields ───────────────────────────────────────────────────────────────────
$pt      = absint( get_field( 'padding_top' )        ?: 100 );
$pb      = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob  = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob  = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: __( 'Solutions', 'lionwood' );
$title_bottom_raw = get_field( 'title_bottom' );
$title_bottom = $title_bottom_raw
    ? wp_kses( $title_bottom_raw, [ 'br' => [] ] )
    : 'For Business<br>Growth';

$description  = get_field( 'description' ) ?: '';   // wysiwyg — already safe HTML

$cta_text_raw = get_field( 'cta_text' ) ?: '';
$cta_link_raw = get_field( 'cta_link' );
$cta_url      = ! empty( $cta_link_raw['url'] )    ? esc_url( $cta_link_raw['url'] )    : '';
$cta_label    = ! empty( $cta_link_raw['title'] )  ? esc_html( $cta_link_raw['title'] ) : __( 'Read More', 'lionwood' );
$cta_target   = ! empty( $cta_link_raw['target'] ) ? $cta_link_raw['target']             : '_self';

$items = get_field( 'items' ) ?: [];
?>

<section
    class="sol-section"
    style="
        --sol-pt: <?php echo $pt; ?>px;
        --sol-pb: <?php echo $pb; ?>px;
        --sol-pt-mob: <?php echo $pt_mob; ?>px;
        --sol-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sol-section__container">
        <div class="sol-section__body">

            <?php /* ── LEFT COLUMN ─────────────────────────────────────── */ ?>
            <div class="sol-col sol-col--left">

                <div class="sol-heading">
                    <span class="sol-heading__top"><?php echo esc_html( $title_top ); ?></span>
                    <span class="sol-heading__bottom"><?php echo $title_bottom; ?></span>
                </div>

                <div class="sol-content-outer">
                <div class="sol-content-wrap">
                    <?php if ( $description ) : ?>
                        <div class="sol-description">
                            <?php echo wp_kses_post( $description ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $cta_text_raw || $cta_url ) : ?>
                        <div class="sol-cta">
                            <?php if ( $cta_text_raw ) : ?>
                                <p class="sol-cta__text"><?php echo esc_html( $cta_text_raw ); ?></p>
                            <?php endif; ?>
                            <?php if ( $cta_url ) : ?>
                                <a
                                    class="sol-cta__btn"
                                    href="<?php echo $cta_url; ?>"
                                    target="<?php echo esc_attr( $cta_target ); ?>"
                                    <?php echo '_blank' === $cta_target ? 'rel="noopener noreferrer"' : ''; ?>
                                ><?php echo $cta_label; ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div><!-- .sol-content-wrap -->
                </div><!-- .sol-content-outer -->

            </div><!-- .sol-col--left -->

            <?php /* ── RIGHT COLUMN ────────────────────────────────────── */ ?>
            <?php if ( ! empty( $items ) ) : ?>
                <div class="sol-col sol-col--right">

                    <?php /* Vertical decorative line through center */ ?>
                    <div class="sol-col__line" aria-hidden="true"></div>

                    <div class="sol-cards">
                        <?php foreach ( $items as $item ) :
                            $icon     = $item['icon']        ?? null;
                            $ititle   = esc_html( $item['title']       ?? '' );
                            $idesc    = $item['description'] ?? '';
                            $idesc_out = $idesc ? wp_kses( $idesc, [ 'br' => [], 'strong' => [] ] ) : '';
                        ?>
                            <div class="sol-card">
                                <?php if ( $icon ) : ?>
                                    <div class="sol-card__icon">
                                        <img
                                            src="<?php echo esc_url( $icon['url'] ); ?>"
                                            alt="<?php echo esc_attr( $icon['alt'] ?: $ititle ); ?>"
                                            width="60"
                                            height="60"
                                            loading="lazy"
                                        >
                                    </div>
                                <?php endif; ?>

                                <?php if ( $ititle ) : ?>
                                    <h3 class="sol-card__title"><?php echo $ititle; ?></h3>
                                <?php endif; ?>

                                <?php if ( $idesc_out ) : ?>
                                    <p class="sol-card__desc"><?php echo $idesc_out; ?></p>
                                <?php endif; ?>
                            </div><!-- .sol-card -->
                        <?php endforeach; ?>
                    </div><!-- .sol-cards -->

                </div><!-- .sol-col--right -->
            <?php endif; ?>

        </div><!-- .sol-section__body -->
    </div><!-- .sol-section__container -->
</section>
