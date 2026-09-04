<?php
/**
 * Custom post type registration.
 *
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Property custom post type — archive at /properties/, single at /property/name/.
 */
function estatein_register_property() {
	register_post_type(
		'property',
		array(
			'labels'       => array(
				'name'          => __( 'Properties', 'estatein' ),
				'singular_name' => __( 'Property', 'estatein' ),
				'add_new_item'  => __( 'Add New Property', 'estatein' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'properties' ),
			'menu_icon'    => 'dashicons-building',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest' => true,
		)
	);

	if ( ! get_option( 'estatein_rewrites_flushed' ) ) {
		flush_rewrite_rules();
		update_option( 'estatein_rewrites_flushed', 1 );
	}
}
add_action( 'init', 'estatein_register_property' );

/**
 * Private post type for form submissions (contact and newsletter).
 */
function estatein_register_inquiry_post_type() {
	register_post_type(
		'estatein_inquiry',
		array(
			'labels'              => array(
				'name'          => __( 'Form Submissions', 'estatein' ),
				'singular_name' => __( 'Form Submission', 'estatein' ),
				'view_item'     => __( 'View Submission', 'estatein' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'        => true,
			'menu_icon'           => 'dashicons-email-alt',
			'supports'            => array( 'title' ),
		)
	);
}
add_action( 'init', 'estatein_register_inquiry_post_type' );
