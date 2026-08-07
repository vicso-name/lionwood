<?php
/**
 * Block: Sub Solutions Section
 *
 * ACF block slug : acf/sub-solutions
 * Desktop: 3-column grid. Mobile: Swiper slider with numeric counter.
 */

defined( 'ABSPATH' ) || exit;

$pt           = absint( get_field( 'padding_top' )        ?: 100 );
$pb           = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob       = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob       = absint( get_field( 'padding_bottom_mob' ) ?: 140 );
$title_line_1 = esc_html( get_field( 'title_line_1' ) ?: __( 'Explore our', 'lionwood' ) );
$title_line_2 = esc_html( get_field( 'title_line_2' ) ?: __( 'Sub-Solutions', 'lionwood' ) );
$cards        = get_field( 'cards' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
if ( $decor_enabled === null ) $decor_enabled = true;
$decor_color  = get_field( 'decor_bottom_color' ) ?: '#E9E9E9';
$total        = count( $cards );

$check_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" aria-hidden="true">
  <path d="M19.5781 8.31921C19.7172 8.45986 19.7953 8.6506 19.7953 8.84948C19.7953 9.04836 19.7172 9.23909 19.5781 9.37974L12.1609 16.88C12.0218 17.0206 11.8332 17.0996 11.6365 17.0996C11.4399 17.0996 11.2512 17.0206 11.1121 16.88L7.40352 13.1299C7.26841 12.9884 7.19365 12.799 7.19534 12.6023C7.19703 12.4057 7.27504 12.2175 7.41256 12.0785C7.55008 11.9394 7.73611 11.8605 7.93059 11.8588C8.12507 11.8571 8.31243 11.9327 8.45232 12.0693L11.6365 15.2892L18.5294 8.31921C18.6684 8.1786 18.8571 8.09961 19.0537 8.09961C19.2504 8.09961 19.4391 8.1786 19.5781 8.31921Z" fill="#F7F7F7"/>
</svg>';

// Render a single card — used in both grid and swiper
function ssol_render_card( array $card, string $check_icon ): void {
    $card_title = esc_html( $card['card_title'] ?? '' );
    $card_desc  = esc_html( $card['card_description'] ?? '' );
    $best_items = $card['best_for_items'] ?? [];
    ?>
    <article class="ssol-card">

        <div class="ssol-card__icon" aria-hidden="true">
            <?php echo $check_icon; ?>
        </div>

        <div class="ssol-card__body">
            <?php if ( $card_title ) : ?>
                <h3 class="ssol-card__title"><?php echo $card_title; ?></h3>
            <?php endif; ?>

            <?php if ( $card_desc ) : ?>
                <p class="ssol-card__description"><?php echo $card_desc; ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $best_items ) ) : ?>
                <div class="ssol-card__badge">
                    <span class="ssol-card__badge-label"><?php esc_html_e( 'Best for:', 'lionwood' ); ?></span>
                    <ul class="ssol-card__badge-list">
                        <?php foreach ( $best_items as $bi ) :
                            $bi_text = esc_html( $bi['text'] ?? '' );
                            if ( ! $bi_text ) continue;
                        ?>
                            <li class="ssol-card__badge-item">
                                <span class="ssol-card__badge-dot" aria-hidden="true"></span>
                                <span><?php echo $bi_text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

    </article>
    <?php
}
?>

<section
    class="ssol-section"
    style="
        --ssol-pt: <?php echo $pt; ?>px;
        --ssol-pb: <?php echo $pb; ?>px;
        --ssol-pt-mob: <?php echo $pt_mob; ?>px;
        --ssol-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ssol-section__container">

        <div class="ssol-heading">
            <span class="ssol-heading__line-1"><?php echo $title_line_1; ?></span>
            <span class="ssol-heading__line-2"><?php echo $title_line_2; ?></span>
        </div>

        <?php if ( ! empty( $cards ) ) : ?>

            <?php /* ── Desktop grid ──────────────────────────────────── */ ?>
            <div class="ssol-grid">
                <?php foreach ( $cards as $card ) :
                    ssol_render_card( $card, $check_icon );
                endforeach; ?>
            </div>

            <?php /* ── Mobile Swiper ─────────────────────────────────── */ ?>
            <div class="ssol-swiper-wrap">
                <div class="swiper ssol-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ( $cards as $card ) : ?>
                            <div class="swiper-slide ssol-slide">
                                <?php ssol_render_card( $card, $check_icon ); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="ssol-counter" aria-live="polite">
                    <span class="ssol-counter__current">01</span>
                    <span class="ssol-counter__sep">/</span>
                    <span class="ssol-counter__total"><?php echo str_pad( $total, 2, '0', STR_PAD_LEFT ); ?></span>
                </div>
            </div>

        <?php endif; ?>

    </div><!-- .ssol-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
