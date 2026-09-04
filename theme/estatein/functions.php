<?php
/**
 * Estatein theme functions.
 *
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ESTATEIN_VERSION', '1.0.0' );

$estatein_includes = array(
	'setup',
	'post-types',
	'inquiry-admin',
	'helpers',
	'icons',
	'navigation',
	'template-tags',
	'forms',
	'seo',
	'seed',
);

foreach ( $estatein_includes as $file ) {
	require get_template_directory() . '/inc/' . $file . '.php';
}
