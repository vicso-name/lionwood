<?php
/**
 * Block: Partnership Designed For
 * Slug: acf/partnership-designed-for
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$title_top    = get_field( 'title_top' )    ?: 'This Program';
$title_bottom = get_field( 'title_bottom' ) ?: 'Is Designed For';
$cards        = get_field( 'cards' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
    <path d="M12.0823 3.67079C12.1917 3.78018 12.2531 3.92853 12.2531 4.08321C12.2531 4.23789 12.1917 4.38623 12.0823 4.49563L6.249 10.329C6.13961 10.4383 5.99126 10.4998 5.83658 10.4998C5.6819 10.4998 5.53356 10.4383 5.42417 10.329L2.5075 7.41229C2.40124 7.30227 2.34244 7.15492 2.34377 7.00197C2.3451 6.84903 2.40645 6.70272 2.5146 6.59456C2.62276 6.48641 2.76907 6.42506 2.92201 6.42373C3.07496 6.4224 3.22231 6.4812 3.33233 6.58746L5.83658 9.09171L11.2575 3.67079C11.3669 3.56143 11.5152 3.5 11.6699 3.5C11.8246 3.5 11.9729 3.56143 12.0823 3.67079Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="pdf-section"
    style="
        --pdf-pt: <?php echo $pt; ?>px;
        --pdf-pb: <?php echo $pb; ?>px;
        --pdf-pt-mob: <?php echo $pt_mob; ?>px;
        --pdf-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="pdf-section__container">

        <?php /* ── Heading ── */ ?>
        <div class="pdf-heading">
            <span class="pdf-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="pdf-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php /* ── Cards grid ── */ ?>
        <?php if ( ! empty( $cards ) ) : ?>
            <div class="pdf-grid">
                <?php foreach ( $cards as $card ) :
                    $card_title = esc_html( $card['title'] ?? '' );
                    $items      = $card['items'] ?: [];
                ?>
                    <div class="pdf-card">
                        <div class="pdf-card__head">
                            <span class="pdf-card__title"><?php echo $card_title; ?></span>
                        </div>
                        <?php if ( ! empty( $items ) ) : ?>
                            <ul class="pdf-card__list">
                                <?php foreach ( $items as $item ) :
                                    $text = esc_html( $item['text'] ?? '' );
                                    if ( ! $text ) continue;
                                ?>
                                    <li class="pdf-card__item">
                                        <span class="pdf-card__icon"><?php echo $check_svg; ?></span>
                                        <span class="pdf-card__text"><?php echo $text; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
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
