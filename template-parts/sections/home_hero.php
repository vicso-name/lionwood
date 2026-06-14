<?php
/**
 * Block: Home Hero
 *
 * ACF block slug : acf/home-hero
 * Template file  : blocks/home-hero/home-hero.php
 *
 * Layout:
 *   Line 1: [FIND] [arrow SVG] ............. [PERFECT]
 *   Line 2:             SOFTWARE FOR
 *   Line 3: _ [MANUFACTURING] [circle image]  ← typed/cycling
 *
 *   120px gap
 *
 *   Row: [description 410px] .......... [Get Estimate btn] [Book a Meeting btn]
 *
 * Book a Meeting becomes sticky + filled on scroll (handled in JS).
 */

defined( 'ABSPATH' ) || exit;

$line1_start = get_field( 'title_line1_start' ) ?: __( 'Find', 'theme' );
$line1_end   = get_field( 'title_line1_end' )   ?: __( 'Perfect', 'theme' );
$line2       = get_field( 'title_line2' )        ?: __( 'Software For', 'theme' );
$industries  = get_field( 'industries' )         ?: [];
$desc_raw    = get_field( 'description' );
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$est_raw    = get_field( 'cta_estimate' );
$est_url    = ! empty( $est_raw['url'] )    ? esc_url( $est_raw['url'] )    : '#';
$est_label  = ! empty( $est_raw['title'] )  ? esc_html( $est_raw['title'] ) : __( 'Get Estimate', 'theme' );
$est_target = ! empty( $est_raw['target'] ) ? $est_raw['target']             : '_self';

$mtg_raw    = get_field( 'cta_meeting' );
$mtg_url    = ! empty( $mtg_raw['url'] )    ? esc_url( $mtg_raw['url'] )    : '#';
$mtg_label  = ! empty( $mtg_raw['title'] )  ? esc_html( $mtg_raw['title'] ) : __( 'Book a Meeting', 'theme' );
$mtg_target = ! empty( $mtg_raw['target'] ) ? $mtg_raw['target']             : '_self';

// Build industries JSON for JS
$industries_data = [];
foreach ( $industries as $ind ) {
    $img = $ind['image'] ?? null;
    $industries_data[] = [
        'name'      => esc_html( $ind['name'] ?? '' ),
        'image_url' => $img ? esc_url( $img['url'] ) : '',
        'image_alt' => $img ? esc_attr( $img['alt'] ?: $ind['name'] ) : '',
    ];
}

// Fallback first industry for SSR (no-JS)
$first_industry = $industries[0] ?? null;
$first_name     = esc_html( $first_industry['name'] ?? '' );
$first_img      = $first_industry['image'] ?? null;
?>

<section class="hh-section" aria-label="<?php esc_attr_e( 'Hero', 'theme' ); ?>">
    <div class="hh-section__container">

        <?php /* ── Heading ────────────────────────────────────────────── */ ?>
        <h1 class="hh-heading" aria-label="<?php echo esc_attr( $line1_start . ' ' . $line1_end . ' ' . $line2 . ' ' . $first_name ); ?>">

            <?php /* Line 1: FIND [arrow] ........... PERFECT */ ?>
            <div class="hh-heading__line hh-heading__line--1">
                <span class="hh-heading__word hh-heading__word--start hh-anim" data-delay="0">
                    <?php echo esc_html( $line1_start ); ?>
                </span>
                <span class="hh-heading__arrow hh-anim" data-delay="150" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="110" height="110" viewBox="0 0 48 36" fill="none">
                        <path d="M43 23L44.4237 24.4047L45.8096 23L44.4237 21.5953L43 23ZM15.8667 23V25V23ZM15.8667 3V1V3ZM33.1333 13L31.7097 14.4047L41.5763 24.4047L43 23L44.4237 21.5953L34.557 11.5953L33.1333 13ZM43 23L41.5763 21.5953L31.7097 31.5953L33.1333 33L34.557 34.4047L44.4237 24.4047L43 23ZM43 23V21H15.8667V23V25H43V23ZM15.8667 23V21C13.789 21 11.791 20.1638 10.3136 18.6664L8.88988 20.0711L7.46621 21.4758C9.68951 23.7291 12.7107 25 15.8667 25V23ZM8.88988 20.0711L10.3136 18.6664C8.83524 17.1681 8 15.1303 8 13H6H4C4 16.174 5.24381 19.2233 7.46621 21.4758L8.88988 20.0711ZM6 13H8C8 10.8697 8.83524 8.83191 10.3136 7.33362L8.88988 5.92893L7.46621 4.52424C5.24381 6.77668 4 9.826 4 13H6ZM8.88988 5.92893L10.3136 7.33362C11.791 5.83624 13.789 5 15.8667 5V3V1C12.7107 1 9.68951 2.2709 7.46621 4.52424L8.88988 5.92893ZM15.8667 3V5H18.3333V3V1H15.8667V3Z" fill="#C83030"/>
                    </svg>
                </span>
                <span class="hh-heading__word hh-heading__word--end hh-anim" data-delay="300">
                    <?php echo esc_html( $line1_end ); ?>
                </span>
            </div>

            <?php /* Line 2: SOFTWARE FOR */ ?>
            <div class="hh-heading__line hh-heading__line--2">
                <span class="hh-heading__static hh-anim" data-delay="450">
                    <?php echo esc_html( $line2 ); ?>
                </span>
            </div>

            <?php /* Line 3: _ [typed industry] [circle image] */ ?>
            <div class="hh-heading__line hh-heading__line--3">
                <span class="hh-heading__dash hh-anim" data-delay="600" aria-hidden="true">_</span>
                <span
                    class="hh-heading__typed"
                    data-industries="<?php echo esc_attr( wp_json_encode( $industries_data ) ); ?>"
                    aria-live="polite"
                ></span>

                <?php /* Industry image with subtract mask */ ?>
                <span class="hh-heading__img-wrap" aria-hidden="true">

                    <?php if ( $first_img ) : ?>
                        <img
                            class="hh-heading__img"
                            src="<?php echo esc_url( $first_img['url'] ); ?>"
                            alt="<?php echo esc_attr( $first_img['alt'] ?: $first_name ); ?>"
                            width="96"
                            height="96"
                            loading="eager"
                        >
                    <?php endif; ?>
                </span>
            </div>

        </h1><!-- .hh-heading -->

        <?php /* ── Bottom row: description + CTAs ────────────────────── */ ?>
        <div class="hh-bottom">

            <p class="hh-description hh-anim" data-delay="900">
                <?php echo $description; ?>
            </p>

            <div class="hh-ctas hh-anim" data-delay="1000">
                <a
                    class="hh-btn hh-btn--primary"
                    href="<?php echo $est_url; ?>"
                    target="<?php echo esc_attr( $est_target ); ?>"
                    <?php echo '_blank' === $est_target ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $est_label; ?></a>

                <a
                    class="hh-btn hh-btn--outline js-book-meeting"
                    href="<?php echo $mtg_url; ?>"
                    target="<?php echo esc_attr( $mtg_target ); ?>"
                    <?php echo '_blank' === $mtg_target ? 'rel="noopener noreferrer"' : ''; ?>
                >
                    <?php /* Calendar icon */ ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                        <rect x="1.5" y="3" width="15" height="13.5" rx="2" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M1.5 7H16.5" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M6 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                        <path d="M12 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <?php echo $mtg_label; ?>
                </a>
            </div>

        </div><!-- .hh-bottom -->

    </div><!-- .hh-section__container -->
</section>

<?php /* ── Sticky Book a Meeting clone (appears on scroll) ──────────── */ ?>
<a
    class="hh-sticky-btn js-sticky-meeting"
    href="<?php echo $mtg_url; ?>"
    target="<?php echo esc_attr( $mtg_target ); ?>"
    <?php echo '_blank' === $mtg_target ? 'rel="noopener noreferrer"' : ''; ?>
    aria-label="<?php echo esc_attr( $mtg_label ); ?>"
>
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
        <rect x="1.5" y="3" width="15" height="13.5" rx="2" stroke="currentColor" stroke-width="1.2"/>
        <path d="M1.5 7H16.5" stroke="currentColor" stroke-width="1.2"/>
        <path d="M6 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
        <path d="M12 1.5V4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
    </svg>
    <?php echo $mtg_label; ?>
</a>
