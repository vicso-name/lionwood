<?php
/**
 * AI Summary — options sub-page + ACF field group.
 *
 * Polylang: explicit _en / _uk field pairs following the project pattern
 * (see group_author_profile.json). PHP reads the correct field based on
 * pll_current_language() in single.php.
 */

defined( 'ABSPATH' ) || exit;

// ── Options sub-page ──────────────────────────────────────────────────────────

add_action( 'acf/init', 'lionwood_register_ai_options_page' );
function lionwood_register_ai_options_page(): void {
    if ( ! function_exists( 'acf_add_options_sub_page' ) ) return;

    acf_add_options_sub_page( [
        'page_title'  => 'AI Summary Settings',
        'menu_title'  => 'AI Summary',
        'menu_slug'   => 'acf-options-ai-summary',
        'parent_slug' => 'theme-options',
        'capability'  => 'edit_theme_options',
        'autoload'    => true,
    ] );
}

// ── ACF field group ───────────────────────────────────────────────────────────

add_action( 'acf/init', 'lionwood_register_ai_fields' );
function lionwood_register_ai_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_ai_summary_options',
        'title'  => 'AI Summary Settings',
        'fields' => [

            // ── Shared tab ────────────────────────────────────────────────────
            [
                'key'   => 'field_ai_tab_shared',
                'label' => '🌐 Shared (all languages)',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'           => 'field_ai_chatgpt_url',
                'label'         => 'ChatGPT URL',
                'name'          => 'ai_chatgpt_url',
                'type'          => 'url',
                'default_value' => 'https://chatgpt.com',
            ],
            [
                'key'           => 'field_ai_perplexity_url',
                'label'         => 'Perplexity URL',
                'name'          => 'ai_perplexity_url',
                'type'          => 'url',
                'default_value' => 'https://www.perplexity.ai',
            ],
            [
                'key'           => 'field_ai_google_url',
                'label'         => 'Google AI URL',
                'name'          => 'ai_google_url',
                'type'          => 'url',
                'default_value' => 'https://gemini.google.com',
            ],

            // ── English tab ───────────────────────────────────────────────────
            [
                'key'   => 'field_ai_tab_en',
                'label' => '🇬🇧 English',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_ai_summary_default_prompt_en',
                'label'        => 'Default Prompt (EN)',
                'name'         => 'ai_summary_default_prompt_en',
                'type'         => 'textarea',
                'instructions' => 'Use {url} as a placeholder — it will be replaced with the post URL automatically.',
                'rows'         => 3,
                'new_lines'    => '',
            ],

            // ── Ukrainian tab ─────────────────────────────────────────────────
            [
                'key'   => 'field_ai_tab_uk',
                'label' => '🇺🇦 Українська',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_ai_summary_default_prompt_uk',
                'label'        => 'Default Prompt (UK)',
                'name'         => 'ai_summary_default_prompt_uk',
                'type'         => 'textarea',
                'instructions' => 'Використовуй {url} як плейсхолдер — буде замінено на URL статті автоматично.',
                'rows'         => 3,
                'new_lines'    => '',
            ],

        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'acf-options-ai-summary',
                ],
            ],
        ],
        'active' => true,
    ] );
}
