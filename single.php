<?php
/**
 * Template: Single Post
 * Three-column layout: left sidebar | content | right sidebar
 */

get_header();

while ( have_posts() ) :
    the_post();

    // ── Parse and split blocks into three categories ─────────────────────────
    $all_blocks     = parse_blocks( get_the_content() );
    $hero_blocks    = [];
    $content_blocks = [];
    $bottom_blocks  = [];

    $bottom_block_names = [
        'acf/about-author',
        'acf/faq-section',
        'acf/insights-articles',
        'acf/contact-section',
    ];

    foreach ( $all_blocks as $block ) {
        if ( $block['blockName'] === 'acf/article-hero' ) {
            $hero_blocks[] = $block;
        } elseif ( in_array( $block['blockName'], $bottom_block_names, true ) ) {
            $bottom_blocks[] = $block;
        } else {
            $content_blocks[] = $block;
        }
    }

    // ── ACF sidebar fields ────────────────────────────────────────────────────
    $sub_show       = get_field( 'sidebar_subscribe_show' );
    $sub_text       = get_field( 'sidebar_subscribe_text' )   ?: 'Discover innovative strategies and forward-thinking ideas to enhance your digital products.';
    $sub_btn_label  = get_field( 'sidebar_subscribe_label' )  ?: 'Subscribe';

    // HubSpot credentials for subscribe popup — from global options (Theme Settings)
    $hs_portal_id        = esc_attr( get_field( 'hs_portal_id', 'option' ) ?: '' );
    $hs_subscribe_form   = esc_attr( get_field( 'hs_default_form_id', 'option' ) ?: '' );

    $ai_show        = get_field( 'sidebar_ai_show' );

    // ── AI prompt: explicit _en / _uk fields (see inc/acf-ai-fields.php) ─────
    $ai_lang     = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
    $ai_lang     = in_array( $ai_lang, [ 'en', 'uk' ], true ) ? $ai_lang : 'en';
    $ai_fallbacks = [
        'en' => 'Summarize the key insights from this article in 5 bullet points, then write a 2-sentence takeaway: {url}',
        'uk' => 'Підсумуй головні думки з цієї статті у 5 пунктах, а потім напиши 2 речення з основним висновком: {url}',
    ];
    $ai_prompt = esc_attr( str_replace(
        '{url}',
        get_permalink(),
        get_field( 'ai_summary_default_prompt_' . $ai_lang, 'option' ) ?: $ai_fallbacks[ $ai_lang ]
    ) );

    $ai_tools = [
        [
            'name' => 'ChatGPT',
            'url'  => get_field( 'ai_chatgpt_url', 'option' ) ?: 'https://chatgpt.com',
            'icon' => 'ChatGPT.svg',
        ],
        [
            'name' => 'Perplexity',
            'url'  => get_field( 'ai_perplexity_url', 'option' ) ?: 'https://www.perplexity.ai',
            'icon' => 'Perplexity.svg',
        ],
        [
            'name' => 'Google AI',
            'url'  => ( function () {
                $u = get_field( 'ai_google_url', 'option' );
                if ( empty( $u ) || strpos( $u, 'gemini.google.com' ) !== false ) {
                    return 'https://www.google.com/search?udm=50';
                }
                return $u;
            } )(),
            'icon' => 'Google_AI.svg',
        ],
    ];

    $cta_show       = get_field( 'sidebar_cta_show' );
    $cta_text       = get_field( 'sidebar_cta_text' )  ?: '';
    $cta_link       = get_field( 'sidebar_cta_link' );
    $cta_url        = ! empty( $cta_link['url'] ) ? esc_url( $cta_link['url'] ) : '#';
    $cta_label      = ! empty( $cta_link['title'] ) ? esc_html( $cta_link['title'] ) : esc_html__( 'Book a Meeting', 'lionwood' );
    $cta_target     = ! empty( $cta_link['target'] ) ? $cta_link['target'] : '_self';

    // Current URL for share buttons
    $share_url  = urlencode( get_permalink() );
    $share_title = urlencode( get_the_title() );

    // SVGs
    $icon_sections = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><g clip-path="url(#clip0_1641_10317)"><path d="M4.37838 10.6C3.61568 10.6 3 11.2253 3 12C3 12.7747 3.61568 13.4 4.37838 13.4C5.14108 13.4 5.75676 12.7747 5.75676 12C5.75676 11.2253 5.14108 10.6 4.37838 10.6ZM4.37838 5C3.61568 5 3 5.62533 3 6.4C3 7.17467 3.61568 7.8 4.37838 7.8C5.14108 7.8 5.75676 7.17467 5.75676 6.4C5.75676 5.62533 5.14108 5 4.37838 5ZM4.37838 16.2C3.61568 16.2 3 16.8347 3 17.6C3 18.3653 3.62486 19 4.37838 19C5.13189 19 5.75676 18.3653 5.75676 17.6C5.75676 16.8347 5.14108 16.2 4.37838 16.2ZM8.05405 18.5333H20.0811C20.5865 18.5333 21 18.1133 21 17.6C21 17.0867 20.5865 16.6667 20.0811 16.6667H8.05405C7.54865 16.6667 7.13514 17.0867 7.13514 17.6C7.13514 18.1133 7.54865 18.5333 8.05405 18.5333ZM8.05405 12.9333H20.0811C20.5865 12.9333 21 12.5133 21 12C21 11.4867 20.5865 11.0667 20.0811 11.0667H8.05405C7.54865 11.0667 7.13514 11.4867 7.13514 12C7.13514 12.5133 7.54865 12.9333 8.05405 12.9333ZM7.13514 6.4C7.13514 6.91333 7.54865 7.33333 8.05405 7.33333H20.0811C20.5865 7.33333 21 6.91333 21 6.4C21 5.88667 20.5865 5.46667 20.0811 5.46667H8.05405C7.54865 5.46667 7.13514 5.88667 7.13514 6.4Z" fill="#848588"/></g><defs><clipPath id="clip0_1641_10317"><rect width="24" height="24" fill="white"/></clipPath></defs></svg>';
    $dot_svg    = '<svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none"><circle cx="3" cy="3" r="3" fill="#C83030"/></svg>';
    $fb_svg     = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M11.8346 2.39648C10.8124 2.39648 9.83212 2.80255 9.10933 3.52534C8.38653 4.24814 7.98047 5.22846 7.98047 6.25065V8.39648H5.91797C5.81464 8.39648 5.73047 8.47982 5.73047 8.58398V11.4173C5.73047 11.5207 5.8138 11.6048 5.91797 11.6048H7.98047V17.4173C7.98047 17.5206 8.0638 17.6048 8.16797 17.6048H11.0013C11.1046 17.6048 11.1888 17.5215 11.1888 17.4173V11.6048H13.2696C13.3555 11.6048 13.4305 11.5465 13.4513 11.4632L14.1596 8.62982C14.1666 8.60217 14.1672 8.5733 14.1613 8.54539C14.1554 8.51749 14.1433 8.4913 14.1258 8.4688C14.1083 8.4463 14.0858 8.42809 14.0602 8.41556C14.0346 8.40303 14.0065 8.39651 13.978 8.39648H11.1888V6.25065C11.1888 6.16584 11.2055 6.08186 11.238 6.0035C11.2704 5.92514 11.318 5.85395 11.378 5.79398C11.4379 5.73401 11.5091 5.68643 11.5875 5.65398C11.6658 5.62152 11.7498 5.60482 11.8346 5.60482H14.0013C14.1046 5.60482 14.1888 5.52148 14.1888 5.41732V2.58398C14.1888 2.48065 14.1055 2.39648 14.0013 2.39648H11.8346Z" fill="#F7F7F7"/></svg>';
    $x_svg      = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g clip-path="url(#clip0_1641_10353)"><mask id="mask0_1641_10353" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="14" height="14"><path d="M0 0H14V14H0V0Z" fill="white"/></mask><g mask="url(#mask0_1641_10353)"><path d="M11.025 0.65625H13.172L8.482 6.03025L14 13.3442H9.68L6.294 8.90925L2.424 13.3442H0.275L5.291 7.59425L0 0.65725H4.43L7.486 4.71025L11.025 0.65625ZM10.27 12.0562H11.46L3.78 1.87725H2.504L10.27 12.0562Z" fill="#F7F7F7"/></g></g><defs><clipPath id="clip0_1641_10353"><rect width="14" height="14" fill="white"/></clipPath></defs></svg>';
    $li_svg     = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3.95833 1.5625C3.48868 1.5625 3.03826 1.74907 2.70617 2.08117C2.37407 2.41326 2.1875 2.86368 2.1875 3.33333C2.1875 3.80299 2.37407 4.25341 2.70617 4.5855C3.03826 4.9176 3.48868 5.10417 3.95833 5.10417C4.42799 5.10417 4.87841 4.9176 5.2105 4.5855C5.5426 4.25341 5.72917 3.80299 5.72917 3.33333C5.72917 2.86368 5.5426 2.41326 5.2105 2.08117C4.87841 1.74907 4.42799 1.5625 3.95833 1.5625ZM2.29167 6.5625C2.26404 6.5625 2.23754 6.57347 2.21801 6.59301C2.19847 6.61255 2.1875 6.63904 2.1875 6.66667V17.5C2.1875 17.5575 2.23417 17.6042 2.29167 17.6042H5.625C5.65263 17.6042 5.67912 17.5932 5.69866 17.5737C5.71819 17.5541 5.72917 17.5276 5.72917 17.5V6.66667C5.72917 6.63904 5.71819 6.61255 5.69866 6.59301C5.67912 6.57347 5.65263 6.5625 5.625 6.5625H2.29167ZM7.70833 6.5625C7.68071 6.5625 7.65421 6.57347 7.63468 6.59301C7.61514 6.61255 7.60417 6.63904 7.60417 6.66667V17.5C7.60417 17.5575 7.65083 17.6042 7.70833 17.6042H11.0417C11.0693 17.6042 11.0958 17.5932 11.1153 17.5737C11.1349 17.5541 11.1458 17.5276 11.1458 17.5V11.6667C11.1458 11.2523 11.3105 10.8548 11.6035 10.5618C11.8965 10.2688 12.2939 10.1042 12.7083 10.1042C13.1227 10.1042 13.5202 10.2688 13.8132 10.5618C14.1062 10.8548 14.2708 11.2523 14.2708 11.6667V17.5C14.2708 17.5575 14.3175 17.6042 14.375 17.6042H17.7083C17.736 17.6042 17.7625 17.5932 17.782 17.5737C17.8015 17.5541 17.8125 17.5276 17.8125 17.5V10.3167C17.8125 8.29417 16.0542 6.7125 14.0417 6.895C13.4191 6.95212 12.8092 7.1062 12.2342 7.35167L11.1458 7.81833V6.66667C11.1458 6.63904 11.1349 6.61255 11.1153 6.59301C11.0958 6.57347 11.0693 6.5625 11.0417 6.5625H7.70833Z" fill="#F7F7F7"/></svg>';
?>

<?php get_template_part( 'template-parts/partials/breadcrumbs' ); ?>

<?php
// ── Hero block — full-width, outside the three-column layout ─────────────────
foreach ( $hero_blocks as $block ) {
    echo render_block( $block );
}
?>

<main class="sp-main">
    <div class="sp-layout">

        <?php /* ══════════════════════════════════════════════════════
           LEFT SIDEBAR
        ══════════════════════════════════════════════════════ */ ?>
        <aside class="sp-sidebar sp-sidebar--left">

            <?php /* ── Subscribe box ── */ ?>
            <?php if ( $sub_show !== false ) : ?>
            <div class="sp-subscribe">
                <p class="sp-subscribe__text"><?php echo esc_html( $sub_text ); ?></p>
                <button
                    type="button"
                    class="sp-subscribe__btn"
                    data-sp-subscribe-open
                ><?php echo esc_html( $sub_btn_label ); ?></button>
            </div>
            <?php endif; ?>

            <?php /* ── AI Summary ── */ ?>
            <?php if ( $ai_show !== false ) : ?>
            <div class="sp-ai">
                <span class="sp-ai__title"><?php esc_html_e( 'AI Summary', 'lionwood' ); ?></span>
                <div class="sp-ai__items">
                    <?php foreach ( $ai_tools as $ai ) : ?>
                        <button
                            class="sp-ai__chip"
                            type="button"
                            data-url="<?php echo esc_url( $ai['url'] ); ?>"
                            data-prompt="<?php echo $ai_prompt; ?>"
                        >
                            <img
                                src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $ai['icon'] ); ?>"
                                alt="<?php echo esc_attr( $ai['name'] ); ?>"
                                width="20"
                                height="20"
                                loading="lazy"
                            >
                            <?php echo esc_html( $ai['name'] ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </aside><!-- .sp-sidebar--left -->

        <?php /* ══════════════════════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════════════════════ */ ?>
        <article class="sp-content" id="sp-content">
            <?php
            foreach ( $content_blocks as $block ) {
                echo render_block( $block );
            }
            ?>
        </article>

        <?php /* ══════════════════════════════════════════════════════
           RIGHT SIDEBAR
        ══════════════════════════════════════════════════════ */ ?>
        <aside class="sp-sidebar sp-sidebar--right">

            <?php /* ── Table of Contents (JS-generated) ── */ ?>
            <div class="sp-toc" id="sp-toc" aria-label="<?php esc_attr_e( 'Table of contents', 'lionwood' ); ?>">
                <div class="sp-toc__head">
                    <?php echo $icon_sections; ?>
                    <span class="sp-toc__label"><?php esc_html_e( 'SECTIONS', 'lionwood' ); ?></span>
                    <button class="sp-toc__toggle" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle sections', 'lionwood' ); ?>">
                        <svg class="sp-toc__arrow sp-toc__arrow--down" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M7.99561 2.66732L7.99561 13.334M11.9956 9.33399L7.99561 13.334L3.99561 9.33398" stroke="#111319" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg class="sp-toc__arrow sp-toc__arrow--up" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M7.99561 13.3327L7.99561 2.66602M3.99561 6.66602L7.99561 2.66602L11.9956 6.66602" stroke="#111319" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="sp-toc__scroll-wrap">
                    <div class="sp-toc__scrollbar" aria-hidden="true">
                        <div class="sp-toc__scrollbar-thumb"></div>
                    </div>
                    <nav class="sp-toc__nav" id="sp-toc-nav"></nav>
                </div>
                <div class="sp-toc__fade" aria-hidden="true"></div>
            </div>

            <?php /* ── Right CTA ── */ ?>
            <?php if ( $cta_show !== false && $cta_text ) : ?>
            <div class="sp-right-cta">
                <p class="sp-right-cta__text"><?php echo esc_html( $cta_text ); ?></p>
                <a
                    class="sp-right-cta__btn"
                    href="<?php echo $cta_url; ?>"
                    target="<?php echo esc_attr( $cta_target ); ?>"
                    <?php echo $cta_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                ><?php echo $cta_label; ?></a>
            </div>
            <?php endif; ?>

            <?php /* ── Share ── */ ?>
            <div class="sp-share">
                <span class="sp-share__title"><?php esc_html_e( 'Share this article', 'lionwood' ); ?></span>
                <div class="sp-share__icons">
                    <a class="sp-share__icon" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <?php echo $fb_svg; ?>
                    </a>
                    <a class="sp-share__icon" href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                        <?php echo $x_svg; ?>
                    </a>
                    <a class="sp-share__icon" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                        <?php echo $li_svg; ?>
                    </a>
                </div>
            </div>

        </aside><!-- .sp-sidebar--right -->

    </div><!-- .sp-layout -->
</main>

<?php if ( ! empty( $bottom_blocks ) ) : ?>
<div class="sp-bottom-sections">
    <?php foreach ( $bottom_blocks as $block ) : ?>
        <?php echo render_block( $block ); ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php /* ── Subscribe popup ─────────────────────────────────────────────── */ ?>
<div
    class="sp-popup"
    id="sp-popup"
    hidden
    role="dialog"
    aria-modal="true"
    aria-label="<?php esc_attr_e( 'Subscribe to newsletter', 'lionwood' ); ?>"
>
    <div class="sp-popup__overlay" data-sp-popup-close aria-hidden="true"></div>
    <div class="sp-popup__card">

        <button
            class="sp-popup__close"
            type="button"
            data-sp-popup-close
            aria-label="<?php esc_attr_e( 'Close', 'lionwood' ); ?>"
        >
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </button>

        <p class="sp-popup__title"><?php esc_html_e( 'Stay in the Loop', 'lionwood' ); ?></p>
        <p class="sp-popup__desc"><?php esc_html_e( 'Get fresh insights and forward-thinking ideas delivered straight to your inbox.', 'lionwood' ); ?></p>

        <form
            class="sp-popup__form"
            data-sp-popup-form
            <?php if ( $hs_portal_id ) : ?>data-hs-portal="<?php echo $hs_portal_id; ?>"<?php endif; ?>
            <?php if ( $hs_subscribe_form ) : ?>data-hs-form="<?php echo $hs_subscribe_form; ?>"<?php endif; ?>
            novalidate
        >
            <input
                class="sp-popup__input"
                type="text"
                name="full_name"
                placeholder="<?php esc_attr_e( 'Full Name *', 'lionwood' ); ?>"
                required
                autocomplete="name"
            >
            <input
                class="sp-popup__input"
                type="email"
                name="email"
                placeholder="<?php esc_attr_e( 'Business Email *', 'lionwood' ); ?>"
                required
                autocomplete="email"
            >
            <p class="sp-popup__terms">
                <?php esc_html_e( 'By clicking the button, I agree to the', 'lionwood' ); ?>
                <a href="<?php echo esc_url( home_url( '/policy-policy/' ) ); ?>" class="sp-popup__terms-link"><?php esc_html_e( 'Terms & Conditions', 'lionwood' ); ?></a>
            </p>
            <button type="submit" class="sp-popup__submit" data-sp-popup-submit>
                <?php esc_html_e( 'Subscribe', 'lionwood' ); ?>
            </button>
            <p class="sp-popup__error" data-sp-popup-error hidden></p>
        </form>

        <div class="sp-popup__success" data-sp-popup-success hidden>
            <div class="sp-popup__success-icon" aria-hidden="true">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 16L13 23L26 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="sp-popup__success-title"><?php esc_html_e( "You're Subscribed!", 'lionwood' ); ?></p>
            <p class="sp-popup__success-text"><?php esc_html_e( 'Thanks for joining. Fresh insights are on their way.', 'lionwood' ); ?></p>
        </div>

    </div>
</div>

<?php
endwhile;
get_footer();
?>
