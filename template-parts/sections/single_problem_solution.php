<?php
/**
 * Block: Single Problem Solution
 *
 * ACF block slug : acf/single-problem-solution
 * Dark bg, staircase 4-line heading, problem/solution columns, optional banner.
 */

defined( 'ABSPATH' ) || exit;

$pt              = absint( get_field( 'padding_top' )        ?: 100 );
$pb              = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob          = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob          = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$line_1          = get_field( 'title_line_1' ) ?: '';
$line_2          = get_field( 'title_line_2' ) ?: '';
$line_3          = get_field( 'title_line_3' ) ?: '';
$line_4          = get_field( 'title_line_4' ) ?: '';

$prob_badge      = get_field( 'problem_badge' )  ?: __( "Client's problem", 'lionwood' );
$prob_items      = get_field( 'problem_items' )  ?: [];
$sol_badge       = get_field( 'solution_badge' ) ?: '';
$sol_text        = get_field( 'solution_text' )  ?: '';
$sol_link        = get_field( 'solution_link' );
$sol_link_url    = ! empty( $sol_link['url'] )    ? esc_url( $sol_link['url'] )    : '';
$sol_link_lbl    = ! empty( $sol_link['title'] )  ? esc_html( $sol_link['title'] ) : __( 'Get Estimate', 'lionwood' );
$sol_link_tgt    = ! empty( $sol_link['target'] ) ? $sol_link['target']             : '_self';

$banner_enabled  = get_field( 'banner_enabled' );
$banner_text_raw = get_field( 'banner_text' ) ?: '';
$banner_text     = $banner_text_raw ? wp_kses( $banner_text_raw, [ 'br' => [] ] ) : '';
$banner_link     = get_field( 'banner_link' );
$banner_bg       = get_field( 'banner_bg' );
$banner_link_url = ! empty( $banner_link['url'] )    ? esc_url( $banner_link['url'] )    : '';
$banner_lbl      = ! empty( $banner_link['title'] )  ? esc_html( $banner_link['title'] ) : __( 'Book a Meeting', 'lionwood' );
$banner_tgt      = ! empty( $banner_link['target'] ) ? $banner_link['target']             : '_self';

$decor_enabled   = get_field( 'decor_bottom_enabled' );
$decor_color     = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// × icon SVG
$x_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
    <path d="M10.8702 3.30313C10.9686 3.40158 11.0239 3.53509 11.0239 3.6743C11.0239 3.81351 10.9686 3.94702 10.8702 4.04548L5.62019 9.29548C5.52174 9.3939 5.38823 9.44919 5.24902 9.44919C5.10981 9.44919 4.97629 9.3939 4.87784 9.29548L2.25284 6.67048C2.15721 6.57146 2.10429 6.43884 2.10549 6.30119C2.10668 6.16354 2.1619 6.03186 2.25924 5.93452C2.35658 5.83718 2.48825 5.78197 2.62591 5.78077C2.76356 5.77958 2.89618 5.83249 2.99519 5.92813L5.24902 8.18195L10.1278 3.30313C10.2263 3.2047 10.3598 3.14941 10.499 3.14941C10.6382 3.14941 10.7717 3.2047 10.8702 3.30313Z" fill="#F7F7F7"/>
</svg>';

// Calendar icon for banner button
$cal_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <rect x="1.5" y="3" width="15" height="13.5" rx="2" stroke="currentColor" stroke-width="1.2"/>
    <path d="M1.5 7H16.5" stroke="currentColor" stroke-width="1.2"/>
    <path d="M6 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M12 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
</svg>';
?>

<section
    class="sps-section"
    style="
        --sps-pt: <?php echo $pt; ?>px;
        --sps-pb: <?php echo $pb; ?>px;
        --sps-pt-mob: <?php echo $pt_mob; ?>px;
        --sps-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sps-section__container">

        <?php /* ── Staircase heading ─────────────────────────────────── */ ?>
        <div class="sps-heading" aria-label="<?php echo esc_attr( implode( ' ', array_filter( [ $line_1, $line_2, $line_3, $line_4 ] ) ) ); ?>">
            <?php if ( $line_1 ) : ?><span class="sps-heading__line sps-heading__line--1"><?php echo esc_html( $line_1 ); ?></span><?php endif; ?>
            <?php if ( $line_2 ) : ?><span class="sps-heading__line sps-heading__line--2"><?php echo esc_html( $line_2 ); ?></span><?php endif; ?>
            <?php if ( $line_3 ) : ?><span class="sps-heading__line sps-heading__line--3"><?php echo esc_html( $line_3 ); ?></span><?php endif; ?>
            <?php if ( $line_4 ) : ?><span class="sps-heading__line sps-heading__line--4"><?php echo esc_html( $line_4 ); ?></span><?php endif; ?>
        </div>

        <?php /* ── Problem / Solution row ────────────────────────────── */ ?>
        <div class="sps-row">
            <div class="sps-row__top-line" aria-hidden="true"></div>

            <?php /* Left: problem */ ?>
            <div class="sps-col sps-col--problem">
                <?php if ( $prob_badge ) : ?>
                    <span class="sps-badge sps-badge--light"><?php echo esc_html( $prob_badge ); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $prob_items ) ) : ?>
                    <ul class="sps-problem-list">
                        <?php foreach ( $prob_items as $prob ) :
                            $text = esc_html( $prob['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="sps-problem-list__item">
                                <span class="sps-problem-list__icon"><?php echo $x_icon; ?></span>
                                <span class="sps-problem-list__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <?php /* Vertical divider */ ?>
            <div class="sps-row__divider" aria-hidden="true"></div>

            <?php /* Right: solution */ ?>
            <div class="sps-col sps-col--solution">
                <?php if ( $sol_badge ) : ?>
                    <span class="sps-badge sps-badge--light"><?php echo esc_html( $sol_badge ); ?></span>
                <?php endif; ?>
                <?php if ( $sol_text ) : ?>
                    <div class="sps-solution-text">
                        <?php echo wp_kses_post( $sol_text ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $sol_link_url ) : ?>
                    <a
                        class="sps-solution-btn"
                        href="<?php echo $sol_link_url; ?>"
                        target="<?php echo esc_attr( $sol_link_tgt ); ?>"
                        <?php echo '_blank' === $sol_link_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                    ><?php echo $sol_link_lbl; ?></a>
                <?php endif; ?>
            </div>
        </div><!-- .sps-row -->

        <?php /* ── Optional banner ───────────────────────────────────── */ ?>
        <?php if ( $banner_enabled ) : ?>
            <div class="sps-banner"<?php if ( $banner_bg ) echo ' style="background-image: url(\'' . esc_url( $banner_bg['url'] ) . '\')"'; ?>>
                <div class="sps-banner__overlay" aria-hidden="true"></div>
                <?php if ( $banner_text ) : ?>
                    <p class="sps-banner__text"><?php echo $banner_text; ?></p>
                <?php endif; ?>
                <?php if ( $banner_link_url ) : ?>
                    <a
                        class="sps-banner__btn"
                        href="<?php echo $banner_link_url; ?>"
                        target="<?php echo esc_attr( $banner_tgt ); ?>"
                        <?php echo '_blank' === $banner_tgt ? 'rel="noopener noreferrer"' : ''; ?>
                    >
                        <?php echo $cal_icon; ?>
                        <?php echo $banner_lbl; ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div><!-- .sps-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
