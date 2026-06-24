<?php
/**
 * Header Options — options sub-page + ACF field group.
 *
 * Polylang: explicit _en / _uk field pairs.
 * Logo is shared; CTA link is per-language (label differs, URL may differ).
 */

defined( 'ABSPATH' ) || exit;

// ── Options sub-page ──────────────────────────────────────────────────────────

add_action( 'acf/init', 'lionwood_register_header_options_page' );
function lionwood_register_header_options_page(): void {
    if ( ! function_exists( 'acf_add_options_sub_page' ) ) return;

    acf_add_options_sub_page( [
        'page_title'  => 'Header Settings',
        'menu_title'  => 'Header',
        'menu_slug'   => 'acf-options-header',
        'parent_slug' => 'theme-options',
        'capability'  => 'edit_theme_options',
        'autoload'    => true,
    ] );
}

// ── ACF field group ───────────────────────────────────────────────────────────

add_action( 'acf/init', 'lionwood_register_header_fields' );
function lionwood_register_header_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_header_options',
        'title'  => 'Header Settings',
        'fields' => [

            // ── Shared tab ────────────────────────────────────────────────────
            [
                'key'   => 'field_hdr_tab_shared',
                'label' => '🌐 Shared (all languages)',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_hdr_logo',
                'label'        => 'Logo',
                'name'         => 'header_logo',
                'type'         => 'image',
                'instructions' => 'Recommended: SVG or PNG, max height 36px. Leave empty to use the WordPress Customizer logo.',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ],

            // ── English tab ───────────────────────────────────────────────────
            [
                'key'   => 'field_hdr_tab_en',
                'label' => '🇬🇧 English',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_hdr_cta_en',
                'label'        => 'CTA Button (EN)',
                'name'         => 'header_cta_link_en',
                'type'         => 'link',
                'instructions' => 'Header CTA button — URL, label and target.',
                'return_format'=> 'array',
            ],

            // ── Ukrainian tab ─────────────────────────────────────────────────
            [
                'key'   => 'field_hdr_tab_uk',
                'label' => '🇺🇦 Українська',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_hdr_cta_uk',
                'label'        => 'CTA Button (UK)',
                'name'         => 'header_cta_link_uk',
                'type'         => 'link',
                'instructions' => 'Кнопка CTA у хедері — URL, підпис та ціль.',
                'return_format'=> 'array',
            ],

        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'acf-options-header',
                ],
            ],
        ],
        'active' => true,
    ] );
}
