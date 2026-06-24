<?php
/**
 * Error Page (404) — options sub-page + ACF field group.
 *
 * Polylang: explicit _en / _uk field pairs following the project pattern
 * (see group_author_profile.json, inc/acf-ai-fields.php).
 * PHP reads the correct field based on pll_current_language() in 404.php.
 */

defined( 'ABSPATH' ) || exit;

// ── Options sub-page ──────────────────────────────────────────────────────────

add_action( 'acf/init', 'lionwood_register_error_page_options_page' );
function lionwood_register_error_page_options_page(): void {
    if ( ! function_exists( 'acf_add_options_sub_page' ) ) return;

    acf_add_options_sub_page( [
        'page_title'  => 'Error Page Settings',
        'menu_title'  => 'Error Page',
        'menu_slug'   => 'error-page',
        'parent_slug' => 'theme-options',
        'capability'  => 'edit_theme_options',
        'autoload'    => true,
    ] );
}

// ── ACF field group ───────────────────────────────────────────────────────────

add_action( 'acf/init', 'lionwood_register_error_page_fields' );
function lionwood_register_error_page_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_error_page_options',
        'title'  => 'Error Page Settings',
        'fields' => [

            // ── Shared tab ────────────────────────────────────────────────────
            [
                'key'   => 'field_ep_tab_shared',
                'label' => '🌐 Shared (all languages)',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_ep_image',
                'label'        => '404 Image',
                'name'         => '404_image',
                'type'         => 'image',
                'instructions' => 'Illustration shown on the 404 page. Leave empty to display the "404" number instead.',
                'return_format'=> 'array',
                'preview_size' => 'medium',
                'library'      => 'all',
            ],
            [
                'key'          => 'field_ep_btn_url',
                'label'        => 'Button URL',
                'name'         => '404_btn_url',
                'type'         => 'url',
                'instructions' => 'Leave empty to use the site homepage.',
            ],

            // ── English tab ───────────────────────────────────────────────────
            [
                'key'   => 'field_ep_tab_en',
                'label' => '🇬🇧 English',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_ep_text_en',
                'label'        => '404 Text (EN)',
                'name'         => '404_text_en',
                'type'         => 'textarea',
                'instructions' => 'Message shown below the 404 image.',
                'rows'         => 3,
                'new_lines'    => 'br',
            ],
            [
                'key'          => 'field_ep_btn_label_en',
                'label'        => 'Button Label (EN)',
                'name'         => '404_btn_label_en',
                'type'         => 'text',
                'default_value'=> 'Back to Home',
            ],

            // ── Ukrainian tab ─────────────────────────────────────────────────
            [
                'key'   => 'field_ep_tab_uk',
                'label' => '🇺🇦 Українська',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'          => 'field_ep_text_uk',
                'label'        => '404 Text (UK)',
                'name'         => '404_text_uk',
                'type'         => 'textarea',
                'instructions' => 'Повідомлення під зображенням 404.',
                'rows'         => 3,
                'new_lines'    => 'br',
            ],
            [
                'key'          => 'field_ep_btn_label_uk',
                'label'        => 'Button Label (UK)',
                'name'         => '404_btn_label_uk',
                'type'         => 'text',
                'default_value'=> 'На головну',
            ],

        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'error-page',
                ],
            ],
        ],
        'active' => true,
    ] );
}
