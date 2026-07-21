<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <?php // <title> intentionally not hardcoded here — add_theme_support('title-tag')
    // in functions.php already makes WordPress (and RankMath on top of it) render
    // it via wp_head() below; a manual tag here produced two <title> elements. ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">

<?php
// ── SVGs ──────────────────────────────────────────────────────────────────────
$svg_chevron_down = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.25 6.5L6 10.25L9.75 6.5" stroke="#848588" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$svg_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M3.70123 10.2991L10.3009 3.69948M5.35115 3.69948L10.3009 3.69948L10.3009 8.64923" stroke="#848588" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$svg_globe = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M1.5 9C1.5 13.1422 4.85775 16.5 9 16.5C13.1422 16.5 16.5 13.1422 16.5 9C16.5 4.85775 13.1422 1.5 9 1.5C4.85775 1.5 1.5 4.85775 1.5 9Z" stroke="#111319" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.74625 1.53711C9.74625 1.53711 11.9962 4.49961 11.9962 8.99961C11.9962 13.4996 9.74625 16.4621 9.74625 16.4621M8.24625 16.4621C8.24625 16.4621 5.99625 13.4996 5.99625 8.99961C5.99625 4.49961 8.24625 1.53711 8.24625 1.53711M1.96875 11.6246H16.0238M1.96875 6.37461H16.0238" stroke="#111319" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$svg_lang_chevron = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.52819 8.77064L1.75685 4.99931L2.69952 4.05664L5.99952 7.35664L9.29952 4.05664L10.2422 4.99931L6.47085 8.77064C6.34584 8.89562 6.1763 8.96583 5.99952 8.96583C5.82275 8.96583 5.65321 8.89562 5.52819 8.77064Z" fill="#111319"/></svg>';

// ── ACF Options: CTA button (language-aware) ──────────────────────────────────
$hdr_lang  = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
$hdr_lang  = in_array( $hdr_lang, [ 'en', 'uk' ], true ) ? $hdr_lang : 'en';
$cta_link  = function_exists( 'get_field' )
    ? ( get_field( 'header_cta_link_' . $hdr_lang, 'option' ) ?: get_field( 'header_cta_link', 'option' ) )
    : null;
$cta_url   = ! empty( $cta_link['url'] )    ? esc_url( $cta_link['url'] )    : '#';
$cta_label = ! empty( $cta_link['title'] )  ? esc_html( $cta_link['title'] ) : __( 'Contact Us', 'lionwood' );
$cta_tgt   = ! empty( $cta_link['target'] ) ? $cta_link['target']             : '_self';

// ── Logo — ACF Options OR WordPress Customizer ────────────────────────────────
$logo     = function_exists('get_field') ? get_field( 'header_logo', 'option' ) : null;
$logo_url = $logo ? esc_url( $logo['url'] ) : '';
$logo_alt = $logo ? esc_attr( $logo['alt'] ?: get_bloginfo('name') ) : esc_attr( get_bloginfo('name') );

// Fallback: Customizer custom logo
if ( ! $logo_url && has_custom_logo() ) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_image     = wp_get_attachment_image_src( $custom_logo_id, 'full' );
    if ( $logo_image ) $logo_url = esc_url( $logo_image[0] );
}
?>

<header class="hdr" id="header" role="banner">
    <div class="hdr__inner">

        <?php /* ── Logo ── */ ?>
        <a class="hdr__logo" href="<?php echo esc_url( home_url('/') ); ?>" aria-label="<?php bloginfo('name'); ?>">
            <?php if ( $logo_url ) : ?>
                <img
                    src="<?php echo $logo_url; ?>"
                    alt="<?php echo $logo_alt; ?>"
                    width="120"
                    height="36"
                    loading="eager"
                >
            <?php else : ?>
                <span class="hdr__logo-text"><?php bloginfo('name'); ?></span>
            <?php endif; ?>
        </a>

        <?php /* ── Main nav ── */ ?>
        <nav class="hdr__nav" aria-label="<?php esc_attr_e( 'Main navigation', 'lionwood' ); ?>">
            <?php
            wp_nav_menu([
                'theme_location'  => 'primary',
                'container'       => false,
                'menu_class'      => 'hdr__menu',
                'walker'          => new Lionwood_Mega_Menu_Walker(),
                'items_wrap'      => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                'fallback_cb'     => false,
            ]);
            ?>
        </nav>

        <?php /* ── Right controls ── */ ?>
        <div class="hdr__controls">

            <?php /* Language switcher — Polylang */ ?>
            <?php if ( function_exists('pll_the_languages') ) : ?>
                <div class="hdr__lang" data-hdr-lang>
                    <button class="hdr__lang-btn" aria-expanded="false" aria-haspopup="listbox" data-hdr-lang-toggle>
                        <?php echo $svg_globe; ?>
                        <span class="hdr__lang-current">
                            <?php echo esc_html( strtoupper( pll_current_language() ) ); ?>
                        </span>
                        <?php echo $svg_lang_chevron; ?>
                    </button>
                    <ul class="hdr__lang-drop" role="listbox" aria-label="<?php esc_attr_e( 'Select language', 'lionwood' ); ?>">
                        <?php
                        $languages = pll_the_languages(['raw' => 1]);
                        foreach ( $languages as $lang ) :
                            $is_current = $lang['current_lang'];
                        ?>
                            <li role="option" aria-selected="<?php echo $is_current ? 'true' : 'false'; ?>">
                                <a
                                    href="<?php echo esc_url( $lang['url'] ); ?>"
                                    class="hdr__lang-item<?php echo $is_current ? ' is-active' : ''; ?>"
                                    hreflang="<?php echo esc_attr( $lang['slug'] ); ?>"
                                ><?php echo esc_html( strtoupper( $lang['slug'] ) ); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php /* CTA button */ ?>
            <a
                class="hdr__cta"
                href="<?php echo $cta_url; ?>"
                target="<?php echo esc_attr( $cta_tgt ); ?>"
                <?php echo $cta_tgt === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
            ><?php echo $cta_label; ?></a>

            <?php /* Burger — mobile only */ ?>
            <button class="hdr__burger" aria-label="<?php esc_attr_e( 'Open menu', 'lionwood' ); ?>" aria-expanded="false" data-hdr-burger>
                <span></span><span></span><span></span>
            </button>

        </div>

    </div><!-- .hdr__inner -->
</header>


<?php
// ── Mobile menu ────────────────────────────────────────────────────────────────
$svg_close = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M15 5L5 15M5 5L15 15" stroke="#111319" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<div class="mob" id="mob-menu" aria-hidden="true" aria-label="<?php esc_attr_e( 'Main navigation', 'lionwood' ); ?>" role="dialog">

    <div class="mob__backdrop" data-mob-close aria-hidden="true"></div>

    <div class="mob__panel">

        <?php /* ── Panel header ── */ ?>
        <div class="mob__hdr">

            <a class="mob__hdr-logo" href="<?php echo esc_url( home_url('/') ); ?>" aria-label="<?php bloginfo('name'); ?>">
                <?php if ( $logo_url ) : ?>
                    <img src="<?php echo $logo_url; ?>" alt="<?php echo $logo_alt; ?>" width="100" height="30" loading="lazy">
                <?php else : ?>
                    <span class="mob__hdr-logo-text"><?php bloginfo('name'); ?></span>
                <?php endif; ?>
            </a>

            <div class="mob__hdr-right">
                <?php if ( function_exists('pll_the_languages') ) : ?>
                    <div class="mob__lang" data-mob-lang>
                        <button class="mob__lang-btn" aria-expanded="false" data-mob-lang-toggle>
                            <?php echo $svg_globe; ?>
                            <span><?php echo esc_html( strtoupper( pll_current_language() ) ); ?></span>
                            <?php echo $svg_lang_chevron; ?>
                        </button>
                        <ul class="mob__lang-drop" role="listbox">
                            <?php foreach ( pll_the_languages(['raw' => 1]) as $lang ) : ?>
                                <li role="option" aria-selected="<?php echo $lang['current_lang'] ? 'true' : 'false'; ?>">
                                    <a href="<?php echo esc_url( $lang['url'] ); ?>"
                                       class="mob__lang-item<?php echo $lang['current_lang'] ? ' is-active' : ''; ?>"
                                       hreflang="<?php echo esc_attr( $lang['slug'] ); ?>">
                                        <?php echo esc_html( strtoupper( $lang['slug'] ) ); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <button class="mob__close" aria-label="<?php esc_attr_e( 'Close menu', 'lionwood' ); ?>" data-mob-close>
                    <?php echo $svg_close; ?>
                </button>
            </div>

        </div>

        <?php /* ── Scrollable nav ── */ ?>
        <div class="mob__body">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'mob__list',
                'walker'         => new Lionwood_Mobile_Menu_Walker(),
                'fallback_cb'    => false,
            ]);
            ?>
        </div>

        <?php /* ── Fixed CTA at bottom ── */ ?>
        <div class="mob__foot">
            <a
                class="mob__cta"
                href="<?php echo $cta_url; ?>"
                target="<?php echo esc_attr( $cta_tgt ); ?>"
                <?php echo $cta_tgt === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
            ><?php echo $cta_label; ?></a>
        </div>

    </div><!-- .mob__panel -->

</div><!-- .mob -->

<main>
