<?php
/**
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF property field group (Free).
 */
// function estatein_acf_init() {
// 	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
// 		return;
// 	}

// 	acf_add_local_field_group(
// 		array(
// 			'key'      => 'group_estatein_property',
// 			'title'    => __( 'Property Details', 'estatein' ),
// 			'fields'   => array(
// 				array(
// 					'key'      => 'field_property_price',
// 					'label'    => __( 'Price', 'estatein' ),
// 					'name'     => 'price',
// 					'type'     => 'number',
// 					'required' => 1,
// 				),
// 				array(
// 					'key'   => 'field_property_location',
// 					'label' => __( 'Location', 'estatein' ),
// 					'name'  => 'location',
// 					'type'  => 'text',
// 				),
// 				array(
// 					'key'   => 'field_property_bedrooms',
// 					'label' => __( 'Bedrooms', 'estatein' ),
// 					'name'  => 'bedrooms',
// 					'type'  => 'number',
// 				),
// 				array(
// 					'key'   => 'field_property_bathrooms',
// 					'label' => __( 'Bathrooms', 'estatein' ),
// 					'name'  => 'bathrooms',
// 					'type'  => 'number',
// 				),
// 				array(
// 					'key'   => 'field_property_area',
// 					'label' => __( 'Area', 'estatein' ),
// 					'name'  => 'area',
// 					'type'  => 'text',
// 				),
// 				array(
// 					'key'   => 'field_property_type_label',
// 					'label' => __( 'Property Type Label', 'estatein' ),
// 					'name'  => 'property_type_label',
// 					'type'  => 'text',
// 				),
// 				array(
// 					'key'   => 'field_property_category_tag',
// 					'label' => __( 'Category Tag', 'estatein' ),
// 					'name'  => 'category_tag',
// 					'type'  => 'text',
// 				),
// 				array(
// 					'key'   => 'field_property_is_featured',
// 					'label' => __( 'Featured Property', 'estatein' ),
// 					'name'  => 'is_featured',
// 					'type'  => 'true_false',
// 					'ui'    => 1,
// 				),
// 				array(
// 					'key'          => 'field_property_amenities',
// 					'label'        => __( 'Amenities', 'estatein' ),
// 					'name'         => 'amenities',
// 					'type'         => 'textarea',
// 					'instructions' => __( 'One amenity per line.', 'estatein' ),
// 					'rows'         => 6,
// 				),
// 				array(
// 					'key'           => 'field_property_gallery_image_1',
// 					'label'         => __( 'Gallery image 1', 'estatein' ),
// 					'name'          => 'gallery_image_1',
// 					'type'          => 'image',
// 					'return_format' => 'url',
// 				),
// 				array(
// 					'key'           => 'field_property_gallery_image_2',
// 					'label'         => __( 'Gallery image 2', 'estatein' ),
// 					'name'          => 'gallery_image_2',
// 					'type'          => 'image',
// 					'return_format' => 'url',
// 				),
// 				array(
// 					'key'           => 'field_property_gallery_image_3',
// 					'label'         => __( 'Gallery image 3', 'estatein' ),
// 					'name'          => 'gallery_image_3',
// 					'type'          => 'image',
// 					'return_format' => 'url',
// 				),
// 			),
// 			'location' => array(
// 				array(
// 					array(
// 						'param'    => 'post_type',
// 						'operator' => '==',
// 						'value'    => 'property',
// 					),
// 				),
// 			),
// 		)
// 	);
// }
// add_action( 'acf/init', 'estatein_acf_init' );

/**
 * Warn if ACF is missing.
 */
function estatein_acf_notice() {
	if ( ! current_user_can( 'activate_plugins' ) || function_exists( 'get_field' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>';
	esc_html_e( 'Estatein: install and activate Advanced Custom Fields to edit property details in the admin.', 'estatein' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'estatein_acf_notice' );

/**
 * Get an ACF field, then post meta.
 *
 * @param string           $key     Field name.
 * @param int|string|false $post_id Post ID or "option".
 * @return mixed
 */
function estatein_field( $key, $post_id = false ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, $post_id );
		if ( null !== $value && false !== $value && '' !== $value ) {
			return $value;
		}
	}

	if ( $post_id && is_numeric( $post_id ) ) {
		$meta = get_post_meta( $post_id, $key, true );
		if ( '' !== $meta && false !== $meta ) {
			return $meta;
		}
	}

	return '';
}

/**
 * Normalize an ACF image field, attachment ID, or URL to an image URL.
 *
 * @param mixed  $value Attachment ID, URL string, or ACF image array.
 * @param string $size  WordPress image size.
 * @return string
 */
function estatein_image_url( $value, $size = 'estatein-gallery' ) {
	if ( empty( $value ) && 0 !== $value && '0' !== $value ) {
		return '';
	}

	if ( is_string( $value ) ) {
		if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return $value;
		}
		if ( is_numeric( $value ) ) {
			$value = (int) $value;
		}
	}

	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, $size );
		return $url ? $url : '';
	}

	if ( is_array( $value ) ) {
		if ( ! empty( $value['url'] ) && is_string( $value['url'] ) ) {
			return $value['url'];
		}
		if ( ! empty( $value['ID'] ) ) {
			return estatein_image_url( $value['ID'], $size );
		}
		if ( ! empty( $value['id'] ) ) {
			return estatein_image_url( $value['id'], $size );
		}
	}

	return '';
}

/**
 * Build property gallery URLs: featured image, then gallery_image_1–3.
 *
 * @param int $post_id Property post ID.
 * @return string[]
 */
function estatein_property_gallery_images( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$images  = array();

	$featured = get_the_post_thumbnail_url( $post_id, 'estatein-gallery' );
	if ( $featured ) {
		$images[] = $featured;
	}

	foreach ( array( 'gallery_image_1', 'gallery_image_2', 'gallery_image_3' ) as $field_key ) {
		$url = estatein_image_url( estatein_field( $field_key, $post_id ), 'estatein-gallery' );
		if ( $url ) {
			$images[] = $url;
		}
	}

	return array_values( array_unique( $images ) );
}

/**
 * Site setting from Estatein Settings plugin, then theme defaults.
 *
 * @param string $key Field name.
 * @return mixed
 */
function estatein_option( $key ) {
	if ( function_exists( 'estatein_get_setting' ) ) {
		$value = estatein_get_setting( $key );
		if ( 'cta_button_url' === $key || 'announcement_link' === $key ) {
			return ( '' !== $value ) ? $value : estatein_properties_url();
		}
		if ( '' !== $value && null !== $value && false !== $value ) {
			return $value;
		}
	}

	$defaults = array(
		'announcement_text' => 'Discover Your Dream Property with Estatein',
		'announcement_link' => '',
		'contact_email'     => 'info@estatein.com',
		'contact_phone'     => '+1 (123) 456-7890',
		'social_facebook'   => '#',
		'social_linkedin'   => '#',
		'social_twitter'    => '#',
		'social_youtube'    => '#',
		'social_instagram'  => '#',
		'cta_title'         => 'Start Your Real Estate Journey Today',
		'cta_description'   => 'Your dream property is just a click away. Whether you\'re looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way.',
		'cta_button_text'   => 'Explore Properties',
		'cta_button_url'    => '',
		'footer_copyright'  => 'All Rights Reserved.',
	);

	if ( 'cta_button_url' === $key || 'announcement_link' === $key ) {
		return estatein_properties_url();
	}

	return $defaults[ $key ] ?? '';
}

/**
 * Page URL by slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function estatein_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * Properties archive URL.
 *
 * @return string
 */
function estatein_properties_url() {
	$archive = get_post_type_archive_link( 'property' );
	if ( $archive ) {
		return $archive;
	}
	return home_url( '/properties/' );
}

/**
 * Location options for property archive filters.
 *
 * @return array<string, string>
 */
function estatein_property_filter_locations() {
	return array(
		'Malibu, California' => __( 'Malibu, California', 'estatein' ),
		'New York, NY'       => __( 'New York, NY', 'estatein' ),
		'Asheville, NC'      => __( 'Asheville, NC', 'estatein' ),
	);
}

/**
 * Property type options for archive filters.
 *
 * @return array<string, string>
 */
function estatein_property_filter_types() {
	return array(
		'Villa'     => __( 'Villa', 'estatein' ),
		'Apartment' => __( 'Apartment', 'estatein' ),
		'Cottage'   => __( 'Cottage', 'estatein' ),
	);
}

/**
 * Price range options for archive filters.
 *
 * @return array<string, string>
 */
function estatein_property_filter_price_ranges() {
	return array(
		'under-500k' => __( 'Under $500,000', 'estatein' ),
		'500k-1m'    => __( '$500,000 – $1,000,000', 'estatein' ),
		'1m-plus'    => __( '$1,000,000+', 'estatein' ),
	);
}

/**
 * Build a property query from search/filter params.
 *
 * @param array $params q, location, type, price keys.
 * @return array
 */
function estatein_property_search_args( $params = array() ) {
	$params = wp_parse_args(
		$params,
		array(
			'q'        => '',
			'location' => '',
			'type'     => '',
			'price'    => '',
		)
	);

	$args = array(
		'post_type'      => 'property',
		'posts_per_page' => 12,
		'post_status'    => 'publish',
	);

	if ( $params['q'] ) {
		$args['s'] = sanitize_text_field( $params['q'] );
	}

	$meta_query = array();

	if ( $params['location'] && isset( estatein_property_filter_locations()[ $params['location'] ] ) ) {
		$meta_query[] = array(
			'key'     => 'location',
			'value'   => $params['location'],
			'compare' => '=',
		);
	}

	if ( $params['type'] && isset( estatein_property_filter_types()[ $params['type'] ] ) ) {
		$meta_query[] = array(
			'key'     => 'property_type_label',
			'value'   => $params['type'],
			'compare' => '=',
		);
	}

	if ( $params['price'] && isset( estatein_property_filter_price_ranges()[ $params['price'] ] ) ) {
		switch ( $params['price'] ) {
			case 'under-500k':
				$meta_query[] = array(
					'key'     => 'price',
					'value'   => 500000,
					'type'    => 'NUMERIC',
					'compare' => '<',
				);
				break;
			case '500k-1m':
				$meta_query[] = array(
					'key'     => 'price',
					'value'   => array( 500000, 999999.99 ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				);
				break;
			case '1m-plus':
				$meta_query[] = array(
					'key'     => 'price',
					'value'   => 1000000,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);
				break;
		}
	}

	if ( $meta_query ) {
		$meta_query['relation'] = 'AND';
		$args['meta_query']     = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	}

	return $args;
}

/**
 * Format a numeric price.
 *
 * @param mixed $price Price value.
 * @return string
 */
function estatein_format_price( $price ) {
	$price = floatval( $price );
	if ( $price <= 0 ) {
		return '';
	}
	return '$' . number_format( $price );
}

/**
 * Split newline text into a list.
 *
 * @param string $text Raw text.
 * @return string[]
 */
function estatein_lines( $text ) {
	if ( ! is_string( $text ) || '' === trim( $text ) ) {
		return array();
	}
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $text ) ) ) );
}

/**
 * Truncate excerpt.
 *
 * @param string $text   Text.
 * @param int    $length Max length.
 * @return string
 */
function estatein_excerpt( $text, $length = 100 ) {
	$text = wp_strip_all_tags( $text );
	if ( strlen( $text ) <= $length ) {
		return $text;
	}
	return substr( $text, 0, $length ) . '...';
}
