<?php
/**
 * Template: Case Study Archive
 *
 * URL: /case-study/
 * All cases, no active filter.
 * Section content sourced from Cases → Case Archive options page.
 */

get_header();

get_template_part( 'template-parts/sections/case_hero', null, [
    'padding_top'          => get_field( 'cao_ch_padding_top',          'option' ) ?: 100,
    'padding_bottom'       => get_field( 'cao_ch_padding_bottom',       'option' ) ?: 100,
    'padding_top_mob'      => get_field( 'cao_ch_padding_top_mob',      'option' ) ?: 70,
    'padding_bottom_mob'   => get_field( 'cao_ch_padding_bottom_mob',   'option' ) ?: 70,
    'title_dark'           => get_field( 'cao_ch_title_dark',           'option' ) ?: __( 'Case', 'lionwood' ),
    'title_gray'           => get_field( 'cao_ch_title_gray',           'option' ) ?: __( 'Studies', 'lionwood' ),
    'description'          => get_field( 'cao_ch_description',          'option' ) ?: '',
    'decor_bottom_enabled' => get_field( 'cao_ch_decor_bottom_enabled', 'option' ),
    'decor_bottom_color'   => get_field( 'cao_ch_decor_bottom_color',   'option' ) ?: '#ffffff',
] );

get_template_part( 'template-parts/cases/cases-listing', null, [
    'padding_top'          => get_field( 'cao_ccg_padding_top',          'option' ) ?: 100,
    'padding_bottom'       => get_field( 'cao_ccg_padding_bottom',       'option' ) ?: 100,
    'padding_top_mob'      => get_field( 'cao_ccg_padding_top_mob',      'option' ) ?: 70,
    'padding_bottom_mob'   => get_field( 'cao_ccg_padding_bottom_mob',   'option' ) ?: 70,
    'marquee_text'         => get_field( 'cao_ccg_marquee_text',         'option' ) ?: __( 'Successful Products', 'lionwood' ),
    'decor_bottom_enabled' => get_field( 'cao_ccg_decor_bottom_enabled', 'option' ),
    'decor_bottom_color'   => get_field( 'cao_ccg_decor_bottom_color',   'option' ) ?: '#ffffff',
] );

get_template_part( 'template-parts/sections/cta_section', null, [
    'padding_top'          => get_field( 'cao_cta_padding_top',          'option' ) ?: 100,
    'padding_bottom'       => get_field( 'cao_cta_padding_bottom',       'option' ) ?: 100,
    'padding_top_mob'      => get_field( 'cao_cta_padding_top_mob',      'option' ) ?: 70,
    'padding_bottom_mob'   => get_field( 'cao_cta_padding_bottom_mob',   'option' ) ?: 70,
    'title_top'            => get_field( 'cao_cta_title_top',            'option' ) ?: __( 'Ready to Accelerate', 'lionwood' ),
    'title_bottom'         => get_field( 'cao_cta_title_bottom',         'option' ) ?: '',
    'grid_text_1'          => get_field( 'cao_cta_grid_text_1',          'option' ) ?: '',
    'grid_text_2'          => get_field( 'cao_cta_grid_text_2',          'option' ) ?: '',
    'grid_text_3'          => get_field( 'cao_cta_grid_text_3',          'option' ) ?: '',
    'card_bg'              => get_field( 'cao_cta_card_bg',              'option' ),
    'card_text'            => get_field( 'cao_cta_card_text',            'option' ) ?: '',
    'card_link'            => get_field( 'cao_cta_card_link',            'option' ),
    'decor_bottom_enabled' => get_field( 'cao_cta_decor_bottom_enabled', 'option' ),
    'decor_bottom_color'   => get_field( 'cao_cta_decor_bottom_color',   'option' ) ?: '#ffffff',
] );

get_footer();
