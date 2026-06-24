<?php
/**
 * AJAX Handler: Choose Cases Grid
 * Supports multi-select filter (term_ids = JSON array).
 *
 * File: inc/ajax/ccg-ajax.php
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'smplfy_ccg_ajax' ) ) {
    function smplfy_ccg_ajax(): void {
        check_ajax_referer( 'ccg_ajax', 'nonce' );

        $action_type = sanitize_text_field( $_POST['action_type'] ?? 'load_more' );
        $per_page    = absint( $_POST['per_page'] ?? 6 );
        $offset      = absint( $_POST['offset']   ?? 0 );
        $taxonomy    = sanitize_key( $_POST['taxonomy'] ?? '' );

        $allowed_taxonomies = [ 'case_study_category', 'case_study_service' ];
        if ( $taxonomy && ! in_array( $taxonomy, $allowed_taxonomies, true ) ) {
            wp_send_json_error( [ 'message' => 'invalid_taxonomy' ], 400 );
        }

        // term_ids comes as JSON array string
        $term_ids_raw = sanitize_text_field( $_POST['term_ids'] ?? '[]' );
        $term_ids     = json_decode( $term_ids_raw, true );
        $term_ids     = is_array( $term_ids ) ? array_map( 'absint', $term_ids ) : [];
        $term_ids     = array_filter( $term_ids ); // remove zeros

        // Base query
        $query_args = [
            'post_type'      => 'case_study',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'offset'         => ( 'filter' === $action_type ) ? 0 : $offset,
        ];

        // Multi-select tax_query: IN operator
        if ( $taxonomy && ! empty( $term_ids ) ) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                    'operator' => 'IN', // case must have AT LEAST ONE of selected terms
                ],
            ];
        }

        $query = new WP_Query( $query_args );
        $cases = $query->posts;
        $total = $query->found_posts;

        if ( empty( $cases ) ) {
            wp_send_json_success( [
                'html'     => '',
                'count'    => 0,
                'total'    => 0,
                'offset'   => 0,
                'has_more' => false,
            ] );
        }

        ob_start();
        foreach ( $cases as $case ) {
            get_template_part( 'template-parts/partials/case-card', null, [ 'case_id' => $case->ID ] );
        }
        $html = ob_get_clean();

        $new_offset = ( 'filter' === $action_type ) ? $per_page : $offset + count( $cases );

        wp_send_json_success( [
            'html'     => $html,
            'count'    => count( $cases ),
            'total'    => $total,
            'offset'   => $new_offset,
            'has_more' => $new_offset < $total,
        ] );
    }
}

add_action( 'wp_ajax_ccg_ajax',        'smplfy_ccg_ajax' );
add_action( 'wp_ajax_nopriv_ccg_ajax', 'smplfy_ccg_ajax' );

if ( ! function_exists( 'smplfy_ccg_localize' ) ) {
    function smplfy_ccg_localize(): void {
        wp_add_inline_script(
            'theme-main',
            'window.ccgAjax = ' . wp_json_encode( [
                'url'        => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'ccg_ajax' ),
                'archiveUrl' => get_post_type_archive_link( 'case_study' ) ?: '/case-study/',
            ] ) . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'smplfy_ccg_localize' );
