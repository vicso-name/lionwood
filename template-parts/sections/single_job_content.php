<?php
/**
 * Block: Single Job Content
 * Slug: acf/single-job-content
 *
 * Repeater rows: H2 title left | wysiwyg OR checklist right
 * Separator between rows.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$rows          = get_field( 'rows' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
    <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    class="sjc-section"
    style="
        --sjc-pt: <?php echo $pt; ?>px;
        --sjc-pb: <?php echo $pb; ?>px;
        --sjc-pt-mob: <?php echo $pt_mob; ?>px;
        --sjc-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sjc-section__container">

        <?php foreach ( $rows as $i => $row ) :
            $title        = esc_html( $row['title'] ?? '' );
            $content_type = $row['content_type'] ?? 'text';
            $text         = $row['text']          ?? '';
            $items        = $row['items']         ?: [];
        ?>

            <?php if ( $i > 0 ) : ?>
                <div class="sjc-divider" aria-hidden="true"></div>
            <?php endif; ?>

            <div class="sjc-row">

                <?php /* ── Left: title ── */ ?>
                <div class="sjc-row__left">
                    <?php if ( $title ) : ?>
                        <h2 class="sjc-title"><?php echo $title; ?></h2>
                    <?php endif; ?>
                </div>

                <?php /* ── Right: text or checklist ── */ ?>
                <div class="sjc-row__right">

                    <?php if ( $content_type === 'text' && $text ) : ?>
                        <div class="sjc-text">
                            <?php echo wp_kses_post( $text ); ?>
                        </div>

                    <?php elseif ( $content_type === 'checklist' && ! empty( $items ) ) : ?>
                        <ul class="sjc-list">
                            <?php foreach ( $items as $item ) :
                                $item_text = esc_html( $item['text'] ?? '' );
                                if ( ! $item_text ) continue;
                            ?>
                                <li class="sjc-list__item">
                                    <span class="sjc-list__icon"><?php echo $check_svg; ?></span>
                                    <span class="sjc-list__text"><?php echo $item_text; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
