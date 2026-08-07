<?php
/**
 * AJAX Handler: Solutions Grid
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'lionwood_sg_ajax' ) ) {
    function lionwood_sg_ajax(): void {
        check_ajax_referer( 'sg_ajax', 'nonce' );

        $action_type = sanitize_text_field( $_POST['action_type'] ?? 'load_more' );
        $per_page    = absint( $_POST['per_page'] ?? 6 );
        $offset      = absint( $_POST['offset']   ?? 0 );

        $term_ids_raw = sanitize_text_field( $_POST['term_ids'] ?? '[]' );
        $term_ids     = json_decode( $term_ids_raw, true );
        $term_ids     = is_array( $term_ids ) ? array_filter( array_map( 'absint', $term_ids ) ) : [];

        $query_args = [
            'post_type'      => 'solution',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'offset'         => ( 'filter' === $action_type ) ? 0 : $offset,
        ];

        if ( ! empty( $term_ids ) ) {
            $query_args['tax_query'] = [ [
                'taxonomy' => 'solution_category',
                'field'    => 'term_id',
                'terms'    => $term_ids,
                'operator' => 'IN',
            ] ];
        }

        $query     = new WP_Query( $query_args );
        $solutions = $query->posts;
        $total     = $query->found_posts;

        if ( empty( $solutions ) ) {
            wp_send_json_success( [ 'html' => '', 'count' => 0, 'total' => 0, 'offset' => 0, 'has_more' => false ] );
        }

        ob_start();
        foreach ( $solutions as $sol ) {
            get_template_part( 'template-parts/partials/solution-card', null, [ 'post_id' => $sol->ID ] );
        }
        $html = ob_get_clean();

        $new_offset = ( 'filter' === $action_type ) ? $per_page : $offset + count( $solutions );

        wp_send_json_success( [
            'html'     => $html,
            'count'    => count( $solutions ),
            'total'    => $total,
            'offset'   => $new_offset,
            'has_more' => $new_offset < $total,
        ] );
    }
}

add_action( 'wp_ajax_sg_ajax',        'lionwood_sg_ajax' );
add_action( 'wp_ajax_nopriv_sg_ajax', 'lionwood_sg_ajax' );

if ( ! function_exists( 'lionwood_sg_localize' ) ) {
    function lionwood_sg_localize(): void {
        wp_add_inline_script(
            'btf-main-scripts',
            'window.sgAjax = ' . wp_json_encode( [
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'sg_ajax' ),
            ] ) . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'lionwood_sg_localize' );
