<?php
/**
 * Block: Solution Timeline Section
 *
 * ACF block slug : acf/solution-timeline
 *
 * Dark (#111319) background snake timeline + embedded banner below.
 * Timeline logic identical to process_timeline — uses slt- prefix.
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 140 );
$title_top     = esc_html( get_field( 'title_top' )    ?: __( 'Solution', 'lionwood' ) );
$title_bottom  = esc_html( get_field( 'title_bottom' ) ?: __( 'Timeline', 'lionwood' ) );
$items_per_row = absint( get_field( 'items_per_row' ) ?: 5 );
$milestones    = get_field( 'milestones' ) ?: [];

// Banner fields
$ban_bg_color  = get_field( 'ban_bg_color' )  ?: '#C83030';
$ban_bg_image  = get_field( 'ban_bg_image' );
$ban_text_raw  = get_field( 'ban_text' ) ?: '';
$ban_text      = $ban_text_raw ? wp_kses( $ban_text_raw, [ 'br' => [] ] ) : '';
$ban_link_raw  = get_field( 'ban_link' );
$ban_link_url  = ! empty( $ban_link_raw['url'] )    ? esc_url( $ban_link_raw['url'] )    : '';
$ban_link_lbl  = ! empty( $ban_link_raw['title'] )  ? esc_html( $ban_link_raw['title'] ) : __( 'Book a Meeting', 'lionwood' );
$ban_link_tgt  = ! empty( $ban_link_raw['target'] ) ? $ban_link_raw['target']             : '_self';

$decor_enabled = get_field( 'decor_bottom_enabled' );
if ( $decor_enabled === null ) $decor_enabled = true;
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

if ( empty( $milestones ) ) return;

$rows = array_chunk( $milestones, $items_per_row );
$uid  = 'slt-' . uniqid();

// Dynamic milestone max-width: ~(1200px content - gaps) / columns, capped at 370px
$milestone_maxw = min( 370, intval( floor( (1200 - ($items_per_row - 1) * 24) / $items_per_row ) ) );
// Reversed row left indent: scales from 11% (2 cols) up to 20% (5+ cols)
$reversed_pl    = max( 11, min( 20, $items_per_row * 4 + 3 ) );
// Row gap: for 2 cols add 10% extra so item 2 sits ~10% further right; else 24px
$row_gap        = $items_per_row <= 2 ? 'calc(10% + 24px)' : '24px';

$cal_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <rect x="1.5" y="3" width="15" height="13.5" rx="2" stroke="currentColor" stroke-width="1.2"/>
    <path d="M1.5 7H16.5" stroke="currentColor" stroke-width="1.2"/>
    <path d="M6 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M12 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
</svg>';

$default_ban_img = THEME_URI . '/assets/img/banner_bg_red.webp';
$ban_img_url     = $ban_bg_image ? esc_url( $ban_bg_image['url'] ) : esc_url( $default_ban_img );
$ban_bg_style    = 'background-color: ' . esc_attr( $ban_bg_color ) . '; background-image: url(\'' . $ban_img_url . '\'); background-size: cover; background-position: center;';
?>

<section
    class="slt-section"
    id="<?php echo esc_attr( $uid ); ?>"
    style="
        --slt-pt: <?php echo $pt; ?>px;
        --slt-pb: <?php echo $pb; ?>px;
        --slt-pt-mob: <?php echo $pt_mob; ?>px;
        --slt-pb-mob: <?php echo $pb_mob; ?>px;
        --slt-milestone-maxw: <?php echo $milestone_maxw; ?>px;
        --slt-reversed-pl: <?php echo $reversed_pl; ?>%;
        --slt-row-gap: <?php echo $row_gap; ?>;
    "
    data-items-per-row="<?php echo esc_attr( $items_per_row ); ?>"
>
    <div class="slt-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <div class="slt-heading">
            <span class="slt-heading__top"><?php echo $title_top; ?></span>
            <span class="slt-heading__bottom"><?php echo $title_bottom; ?></span>
        </div>

        <?php /* ── Timeline wrapper ─────────────────────────────────────── */ ?>
        <div class="slt-timeline" data-slt-timeline>

            <svg class="slt-svg" aria-hidden="true">
                <path class="slt-path--base" fill="none" stroke="#ffffff" stroke-width="1" opacity="0.2"/>
                <path class="slt-path--progress" fill="none" stroke="#ffffff" stroke-width="1" opacity="1"/>
            </svg>

            <?php foreach ( $rows as $row_index => $row ) :
                $is_reversed = ( $row_index % 2 !== 0 );
            ?>
                <div
                    class="slt-row<?php echo $is_reversed ? ' slt-row--reversed' : ''; ?>"
                    data-row="<?php echo esc_attr( $row_index ); ?>"
                >
                    <?php foreach ( $row as $item ) :
                        $date     = esc_html( $item['date'] ?? '' );
                        $desc     = $item['description'] ?? '';
                        $desc_out = $desc ? wp_kses( $desc, [ 'br' => [] ] ) : '';
                    ?>
                        <div class="slt-milestone" data-slt-milestone>
                            <div class="slt-milestone__dot" aria-hidden="true"></div>
                            <div class="slt-milestone__pill"><?php echo $date; ?></div>
                            <?php if ( $desc_out ) : ?>
                                <p class="slt-milestone__desc"><?php echo $desc_out; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

        </div><!-- .slt-timeline -->

        <?php /* ── Embedded banner ─────────────────────────────────────── */ ?>
        <?php if ( $ban_text || $ban_link_url ) : ?>
            <div class="slt-banner" style="<?php echo esc_attr( $ban_bg_style ); ?>">
                <div class="slt-banner__overlay" aria-hidden="true"></div>

                <?php if ( $ban_text ) : ?>
                    <p class="slt-banner__text"><?php echo $ban_text; ?></p>
                <?php endif; ?>

                <?php if ( $ban_link_url ) : ?>
                    <a
                        class="slt-banner__btn"
                        href="<?php echo $ban_link_url; ?>"
                        target="<?php echo esc_attr( $ban_link_tgt ); ?>"
                        <?php echo '_blank' === $ban_link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                    >
                        <?php echo $cal_icon; ?>
                        <?php echo $ban_link_lbl; ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div><!-- .slt-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
