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

$prob_badge      = get_field( 'problem_badge' )  ?: __( "Client's problem", 'theme' );
$prob_items      = get_field( 'problem_items' )  ?: [];
$sol_badge       = get_field( 'solution_badge' ) ?: __( 'Proposed solution', 'theme' );
$sol_text        = get_field( 'solution_text' )  ?: '';

$banner_enabled  = get_field( 'banner_enabled' );
$banner_text_raw = get_field( 'banner_text' ) ?: '';
$banner_text     = $banner_text_raw ? wp_kses( $banner_text_raw, [ 'br' => [] ] ) : '';
$banner_link     = get_field( 'banner_link' );
$banner_bg       = get_field( 'banner_bg' );
$banner_link_url = ! empty( $banner_link['url'] )    ? esc_url( $banner_link['url'] )    : '';
$banner_lbl      = ! empty( $banner_link['title'] )  ? esc_html( $banner_link['title'] ) : __( 'Book a Meeting', 'theme' );
$banner_tgt      = ! empty( $banner_link['target'] ) ? $banner_link['target']             : '_self';

$decor_enabled   = get_field( 'decor_bottom_enabled' );
$decor_color     = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// × icon SVG
$x_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
    <path d="M7.33446 8.18904L4.34002 11.1835C4.22798 11.2955 4.08539 11.3515 3.91224 11.3515C3.73909 11.3515 3.5965 11.2955 3.48446 11.1835C3.37242 11.0714 3.31641 10.9289 3.31641 10.7557C3.31641 10.5826 3.37242 10.44 3.48446 10.3279L6.47891 7.33349L3.48446 4.33904C3.37242 4.227 3.31641 4.08441 3.31641 3.91126C3.31641 3.73811 3.37242 3.59552 3.48446 3.48349C3.5965 3.37145 3.73909 3.31543 3.91224 3.31543C4.08539 3.31543 4.22798 3.37145 4.34002 3.48349L7.33446 6.47793L10.3289 3.48349C10.4409 3.37145 10.5835 3.31543 10.7567 3.31543C10.9298 3.31543 11.0724 3.37145 11.1845 3.48349C11.2965 3.59552 11.3525 3.73811 11.3525 3.91126C11.3525 4.08441 11.2965 4.227 11.1845 4.33904L8.19002 7.33349L11.1845 10.3279C11.2965 10.44 11.3525 10.5826 11.3525 10.7557C11.3525 10.9289 11.2965 11.0714 11.1845 11.1835C11.0724 11.2955 10.9298 11.3515 10.7567 11.3515C10.5835 11.3515 10.4409 11.2955 10.3289 11.1835L7.33446 8.18904Z" fill="#F7F7F7"/>
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
