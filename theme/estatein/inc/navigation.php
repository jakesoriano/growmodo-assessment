<?php
/**
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Style wp_nav_menu links to match the Figma header.
 *
 * @param array    $atts HTML attributes.
 * @param WP_Post  $item Menu item.
 * @param stdClass $args wp_nav_menu args.
 * @return array
 */
function estatein_nav_link_atts( $atts, $item, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $atts;
	}

	$class  = 'estatein-header__nav-link';
	$active = array_intersect( array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor' ), (array) $item->classes );
	if ( $active ) {
		$class .= ' estatein-header__nav-link--active';
	}

	if ( estatein_is_placeholder_nav_item( $item->title ) ) {
		$class         .= ' estatein-header__nav-link--placeholder';
		$atts['href']   = '#';
		$atts['role']   = 'link';
		$atts['aria-disabled'] = 'true';
		$atts['tabindex']      = '-1';
	}

	$atts['class'] = $class;
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'estatein_nav_link_atts', 10, 3 );

/**
 * Whether a nav label is a non-linking placeholder (About Us / Services).
 *
 * @param string $title Menu item title.
 * @return bool
 */
function estatein_is_placeholder_nav_item( $title ) {
	return in_array( strtolower( trim( wp_strip_all_tags( (string) $title ) ) ), array( 'about us', 'services' ), true );
}

/**
 * Strip real URLs from placeholder primary-nav items.
 *
 * @param string   $item_output Full item HTML.
 * @param WP_Post  $item        Menu item.
 * @param int      $depth       Depth.
 * @param stdClass $args        Menu args.
 * @return string
 */
function estatein_placeholder_nav_item( $item_output, $item, $depth, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $item_output;
	}
	if ( ! estatein_is_placeholder_nav_item( $item->title ) ) {
		return $item_output;
	}

	return sprintf(
		'<span class="estatein-header__nav-link estatein-header__nav-link--placeholder">%s</span>',
		esc_html( $item->title )
	);
}
add_filter( 'walker_nav_menu_start_el', 'estatein_placeholder_nav_item', 10, 4 );

/**
 * Fallback nav when no menu is assigned yet.
 */
function estatein_fallback_menu() {
	$items = array(
		array(
			'label'  => __( 'Home', 'estatein' ),
			'url'    => home_url( '/' ),
			'active' => is_front_page(),
		),
		array(
			'label' => __( 'About Us', 'estatein' ),
			'url'   => '',
		),
		array(
			'label'  => __( 'Properties', 'estatein' ),
			'url'    => estatein_properties_url(),
			'active' => is_post_type_archive( 'property' ) || is_singular( 'property' ),
		),
		array(
			'label' => __( 'Services', 'estatein' ),
			'url'   => '',
		),
	);

	echo '<ul class="menu">';
	foreach ( $items as $item ) {
		$class = ! empty( $item['active'] ) ? ' estatein-header__nav-link--active' : '';

		if ( '' !== $item['url'] ) {
			printf(
				'<li><a class="estatein-header__nav-link%s" href="%s">%s</a></li>',
				esc_attr( $class ),
				esc_url( $item['url'] ),
				esc_html( $item['label'] )
			);
		} else {
			printf(
				'<li><span class="estatein-header__nav-link estatein-header__nav-link--placeholder">%s</span></li>',
				esc_html( $item['label'] )
			);
		}
	}
	echo '</ul>';
}
