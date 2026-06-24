<?php
/**
 * Block: Business Challenge Section
 *
 * ACF block slug : acf/business-challenge
 * bg #E9E9E9, staircase heading, description, two challenge cards
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 80 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 50 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 50 );
$title_line_1  = esc_html( get_field( 'title_line_1' ) ?: __( 'Business', 'lionwood' ) );
$title_line_2  = esc_html( get_field( 'title_line_2' ) ?: __( 'Challenge', 'lionwood' ) );
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$left_title    = esc_html( get_field( 'col_left_title' )  ?: __( 'User challenges', 'lionwood' ) );
$left_items    = get_field( 'col_left_items' )  ?: [];
$right_title   = esc_html( get_field( 'col_right_title' ) ?: __( 'Business challenges', 'lionwood' ) );
$right_items   = get_field( 'col_right_items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
    <path d="M12.0784 3.67079C12.1878 3.78018 12.2492 3.92853 12.2492 4.08321C12.2492 4.23789 12.1878 4.38623 12.0784 4.49563L6.24509 10.329C6.1357 10.4383 5.98736 10.4998 5.83268 10.4998C5.678 10.4998 5.52965 10.4383 5.42026 10.329L2.50359 7.41229C2.39733 7.30227 2.33854 7.15492 2.33987 7.00197C2.34119 6.84903 2.40254 6.70272 2.5107 6.59456C2.61885 6.48641 2.76516 6.42506 2.91811 6.42373C3.07106 6.4224 3.21841 6.4812 3.32843 6.58746L5.83268 9.09171L11.2536 3.67079C11.363 3.56143 11.5113 3.5 11.666 3.5C11.8207 3.5 11.969 3.56143 12.0784 3.67079Z" fill="#F7F7F7"/>
</svg>';

// Helper: render a challenge card
if ( ! function_exists( 'smplfy_bc_card' ) ) {
    function smplfy_bc_card( string $title, array $items, string $check_svg ): void { ?>
        <div class="bc-card">
            <div class="bc-card__header">
                <h3 class="bc-card__title"><?php echo esc_html( $title ); ?></h3>
            </div>
            <?php if ( ! empty( $items ) ) : ?>
                <ul class="bc-card__list">
                    <?php foreach ( $items as $item ) :
                        $text = esc_html( $item['text'] ?? '' );
                        if ( ! $text ) continue;
                    ?>
                        <li class="bc-card__item">
                            <span class="bc-card__icon" aria-hidden="true"><?php echo $check_svg; ?></span>
                            <span class="bc-card__text"><?php echo $text; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php }
}
?>

<section
    class="bc-section"
    style="
        --bc-pt: <?php echo $pt; ?>px;
        --bc-pb: <?php echo $pb; ?>px;
        --bc-pt-mob: <?php echo $pt_mob; ?>px;
        --bc-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="bc-section__container">

        <?php /* ── Header: heading + description ────────────────────────── */ ?>
        <div class="bc-header">
            <div class="bc-heading">
                <span class="bc-heading__line-1"><?php echo esc_html( $title_line_1 ); ?></span>
                <span class="bc-heading__line-2"><?php echo esc_html( $title_line_2 ); ?></span>
            </div>
            <?php if ( $description ) : ?>
                <p class="bc-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Two challenge cards ───────────────────────────────────── */ ?>
        <div class="bc-cards">
            <?php smplfy_bc_card( $left_title,  $left_items,  $check_svg ); ?>
            <?php smplfy_bc_card( $right_title, $right_items, $check_svg ); ?>
        </div>

    </div><!-- .bc-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
