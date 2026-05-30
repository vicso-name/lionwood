<?php
/**
 * Seed Script: Generate 5 demo Testimonials
 *
 * USAGE:
 *   1. Place this file in your theme root temporarily.
 *   2. Add to functions.php:
 *        require_once get_template_directory() . '/seed-testimonials.php';
 *   3. Load any page in the browser once.
 *   4. REMOVE the require_once line and DELETE this file immediately after.
 *
 * The script runs only once — it writes a transient flag so it won't
 * duplicate entries on subsequent page loads.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

	// Run only once
	if ( get_transient( 'seed_testimonials_done' ) ) {
		return;
	}

	$testimonials = [
		[
			'name'             => 'Jonas Scherf',
			'position'         => 'Online marketing manager, scoutbee',
			'quote'            => '"Thanks to the contributions of the Lionwood.software team, the company saw their web traffic increase by as much as four times than when they started the project. The team\'s flexibility and communication allowed them to cultivate a positive working atmosphere during the partnership."',
			'about'            => 'The client aimed to reduce MVP development time and increase conversion to paid users.',
			'results'          => [
				'<strong>+20%</strong> monthly revenue increase',
				'Market launch <strong>2 months</strong> ahead of schedule',
				'<strong>15%</strong> reduction in operational costs',
			],
		],
		[
			'name'             => 'Maria Kovacs',
			'position'         => 'Head of Product, FinFlow',
			'quote'            => '"The Lionwood team delivered a seamless fintech platform that exceeded our expectations. Their deep understanding of our industry and agile approach made the entire development process smooth and transparent."',
			'about'            => 'The client needed a scalable payment processing module integrated with legacy banking infrastructure.',
			'results'          => [
				'<strong>3×</strong> faster transaction processing',
				'Integration completed in <strong>6 weeks</strong>',
				'<strong>99.97%</strong> uptime since launch',
			],
		],
		[
			'name'             => 'Alex Brunner',
			'position'         => 'CEO, RetailEdge',
			'quote'            => '"Working with Lionwood was a game changer for our e-commerce operations. They built a custom inventory management system that practically eliminated human error and dramatically cut our fulfillment time."',
			'about'            => 'The client needed automation of warehouse workflows and real-time stock synchronisation across 4 warehouses.',
			'results'          => [
				'<strong>40%</strong> reduction in fulfillment errors',
				'Real-time sync across <strong>4 warehouses</strong>',
				'ROI achieved in <strong>4 months</strong>',
			],
		],
		[
			'name'             => 'Sophie Laurent',
			'position'         => 'CTO, MediTrack',
			'quote'            => '"Lionwood built our patient data platform with exceptional attention to compliance and security. The team navigated GDPR and HIPAA requirements without slowing down delivery — impressive from start to finish."',
			'about'            => 'The client required a GDPR and HIPAA-compliant patient records system with role-based access control.',
			'results'          => [
				'Full <strong>GDPR & HIPAA</strong> compliance achieved',
				'<strong>0</strong> security incidents post-launch',
				'Onboarding time reduced by <strong>60%</strong>',
			],
		],
		[
			'name'             => 'Dmitri Olenev',
			'position'         => 'Founder, EduSpark',
			'quote'            => '"The mobile learning app Lionwood delivered for us has been transformative. User engagement skyrocketed and our churn dropped significantly within the first quarter after launch. Highly recommended."',
			'about'            => 'The client aimed to build an engaging mobile-first e-learning platform for B2C and B2B markets.',
			'results'          => [
				'<strong>+65%</strong> daily active users in Q1',
				'Churn reduced by <strong>28%</strong>',
				'App Store rating <strong>4.8 ★</strong>',
			],
		],
	];

	foreach ( $testimonials as $data ) {

		// Create the CPT post (title = reviewer name)
		$post_id = wp_insert_post( [
			'post_type'   => 'testimonial',
			'post_title'  => $data['name'],
			'post_status' => 'publish',
		] );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		// ACF fields
		update_field( 'reviewer_name',     $data['name'],     $post_id );
		update_field( 'reviewer_position', $data['position'], $post_id );
		update_field( 'quote',             $data['quote'],    $post_id );
		update_field( 'about_description', $data['about'],    $post_id );

		// Results repeater
		$results_rows = [];
		foreach ( $data['results'] as $result_text ) {
			$results_rows[] = [
				'icon' => false,         // no custom icon — fallback SVG will be used
				'text' => $result_text,
			];
		}
		update_field( 'results', $results_rows, $post_id );
	}

	// Mark as done — never run again
	set_transient( 'seed_testimonials_done', true, 0 ); // 0 = no expiry
} );
