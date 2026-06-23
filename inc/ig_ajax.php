<?php
/**
 * AJAX Handler: Insights Grid
 *
 * Supports filtering by post_type (post / news / whitepaper)
 * and by taxonomy term_ids within that type's category taxonomy.
 */

defined( 'ABSPATH' ) || exit;

// ── Allowed post type → taxonomy map (security whitelist) ─────────────────────
function ig_type_map(): array {
    return [
        'articles'    => [ 'post_type' => 'post',       'taxonomy' => 'category' ],
        'news'        => [ 'post_type' => 'news',        'taxonomy' => 'news_category' ],
        'whitepapers' => [ 'post_type' => 'whitepaper',  'taxonomy' => 'whitepaper_category' ],
    ];
}

if ( ! function_exists( 'smplfy_ig_ajax' ) ) {
    function smplfy_ig_ajax(): void {
        check_ajax_referer( 'ig_ajax', 'nonce' );

        $action_type = sanitize_text_field( $_POST['action_type'] ?? 'load_more' );
        $per_page    = absint( $_POST['per_page'] ?? 6 );
        $offset      = absint( $_POST['offset']   ?? 0 );
        $type_key    = sanitize_key( $_POST['type'] ?? 'articles' );

        $type_map = ig_type_map();
        if ( ! isset( $type_map[ $type_key ] ) ) {
            $type_key = 'articles';
        }
        $config   = $type_map[ $type_key ];
        $taxonomy = $config['taxonomy'];

        // term_ids — JSON array
        $term_ids_raw = sanitize_text_field( $_POST['term_ids'] ?? '[]' );
        $term_ids     = json_decode( $term_ids_raw, true );
        $term_ids     = is_array( $term_ids ) ? array_filter( array_map( 'absint', $term_ids ) ) : [];

        $query_args = [
            'post_type'      => $config['post_type'],
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'offset'         => ( 'filter' === $action_type ) ? 0 : $offset,
        ];

        if ( ! empty( $term_ids ) ) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                    'operator' => 'IN',
                ],
            ];
        }

        $query = new WP_Query( $query_args );
        $posts = $query->posts;
        $total = $query->found_posts;

        if ( empty( $posts ) ) {
            wp_send_json_success( [
                'html'     => '',
                'count'    => 0,
                'total'    => 0,
                'offset'   => 0,
                'has_more' => false,
            ] );
        }

        ob_start();
        foreach ( $posts as $i => $post ) {
            get_template_part( 'template-parts/partials/insights-card', null, [
                'post_id'  => $post->ID,
                'featured' => ( 'filter' === $action_type && $i === 0 ),
            ] );
        }
        $html = ob_get_clean();

        $new_offset = ( 'filter' === $action_type ) ? $per_page : $offset + count( $posts );

        wp_send_json_success( [
            'html'     => $html,
            'count'    => count( $posts ),
            'total'    => $total,
            'offset'   => $new_offset,
            'has_more' => $new_offset < $total,
        ] );
    }
}

add_action( 'wp_ajax_ig_ajax',        'smplfy_ig_ajax' );
add_action( 'wp_ajax_nopriv_ig_ajax', 'smplfy_ig_ajax' );

if ( ! function_exists( 'smplfy_ig_localize' ) ) {
    function smplfy_ig_localize(): void {
        wp_add_inline_script(
            'theme-main',
            'window.igAjax = ' . wp_json_encode( [
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'ig_ajax' ),
            ] ) . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'smplfy_ig_localize' );
