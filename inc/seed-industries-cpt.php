<?php
/**
 * Seed Script: Create 5 demo Industries
 *
 * USAGE:
 *   1. Place in theme root.
 *   2. Add to functions.php:
 *        require_once get_template_directory() . '/seed-industries-cpt.php';
 *   3. Load any page in browser once.
 *   4. Remove require_once and DELETE this file.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_loaded', function () {

    if ( get_transient( 'seed_industries_cpt_done' ) ) return;

    $industries = [
        [
            'title'   => 'Healthcare',
            'excerpt' => 'We build digital solutions for clinics, telemedicine platforms, and patient management systems that improve care quality and reduce operational costs.',
        ],
        [
            'title'   => 'FinTech',
            'excerpt' => 'From payment gateways to investment platforms — we help financial companies launch secure, scalable, and compliant digital products.',
        ],
        [
            'title'   => 'Retail & E-commerce',
            'excerpt' => 'We develop smart retail solutions — from AI-powered recommendation engines to inventory management systems that drive revenue growth.',
        ],
        [
            'title'   => 'Education',
            'excerpt' => 'Interactive learning platforms, LMS systems, and mobile apps that engage students and help educators deliver better outcomes.',
        ],
        [
            'title'   => 'Logistics & Transport',
            'excerpt' => 'Route optimization, fleet tracking, and supply chain management tools that reduce costs and increase delivery efficiency.',
        ],
    ];

    $created = 0;

    foreach ( $industries as $data ) {
        $post_id = wp_insert_post( [
            'post_type'    => 'industry',
            'post_title'   => $data['title'],
            'post_excerpt' => $data['excerpt'],
            'post_status'  => 'publish',
            'post_content' => '',
        ] );

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            error_log( 'seed-industries-cpt: failed to create "' . $data['title'] . '"' );
            continue;
        }

        $created++;
        error_log( 'seed-industries-cpt: created "' . $data['title'] . '" (ID ' . $post_id . ')' );
    }

    set_transient( 'seed_industries_cpt_done', true, 0 );
    error_log( 'seed-industries-cpt: done — created ' . $created . ' industries' );
} );
