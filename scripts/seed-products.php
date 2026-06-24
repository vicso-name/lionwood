<?php
/**
 * Seed: create 3 `product` CPT posts cloned from a source page.
 *
 * Usage (run from WordPress root):
 *   wp eval-file wp-content/themes/lionwood/scripts/seed-products.php
 *
 * Optional: override source ID and titles via constants before the include,
 * or just edit SOURCE_POST_ID / $titles below.
 *
 * Safe to inspect first — reads only; products are created only when you
 * confirm by passing --yes:
 *   wp eval-file wp-content/themes/lionwood/scripts/seed-products.php --yes
 */

defined( 'ABSPATH' ) || exit( "Run via WP-CLI: wp eval-file <path-to-this-file>\n" );

// ── Config ────────────────────────────────────────────────────────────────────

$source_id = 2480;

$titles = [
    'Product One',
    'Product Two',
    'Product Three',
];

// ── Dry-run guard ─────────────────────────────────────────────────────────────

$confirmed = in_array( '--yes', $GLOBALS['argv'] ?? [], true );

// ── Read source ───────────────────────────────────────────────────────────────

$source = get_post( $source_id );

if ( ! $source ) {
    WP_CLI::error( "Post {$source_id} not found. Check the source ID." );
}

WP_CLI::log( "Source post : [{$source->ID}] "{$source->post_title}" (type: {$source->post_type})" );
WP_CLI::log( "Target type : product" );
WP_CLI::log( "Products to create: " . implode( ', ', array_map( fn( $t ) => '"' . $t . '"', $titles ) ) );

if ( ! $confirmed ) {
    WP_CLI::log( '' );
    WP_CLI::log( 'Dry run — no posts created. Re-run with --yes to seed.' );
    exit( 0 );
}

// ── Copy postmeta helper ──────────────────────────────────────────────────────

function seed_copy_meta( int $from_id, int $to_id ): void {
    $all_meta = get_post_meta( $from_id );

    foreach ( $all_meta as $key => $values ) {
        // Skip internal WP keys that must not be duplicated
        if ( in_array( $key, [ '_edit_lock', '_edit_last', '_wp_old_slug', '_wp_old_date' ], true ) ) {
            continue;
        }

        foreach ( $values as $value ) {
            // get_post_meta returns unserialized values; add_post_meta re-serializes if needed
            add_post_meta( $to_id, $key, maybe_unserialize( $value ) );
        }
    }
}

// ── Create products ───────────────────────────────────────────────────────────

$created = [];

foreach ( $titles as $title ) {
    $new_id = wp_insert_post( [
        'post_title'   => $title,
        'post_content' => $source->post_content,
        'post_excerpt' => $source->post_excerpt,
        'post_status'  => 'publish',
        'post_type'    => 'product',
        'post_author'  => get_current_user_id() ?: 1,
    ], true );

    if ( is_wp_error( $new_id ) ) {
        WP_CLI::warning( "Failed to create "{$title}": " . $new_id->get_error_message() );
        continue;
    }

    seed_copy_meta( $source_id, $new_id );

    // Copy featured image if set
    $thumb_id = get_post_thumbnail_id( $source_id );
    if ( $thumb_id ) {
        set_post_thumbnail( $new_id, $thumb_id );
    }

    $edit_url = admin_url( "post.php?post={$new_id}&action=edit" );
    WP_CLI::success( "Created "{$title}" → ID {$new_id}  {$edit_url}" );

    $created[] = $new_id;
}

WP_CLI::log( '' );
WP_CLI::log( 'Done. Created ' . count( $created ) . ' product(s).' );
WP_CLI::log( 'Edit titles, slugs, and ACF fields in the admin before publishing to production.' );
