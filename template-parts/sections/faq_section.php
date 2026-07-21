<?php
/**
 * Block: Frequently Asked Questions
 *
 * ACF block slug : acf/faq-section
 *
 * Best practices:
 * - <details>/<summary> for native CSS-only fallback
 * - JS enhances with smooth animation via max-height transition
 * - aria-expanded on trigger, aria-hidden on panel
 * - Only one item open at a time (accordion behaviour)
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_line_1  = get_field( 'title_line_1' ) ?: __( 'Frequently', 'lionwood' );
$title_line_2  = get_field( 'title_line_2' ) ?: __( 'Asked Questions', 'lionwood' );
$items         = get_field( 'items' )        ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

if ( empty( $items ) ) return;

$uid = 'faq-' . uniqid();

$plus_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true">
    <path d="M18 11C18 11.5523 17.5523 12 17 12H12V17C12 17.5523 11.5523 18 11 18C10.4477 18 10 17.5523 10 17V12H5C4.44772 12 4 11.5523 4 11C4 10.4477 4.44772 10 5 10H10V5C10 4.44772 10.4477 4 11 4C11.5523 4 12 4.44772 12 5V10H17C17.5523 10 18 10.4477 18 11Z" fill="#848588"/>
</svg>';

$close_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true">
    <path d="M15.9497 15.9498C15.5592 16.3404 14.9261 16.3404 14.5355 15.9498L11 12.4143L7.46447 15.9498C7.07394 16.3404 6.44078 16.3404 6.05025 15.9498C5.65973 15.5593 5.65973 14.9261 6.05025 14.5356L9.58579 11.0001L6.05025 7.46455C5.65973 7.07402 5.65973 6.44086 6.05025 6.05033C6.44078 5.65981 7.07394 5.65981 7.46447 6.05033L11 9.58587L14.5355 6.05033C14.9261 5.65981 15.5592 5.65981 15.9497 6.05033C16.3403 6.44086 16.3403 7.07402 15.9497 7.46455L12.4142 11.0001L15.9497 14.5356C16.3403 14.9261 16.3403 15.5593 15.9497 15.9498Z" fill="#111319"/>
</svg>';
?>

<section
    class="faq-section"
    id="<?php echo esc_attr( $uid ); ?>"
    style="
        --faq-pt: <?php echo $pt; ?>px;
        --faq-pb: <?php echo $pb; ?>px;
        --faq-pt-mob: <?php echo $pt_mob; ?>px;
        --faq-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="faq-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="faq-heading">
            <span class="faq-heading__line"><?php echo esc_html( $title_line_1 ); ?></span>
            <span class="faq-heading__line faq-heading__line--indent"><?php echo esc_html( $title_line_2 ); ?></span>
        </div>

        <?php /* ── Accordion ───────────────────────────────────────────── */ ?>
        <div class="faq-accordion" data-faq-accordion>
            <?php foreach ( $items as $i => $item ) :
                $question    = esc_html( $item['question'] ?? '' );
                $answer      = $item['answer'] ?? '';
                $item_id     = $uid . '-item-' . $i;
                $panel_id    = $uid . '-panel-' . $i;
                $is_first    = ( 0 === $i );
            ?>
                <div class="faq-item<?php echo $is_first ? ' is-open' : ''; ?>" data-faq-item>
                    <button
                        class="faq-item__trigger"
                        id="<?php echo esc_attr( $item_id ); ?>"
                        aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                        data-faq-trigger
                    >
                        <span class="faq-item__question"><?php echo $question; ?></span>
                        <span class="faq-item__icon faq-item__icon--plus"><?php echo $plus_icon; ?></span>
                        <span class="faq-item__icon faq-item__icon--close"><?php echo $close_icon; ?></span>
                    </button>

                    <div
                        class="faq-item__panel<?php echo $is_first ? ' is-open' : ''; ?>"
                        id="<?php echo esc_attr( $panel_id ); ?>"
                        role="region"
                        aria-labelledby="<?php echo esc_attr( $item_id ); ?>"
                        aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
                        data-faq-panel
                    >
                        <div class="faq-item__answer">
                            <?php echo wp_kses_post( $answer ); ?>
                        </div>
                    </div>
                </div><!-- .faq-item -->
            <?php endforeach; ?>
        </div><!-- .faq-accordion -->

    </div><!-- .faq-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
