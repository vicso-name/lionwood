<?php
/**
 * Block: Privacy Content
 * Slug: acf/privacy-content
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$sections      = get_field( 'sections' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="prc-section"
    style="
        --prc-pt: <?php echo $pt; ?>px;
        --prc-pb: <?php echo $pb; ?>px;
        --prc-pt-mob: <?php echo $pt_mob; ?>px;
        --prc-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="prc-section__container">

        <?php foreach ( $sections as $i => $section ) :
            $number  = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
            $title   = esc_html( $section['title'] ?? '' );
            $content = $section['content'] ?? '';
        ?>
            <?php if ( $i > 0 ) : ?>
                <div class="prc-divider" aria-hidden="true"></div>
            <?php endif; ?>

            <div class="prc-row">

                <?php /* ── Left col: number + title ── */ ?>
                <div class="prc-row__left">
                    <span class="prc-number"><?php echo $number; ?></span>
                    <?php if ( $title ) : ?>
                        <h2 class="prc-title"><?php echo $title; ?></h2>
                    <?php endif; ?>
                </div>

                <?php /* ── Right col: wysiwyg content ── */ ?>
                <div class="prc-row__right">
                    <?php if ( $content ) : ?>
                        <div class="prc-content">
                            <?php echo wp_kses_post( $content ); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
