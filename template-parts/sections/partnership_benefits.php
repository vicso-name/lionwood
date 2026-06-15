<?php
/**
 * Block: Partnership Benefits
 * Slug: acf/partnership-benefits
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 50 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 70 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 40 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 40 );

$title_top    = get_field( 'title_top' )    ?: 'Your Benefits Of';
$title_bottom = get_field( 'title_bottom' ) ?: 'Becoming a Partner';
$cards        = get_field( 'cards' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="pb-section"
    style="
        --pb-pt: <?php echo $pt; ?>px;
        --pb-pb: <?php echo $pb; ?>px;
        --pb-pt-mob: <?php echo $pt_mob; ?>px;
        --pb-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="pb-section__container">

        <div class="pb-heading">
            <span class="pb-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="pb-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php if ( ! empty( $cards ) ) : ?>
            <div class="pb-grid">
                <?php foreach ( $cards as $card ) :
                    $icon        = $card['icon']        ?? null;
                    $title       = esc_html( $card['title']       ?? '' );
                    $description = esc_html( mb_substr( $card['description'] ?? '', 0, 160 ) );
                ?>
                    <div class="pb-card">
                        <?php if ( $icon ) : ?>
                            <div class="pb-card__icon">
                                <img
                                    src="<?php echo esc_url( $icon['url'] ); ?>"
                                    alt="<?php echo esc_attr( $icon['alt'] ?: $title ); ?>"
                                    width="60"
                                    height="60"
                                    loading="lazy"
                                >
                            </div>
                        <?php endif; ?>
                        <?php if ( $title ) : ?>
                            <h3 class="pb-card__title"><?php echo $title; ?></h3>
                        <?php endif; ?>
                        <?php if ( $description ) : ?>
                            <p class="pb-card__desc"><?php echo $description; ?></p>
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
