<?php
/**
 * Block: About Rows
 * Slug: acf/about-rows
 *
 * Alternating image + text rows (checkerboard layout).
 * Even rows  → image left, text right
 * Odd rows   → image right, text left
 */

defined( 'ABSPATH' ) || exit;

$pt        = absint( get_field('padding_top')        ?: 100 );
$pb        = absint( get_field('padding_bottom')     ?: 100 );
$pt_mob    = absint( get_field('padding_top_mob')    ?: 70 );
$pb_mob    = absint( get_field('padding_bottom_mob') ?: 70 );

$decor_enabled = get_field('decor_enabled');
$decor_color   = get_field('decor_color') ?: '#F7F7F7';

$rows = get_field('rows');

if ( ! $rows ) return;
?>

<section
    class="ar-section"
    style="
        --ar-pt: <?php echo $pt; ?>px;
        --ar-pb: <?php echo $pb; ?>px;
        --ar-pt-mob: <?php echo $pt_mob; ?>px;
        --ar-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ar-section__container">

        <?php foreach ( $rows as $index => $row ) :
            $is_reversed  = ( $index % 2 !== 0 );
            $image        = $row['image'];
            $title        = $row['title'];
            $description  = $row['description'];
            $content_type = $row['content_type'];
            $checklist    = $row['checklist'];
            $logos        = $row['logos'];
            $btn_enabled  = $row['button_enabled'];
            $btn_link     = $row['button_link'];
        ?>
        <div class="ar-row<?php echo $is_reversed ? ' ar-row--reversed' : ''; ?>">

            <?php /* ── Image ── */ ?>
            <div class="ar-row__image-col">
                <?php if ( $image ) : ?>
                    <div
                        class="ar-row__image"
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: '' ); ?>"
                    ></div>
                <?php endif; ?>
            </div>

            <?php /* ── Text ── */ ?>
            <div class="ar-row__text-col">
                <div class="ar-row__text-inner">

                    <?php if ( $title ) : ?>
                        <h2 class="ar-row__title"><?php echo wp_kses_post( $title ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $description ) : ?>
                        <p class="ar-row__description"><?php echo nl2br( esc_html( $description ) ); ?></p>
                    <?php endif; ?>

                    <?php /* ── Checklist ── */ ?>
                    <?php if ( $content_type === 'checklist' && $checklist ) : ?>
                        <ul class="ar-checklist">
                            <?php foreach ( $checklist as $item ) : ?>
                            <li class="ar-checklist__item">
                                <span class="ar-checklist__icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <rect width="16" height="16" rx="8" fill="#C83030"/>
                                        <path d="M11.995 4.781a.48.48 0 0 1 .14.34.48.48 0 0 1-.14.34L6.66 10.797a.48.48 0 0 1-.34.14.48.48 0 0 1-.34-.14L3.646 8.463a.48.48 0 0 1-.14-.337.48.48 0 0 1 .144-.335.48.48 0 0 1 .337-.14.48.48 0 0 1 .334.144l1.997 1.998 4.993-4.993a.48.48 0 0 1 .34-.14c.128 0 .25.05.34.14z" fill="#F7F7F7"/>
                                    </svg>
                                </span>
                                <span class="ar-checklist__text"><?php echo esc_html( $item['text'] ); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php /* ── Logos ── */ ?>
                    <?php if ( $content_type === 'logos' && $logos ) : ?>
                        <div class="ar-logos">
                            <?php foreach ( $logos as $logo_item ) :
                                $logo = $logo_item['logo'];
                                if ( ! $logo ) continue;
                            ?>
                                <img
                                    class="ar-logos__item"
                                    src="<?php echo esc_url( $logo['url'] ); ?>"
                                    alt="<?php echo esc_attr( $logo['alt'] ); ?>"
                                    width="<?php echo esc_attr( $logo['width'] ); ?>"
                                    height="<?php echo esc_attr( $logo['height'] ); ?>"
                                    loading="lazy"
                                >
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php /* ── Button ── */ ?>
                    <?php if ( $btn_enabled && $btn_link ) : ?>
                        <a
                            class="ar-btn"
                            href="<?php echo esc_url( $btn_link['url'] ); ?>"
                            <?php if ( $btn_link['target'] ) : ?>target="<?php echo esc_attr( $btn_link['target'] ); ?>"<?php endif; ?>
                        >
                            <?php echo esc_html( $btn_link['title'] ); ?>
                        </a>
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <?php endforeach; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
