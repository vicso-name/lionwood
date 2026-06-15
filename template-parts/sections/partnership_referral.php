<?php
/**
 * Block: Partnership Referral Partner
 * Slug: acf/partnership-referral
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_top    = get_field( 'title_top' )    ?: 'How to Become a';
$title_bottom = get_field( 'title_bottom' ) ?: 'Referral Partner';
$cards        = get_field( 'cards' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="prr-section"
    style="
        --prr-pt: <?php echo $pt; ?>px;
        --prr-pb: <?php echo $pb; ?>px;
        --prr-pt-mob: <?php echo $pt_mob; ?>px;
        --prr-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="prr-section__container">

        <div class="prr-heading">
            <span class="prr-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="prr-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php if ( ! empty( $cards ) ) : ?>
            <div class="prr-grid">
                <?php foreach ( $cards as $i => $card ) :
                    $number      = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
                    $title       = esc_html( $card['title']       ?? '' );
                    $description = esc_html( mb_substr( $card['description'] ?? '', 0, 160 ) );
                ?>
                    <div class="prr-card">
                        <span class="prr-card__number"><?php echo $number; ?></span>
                        <?php if ( $title ) : ?>
                            <h3 class="prr-card__title"><?php echo $title; ?></h3>
                        <?php endif; ?>
                        <?php if ( $description ) : ?>
                            <p class="prr-card__desc"><?php echo $description; ?></p>
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
