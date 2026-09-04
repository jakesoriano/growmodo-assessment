<?php
/**
 * Theme setup, assets, and supports.
 *
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme supports and menus.
 */
function estatein_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script' )
	);
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'estatein' ),
		)
	);
	add_image_size( 'estatein-card', 640, 480, true );
	add_image_size( 'estatein-gallery', 1200, 800, true );
}
add_action( 'after_setup_theme', 'estatein_setup' );

/**
 * Load CSS and JS the WordPress way.
 */
function estatein_assets() {
	wp_enqueue_style(
		'estatein-fonts',
		'https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap',
		array(),
		ESTATEIN_VERSION
	);
	wp_enqueue_style( 'estatein', get_stylesheet_uri(), array( 'estatein-fonts' ), ESTATEIN_VERSION );
	wp_enqueue_script(
		'estatein',
		get_template_directory_uri() . '/main.js',
		array(),
		ESTATEIN_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'estatein_assets' );
