<?php
/**
 * Block: Solution Comparison Section
 *
 * ACF block slug : acf/solution-comparison
 * Red background, two-line staircase heading, two comparison cards (SaaS vs Custom)
 */

defined( 'ABSPATH' ) || exit;

$pt          = absint( get_field( 'padding_top' )        ?: 100 );
$pb          = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob      = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob      = absint( get_field( 'padding_bottom_mob' ) ?: 140 );
$title_line_1 = esc_html( get_field( 'title_line_1' ) ?: __( 'When off-the-shelf', 'lionwood' ) );
$title_line_2 = esc_html( get_field( 'title_line_2' ) ?: __( 'DPP tools fall short', 'lionwood' ) );
$left_title   = esc_html( get_field( 'col_left_title' )  ?: __( 'Ready-made SaaS', 'lionwood' ) );
$left_items   = get_field( 'col_left_items' )  ?: [];
$right_title  = esc_html( get_field( 'col_right_title' ) ?: __( 'Custom by Lionwood Software', 'lionwood' ) );
$right_items  = get_field( 'col_right_items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
if ( $decor_enabled === null ) $decor_enabled = true;
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$icon_x = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
  <path d="M6.67622 7.44423L3.95399 10.1664C3.85214 10.2683 3.72251 10.3192 3.5651 10.3192C3.4077 10.3192 3.27807 10.2683 3.17622 10.1664C3.07436 10.0646 3.02344 9.93497 3.02344 9.77756C3.02344 9.62015 3.07436 9.49052 3.17622 9.38867L5.89844 6.66645L3.17622 3.94423C3.07436 3.84238 3.02344 3.71275 3.02344 3.55534C3.02344 3.39793 3.07436 3.2683 3.17622 3.16645C3.27807 3.0646 3.4077 3.01367 3.5651 3.01367C3.72251 3.01367 3.85214 3.0646 3.95399 3.16645L6.67622 5.88867L9.39844 3.16645C9.50029 3.0646 9.62992 3.01367 9.78733 3.01367C9.94473 3.01367 10.0744 3.0646 10.1762 3.16645C10.2781 3.2683 10.329 3.39793 10.329 3.55534C10.329 3.71275 10.2781 3.84238 10.1762 3.94423L7.45399 6.66645L10.1762 9.38867C10.2781 9.49052 10.329 9.62015 10.329 9.77756C10.329 9.93497 10.2781 10.0646 10.1762 10.1664C10.0744 10.2683 9.94473 10.3192 9.78733 10.3192C9.62992 10.3192 9.50029 10.2683 9.39844 10.1664L6.67622 7.44423Z" fill="#F7F7F7"/>
</svg>';

$icon_check = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
  <path d="M12.0823 3.67079C12.1917 3.78018 12.2531 3.92853 12.2531 4.08321C12.2531 4.23789 12.1917 4.38623 12.0823 4.49563L6.249 10.329C6.13961 10.4383 5.99126 10.4998 5.83658 10.4998C5.6819 10.4998 5.53356 10.4383 5.42417 10.329L2.5075 7.41229C2.40124 7.30227 2.34244 7.15492 2.34377 7.00197C2.3451 6.84903 2.40645 6.70272 2.5146 6.59456C2.62276 6.48641 2.76907 6.42506 2.92201 6.42373C3.07496 6.4224 3.22231 6.4812 3.33233 6.58746L5.83658 9.09171L11.2575 3.67079C11.3669 3.56143 11.5152 3.5 11.6699 3.5C11.8246 3.5 11.9729 3.56143 12.0823 3.67079Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="sc-section"
    style="
        --sc-pt: <?php echo $pt; ?>px;
        --sc-pb: <?php echo $pb; ?>px;
        --sc-pt-mob: <?php echo $pt_mob; ?>px;
        --sc-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sc-section__container">

        <div class="sc-heading">
            <span class="sc-heading__line-1"><?php echo $title_line_1; ?></span>
            <span class="sc-heading__line-2"><?php echo $title_line_2; ?></span>
        </div>

        <div class="sc-cards">

            <?php /* ── Left card: Ready-made SaaS ─────────────────────────── */ ?>
            <div class="sc-card sc-card--left">
                <div class="sc-card__header sc-card__header--dark">
                    <h3 class="sc-card__title"><?php echo $left_title; ?></h3>
                </div>
                <?php if ( ! empty( $left_items ) ) : ?>
                    <ul class="sc-card__list">
                        <?php foreach ( $left_items as $item ) :
                            $text = esc_html( $item['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="sc-card__item">
                                <span class="sc-card__icon sc-card__icon--dark" aria-hidden="true"><?php echo $icon_x; ?></span>
                                <span class="sc-card__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <?php /* ── Right card: Custom by Lionwood ──────────────────────── */ ?>
            <div class="sc-card sc-card--right">
                <div class="sc-card__header sc-card__header--red">
                    <h3 class="sc-card__title"><?php echo $right_title; ?></h3>
                </div>
                <?php if ( ! empty( $right_items ) ) : ?>
                    <ul class="sc-card__list">
                        <?php foreach ( $right_items as $item ) :
                            $text = esc_html( $item['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="sc-card__item">
                                <span class="sc-card__icon sc-card__icon--red" aria-hidden="true"><?php echo $icon_check; ?></span>
                                <span class="sc-card__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div><!-- .sc-cards -->

    </div><!-- .sc-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
