<?php
/**
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load an SVG from assets/icons and make clip/gradient IDs unique per output.
 *
 * @param string $filename SVG filename under assets/icons/.
 * @param string $icon_class CSS classes for the root svg element.
 * @return string
 */
function estatein_icon_svg_file( $filename, $icon_class = 'estatein-icon' ) {
	static $raw_cache = array();

	if ( ! isset( $raw_cache[ $filename ] ) ) {
		$path = get_template_directory() . '/assets/icons/' . $filename;
		if ( ! is_readable( $path ) ) {
			$raw_cache[ $filename ] = '';
		} else {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme asset.
			$raw_cache[ $filename ] = file_get_contents( $path );
		}
	}

	$svg = $raw_cache[ $filename ];
	if ( ! is_string( $svg ) || '' === trim( $svg ) ) {
		return '';
	}

	$suffix = wp_unique_id( 'icon-' );
	$svg    = preg_replace_callback(
		'/id="([^"]+)"/',
		static function ( $matches ) use ( $suffix ) {
			return 'id="' . $matches[1] . $suffix . '"';
		},
		$svg
	);
	$svg    = preg_replace_callback(
		'/url\(#([^)]+)\)/',
		static function ( $matches ) use ( $suffix ) {
			return 'url(#' . $matches[1] . $suffix . ')';
		},
		$svg
	);
	$svg    = preg_replace_callback(
		'/href="#([^"]+)"/',
		static function ( $matches ) use ( $suffix ) {
			return 'href="#' . $matches[1] . $suffix . '"';
		},
		$svg
	);

	if ( preg_match( '/<svg\b/', $svg ) ) {
		$svg = preg_replace(
			'/<svg\b/',
			'<svg class="' . esc_attr( $icon_class ) . '" aria-hidden="true"',
			$svg,
			1
		);
	}

	return $svg;
}

/**
 * SVG icon — inline UI icons plus Figma assets from assets/icons/.
 *
 * File icons: bed, bath, villa, home, building, camera, shop, sun, star, facebook, linkedin, twitter, youtube, pin, property, price, box, calendar.
 *
 * @param string $name Icon name.
 */
function estatein_icon( $name ) {
	$file_icons = array(
		'bed'      => array(
			'file'  => 'icon-bed.svg',
			'class' => 'estatein-icon',
		),
		'bath'     => array(
			'file'  => 'icon-bath.svg',
			'class' => 'estatein-icon',
		),
		'villa'    => array(
			'file'  => 'icon-villa.svg',
			'class' => 'estatein-icon',
		),
		'home'     => array(
			'file'  => 'icon-villa.svg',
			'class' => 'estatein-icon',
		),
		'building' => array(
			'file'  => 'icon-building-purple.svg',
			'class' => 'estatein-icon estatein-icon--badge',
		),
		'camera'   => array(
			'file'  => 'icon-camera-purple.svg',
			'class' => 'estatein-icon estatein-icon--badge',
		),
		'shop'     => array(
			'file'  => 'icon-shop-purple.svg',
			'class' => 'estatein-icon estatein-icon--badge',
		),
		'sun'      => array(
			'file'  => 'icon-sun-purple.svg',
			'class' => 'estatein-icon estatein-icon--badge',
		),
		'star'     => array(
			'file'  => 'icon-star.svg',
			'class' => 'estatein-icon estatein-icon--rating',
		),
		'facebook' => array(
			'file'  => 'icon-facebook.svg',
			'class' => 'estatein-icon estatein-icon--social',
		),
		'linkedin' => array(
			'file'  => 'icon-linkedin.svg',
			'class' => 'estatein-icon estatein-icon--social',
		),
		'twitter'  => array(
			'file'  => 'icon-twitter.svg',
			'class' => 'estatein-icon estatein-icon--social',
		),
		'youtube'  => array(
			'file'  => 'icon-youtube.svg',
			'class' => 'estatein-icon estatein-icon--social',
		),
		'email'    => array(
			'file'  => 'icon-email.svg',
			'class' => 'estatein-icon estatein-icon--newsletter-email',
		),
		'send'     => array(
			'file'  => 'icon-send.svg',
			'class' => 'estatein-icon estatein-icon--newsletter-send',
		),
		'section'  => array(
			'file'  => 'icon-section.svg',
			'class' => 'estatein-icon estatein-icon--section',
		),
		'pin'      => array(
			'file'  => 'icon-pin.svg',
			'class' => 'estatein-icon estatein-icon--filter',
		),
		'property' => array(
			'file'  => 'icon-property.svg',
			'class' => 'estatein-icon estatein-icon--filter',
		),
		'price'    => array(
			'file'  => 'icon-price.svg',
			'class' => 'estatein-icon estatein-icon--filter',
		),
		'box'      => array(
			'file'  => 'icon-box.svg',
			'class' => 'estatein-icon estatein-icon--filter',
		),
		'calendar' => array(
			'file'  => 'icon-calendar.svg',
			'class' => 'estatein-icon estatein-icon--filter',
		),
		'lightning' => array(
			'file'  => 'icon-lightning.svg',
			'class' => 'estatein-icon estatein-icon--lightning',
		),
		'bed-gray'  => array(
			'file'  => 'icon-bed-gray.svg',
			'class' => 'estatein-icon estatein-icon--stat',
		),
		'bath-gray' => array(
			'file'  => 'icon-bath-gray.svg',
			'class' => 'estatein-icon estatein-icon--stat',
		),
		'area-gray' => array(
			'file'  => 'icon-area-gray.svg',
			'class' => 'estatein-icon estatein-icon--stat',
		),
		'email-purple' => array(
			'file'  => 'icon-email-purple.svg',
			'class' => 'estatein-icon estatein-icon--contact',
		),
		'phone-purple' => array(
			'file'  => 'icon-phone-purple.svg',
			'class' => 'estatein-icon estatein-icon--contact',
		),
		'pin-purple'   => array(
			'file'  => 'icon-pin-purple.svg',
			'class' => 'estatein-icon estatein-icon--contact',
		),
		'logo-purple'  => array(
			'file'  => 'icon-logo-purple.svg',
			'class' => 'estatein-icon estatein-icon--contact',
		),
		'email-white'  => array(
			'file'  => 'icon-email-white.svg',
			'class' => 'estatein-icon estatein-icon--pill',
		),
		'phone-white'  => array(
			'file'  => 'icon-phone-white.svg',
			'class' => 'estatein-icon estatein-icon--pill',
		),
		'pin-white'    => array(
			'file'  => 'icon-pin-white.svg',
			'class' => 'estatein-icon estatein-icon--pill',
		),
	);

	if ( isset( $file_icons[ $name ] ) ) {
		echo estatein_icon_svg_file( $file_icons[ $name ]['file'], $file_icons[ $name ]['class'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	$icons = array(
		'sparkle'  => '<svg class="estatein-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 0L11.5 8.5L20 10L11.5 11.5L10 20L8.5 11.5L0 10L8.5 8.5L10 0Z" fill="currentColor"/></svg>',
		'arrow-up' => '<svg class="estatein-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 12L12 4M12 4H6M12 4V10" stroke="currentColor" stroke-width="1.5"/></svg>',
		'chevron'  => '<svg class="estatein-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5"/></svg>',
		'close'    => '<svg class="estatein-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5"/></svg>',
		'search'   => '<svg class="estatein-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5"/><path d="M14 14L18 18" stroke="currentColor" stroke-width="1.5"/></svg>',
		'pin'      => '<svg class="estatein-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1C5.8 1 4 2.8 4 5C4 8.5 8 14 8 14C8 14 12 8.5 12 5C12 2.8 10.2 1 8 1Z" stroke="currentColor" stroke-width="1.5"/><circle cx="8" cy="5" r="1.5" fill="currentColor"/></svg>',
		'envelope' => '<svg class="estatein-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 6L10 11L18 6" stroke="currentColor" stroke-width="1.5"/></svg>',
		'phone'    => '<svg class="estatein-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 3H8L9.5 7.5L7.5 9C8.5 11.5 10.5 13.5 13 14.5L14.5 12.5L19 14V17C19 17.5 18.5 18 18 18C9.5 18 2 10.5 2 2C2 1.5 2.5 1 3 1H5Z" stroke="currentColor" stroke-width="1.5"/></svg>',
	);

	if ( isset( $icons[ $name ] ) ) {
		echo $icons[ $name ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
