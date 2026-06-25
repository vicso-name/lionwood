<?php
/**
 * Block: Solutions For Who
 *
 * ACF block slug : acf/solutions-for-who
 * Gray background, marquee, two-column card repeater with Problem/Solution sub-cards
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 140 );
$marquee_text  = esc_html( get_field( 'marquee_text' )     ?: __( 'For Who', 'lionwood' ) );
$cards         = get_field( 'cards' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
if ( $decor_enabled === null ) $decor_enabled = true;
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#111319';

$icon_x = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
  <path d="M6.67622 7.44423L3.95399 10.1664C3.85214 10.2683 3.72251 10.3192 3.5651 10.3192C3.4077 10.3192 3.27807 10.2683 3.17622 10.1664C3.07436 10.0646 3.02344 9.93497 3.02344 9.77756C3.02344 9.62015 3.07436 9.49052 3.17622 9.38867L5.89844 6.66645L3.17622 3.94423C3.07436 3.84238 3.02344 3.71275 3.02344 3.55534C3.02344 3.39793 3.07436 3.2683 3.17622 3.16645C3.27807 3.0646 3.4077 3.01367 3.5651 3.01367C3.72251 3.01367 3.85214 3.0646 3.95399 3.16645L6.67622 5.88867L9.39844 3.16645C9.50029 3.0646 9.62992 3.01367 9.78733 3.01367C9.94473 3.01367 10.0744 3.0646 10.1762 3.16645C10.2781 3.2683 10.329 3.39793 10.329 3.55534C10.329 3.71275 10.2781 3.84238 10.1762 3.94423L7.45399 6.66645L10.1762 9.38867C10.2781 9.49052 10.329 9.62015 10.329 9.77756C10.329 9.93497 10.2781 10.0646 10.1762 10.1664C10.0744 10.2683 9.94473 10.3192 9.78733 10.3192C9.62992 10.3192 9.50029 10.2683 9.39844 10.1664L6.67622 7.44423Z" fill="#F7F7F7"/>
</svg>';

$icon_check = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
  <path d="M12.0823 3.67079C12.1917 3.78018 12.2531 3.92853 12.2531 4.08321C12.2531 4.23789 12.1917 4.38623 12.0823 4.49563L6.249 10.329C6.13961 10.4383 5.99126 10.4998 5.83658 10.4998C5.6819 10.4998 5.53356 10.4383 5.42417 10.329L2.5075 7.41229C2.40124 7.30227 2.34244 7.15492 2.34377 7.00197C2.3451 6.84903 2.40645 6.70272 2.5146 6.59456C2.62276 6.48641 2.76907 6.42506 2.92201 6.42373C3.07496 6.4224 3.22231 6.4812 3.33233 6.58746L5.83658 9.09171L11.2575 3.67079C11.3669 3.56143 11.5152 3.5 11.6699 3.5C11.8246 3.5 11.9729 3.56143 12.0823 3.67079Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="sfw-section"
    style="
        --sfw-pt: <?php echo $pt; ?>px;
        --sfw-pb: <?php echo $pb; ?>px;
        --sfw-pt-mob: <?php echo $pt_mob; ?>px;
        --sfw-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Marquee ────────────────────────────────────────────────── */ ?>
    <div class="sfw-marquee" aria-hidden="true">
        <div class="sfw-marquee__track">
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
                <span class="sfw-marquee__item">— <?php echo $marquee_text; ?></span>
            <?php endfor; ?>
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
                <span class="sfw-marquee__item">— <?php echo $marquee_text; ?></span>
            <?php endfor; ?>
        </div>
    </div>

    <div class="sfw-section__container">

        <?php if ( ! empty( $cards ) ) : ?>
            <div class="sfw-grid">
                <?php foreach ( $cards as $index => $card ) :
                    $image         = $card['card_image'] ?? null;
                    $card_title    = esc_html( $card['card_title']    ?? '' );
                    $problem_text  = esc_html( $card['problem_text']  ?? '' );
                    $solution_text = esc_html( $card['solution_text'] ?? '' );
                    $num           = str_pad( $index + 1, 2, '0', STR_PAD_LEFT );
                ?>
                    <div class="sfw-card">

                        <?php /* ── Top row: image + title + index ── */ ?>
                        <div class="sfw-card__header">
                            <div class="sfw-card__header-left">
                                <?php if ( $image ) : ?>
                                    <div
                                        class="sfw-card__image"
                                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                                        role="img"
                                        aria-label="<?php echo esc_attr( $image['alt'] ?: $card_title ); ?>"
                                    ></div>
                                <?php else : ?>
                                    <div class="sfw-card__image sfw-card__image--empty" aria-hidden="true"></div>
                                <?php endif; ?>

                                <?php if ( $card_title ) : ?>
                                    <h3 class="sfw-card__title"><?php echo $card_title; ?></h3>
                                <?php endif; ?>
                            </div>
                            <span class="sfw-card__index" aria-hidden="true">/ <?php echo $num; ?></span>
                        </div>

                        <?php /* ── Problem / Solution sub-cards ── */ ?>
                        <div class="sfw-card__ps-row">

                            <?php if ( $problem_text ) : ?>
                                <div class="sfw-ps-card sfw-ps-card--problem">
                                    <div class="sfw-ps-card__icon sfw-ps-card__icon--dark"><?php echo $icon_x; ?></div>
                                    <div class="sfw-ps-card__content">
                                        <span class="sfw-ps-card__label"><?php esc_html_e( 'Problem', 'lionwood' ); ?></span>
                                        <p class="sfw-ps-card__desc"><?php echo $problem_text; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $solution_text ) : ?>
                                <div class="sfw-ps-card sfw-ps-card--solution">
                                    <div class="sfw-ps-card__icon sfw-ps-card__icon--red"><?php echo $icon_check; ?></div>
                                    <div class="sfw-ps-card__content">
                                        <span class="sfw-ps-card__label"><?php esc_html_e( 'Solution', 'lionwood' ); ?></span>
                                        <p class="sfw-ps-card__desc"><?php echo $solution_text; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div><!-- .sfw-card__ps-row -->

                    </div><!-- .sfw-card -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div><!-- .sfw-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
