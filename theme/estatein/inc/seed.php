<?php
/**
 * Demo content seeded on theme activation.
 *
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create demo pages, properties, and the primary menu if they do not exist.
 */
function estatein_seed_demo() {
	if ( ! post_type_exists( 'property' ) ) {
		estatein_register_property();
	}

	$pages = array(
		'home'    => 'Home',
		'contact' => 'Contact',
	);

	foreach ( $pages as $slug => $title ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post(
				array(
					'post_title'  => $title,
					'post_name'   => $slug,
					'post_status' => 'publish',
					'post_type'   => 'page',
				)
			);
		}
	}

	$home = get_page_by_path( 'home' );
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}

	$properties = array(
		array(
			'title'    => 'Seaside Serenity Villa',
			'content'  => 'Discover your own piece of paradise with the Seaside Serenity Villa. With an open floor plan, breathtaking ocean views from every room, and direct access to a pristine sandy beach.',
			'price'    => 1250000,
			'location' => 'Malibu, California',
			'beds'     => 4,
			'baths'    => 3,
			'area'     => '2,500 Square Feet',
			'type'     => 'Villa',
			'tag'      => 'Coastal Escapes - Where Waves Beckon',
		),
		array(
			'title'    => 'Metropolitan Haven',
			'content'  => 'A modern urban retreat in the heart of the city with stunning skyline views and premium amenities.',
			'price'    => 650000,
			'location' => 'New York, NY',
			'beds'     => 2,
			'baths'    => 2,
			'area'     => '1,200 Square Feet',
			'type'     => 'Apartment',
			'tag'      => 'Urban Oasis - Life in the Heart of the City',
		),
		array(
			'title'    => 'Rustic Retreat Cottage',
			'content'  => 'Escape to nature with this charming countryside cottage surrounded by rolling hills and peaceful landscapes.',
			'price'    => 350000,
			'location' => 'Asheville, NC',
			'beds'     => 3,
			'baths'    => 2.5,
			'area'     => '1,800 Square Feet',
			'type'     => 'Cottage',
			'tag'      => 'Countryside Charm - Escape to Nature\'s Embrace',
		),
	);

	foreach ( $properties as $data ) {
		$existing = get_posts(
			array(
				'post_type'      => 'property',
				'title'          => $data['title'],
				'posts_per_page' => 1,
				'post_status'    => 'any',
			)
		);
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $data['title'],
				'post_content' => $data['content'],
				'post_excerpt' => wp_trim_words( $data['content'], 20 ),
				'post_status'  => 'publish',
				'post_type'    => 'property',
			)
		);

		if ( ! $post_id ) {
			continue;
		}

		$fields = array(
			'price'               => $data['price'],
			'location'            => $data['location'],
			'bedrooms'            => $data['beds'],
			'bathrooms'           => $data['baths'],
			'area'                => $data['area'],
			'property_type_label' => $data['type'],
			'category_tag'        => $data['tag'],
			'is_featured'         => 1,
			'amenities'           => "Expansive oceanfront terrace for outdoor entertaining\nGourmet kitchen with top-of-the-line appliances\nPrivate beach access for morning strolls and sunset views",
		);

		foreach ( $fields as $key => $value ) {
			if ( function_exists( 'update_field' ) ) {
				update_field( $key, $value, $post_id );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	$menu = wp_get_nav_menu_object( 'Primary' );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( 'Primary' );
	} else {
		$menu_id = (int) $menu->term_id;
	}

	if ( $menu_id && ! wp_get_nav_menu_items( $menu_id ) ) {
		$nav = array(
			array(
				'title' => 'Home',
				'url'   => home_url( '/' ),
			),
			array(
				'title' => 'About Us',
				'url'   => '',
			),
			array(
				'title' => 'Properties',
				'url'   => estatein_properties_url(),
			),
			array(
				'title' => 'Services',
				'url'   => '',
			),
		);
		foreach ( $nav as $i => $item ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'    => $item['title'],
					'menu-item-url'      => '' !== $item['url'] ? $item['url'] : '#',
					'menu-item-status'   => 'publish',
					'menu-item-type'     => 'custom',
					'menu-item-position' => $i + 1,
				)
			);
		}
	}

	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	if ( $menu_id ) {
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	if ( false === get_option( 'estatein_settings' ) ) {
		$defaults = function_exists( 'estatein_settings_defaults' )
			? estatein_settings_defaults()
			: array(
				'announcement_text' => 'Discover Your Dream Property with Estatein',
				'contact_email'     => 'info@estatein.com',
				'contact_phone'     => '+1 (123) 456-7890',
				'cta_title'         => 'Start Your Real Estate Journey Today',
				'cta_description'   => 'Your dream property is just a click away. Whether you\'re looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way.',
				'cta_button_text'   => 'Explore Properties',
				'footer_copyright'  => 'All Rights Reserved.',
			);
		update_option( 'estatein_settings', $defaults );
	}

	update_option( 'estatein_seeded', 1 );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'estatein_seed_demo' );
