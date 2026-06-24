<?php
/**
 * Block: Article Numbered List
 * Slug: acf/article-numbered-list
 *
 * Repeater: auto-numbered title + wysiwyg content
 */

defined( 'ABSPATH' ) || exit;

$items = get_field( 'items' ) ?: [];
if ( empty( $items ) ) return;
?>

<div class="anl-list">
    <?php foreach ( $items as $i => $item ) :
        $title   = esc_html( $item['title'] ?? '' );
        $content = $item['content'] ?? '';
        $number  = str_pad( $i + 1, 1, '0', STR_PAD_LEFT );
    ?>
        <div class="anl-item">
            <?php if ( $title ) : ?>
                <h3 class="anl-item__head">
                    <span class="anl-item__num"><?php echo esc_html( $number ); ?></span>
                    <span class="anl-item__title"><?php echo esc_html( $title ); ?></span>
                </h3>
            <?php endif; ?>
            <?php if ( $content ) : ?>
                <div class="anl-item__body">
                    <?php echo wp_kses_post( $content ); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
