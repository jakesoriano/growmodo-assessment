<?php
/**
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta description + Open Graph.
 */
function estatein_meta_tags() {
	if ( is_singular() ) {
		$desc = get_the_excerpt();
		if ( ! $desc ) {
			$desc = wp_trim_words( get_the_content(), 30 );
		}
	} elseif ( is_post_type_archive( 'property' ) ) {
		$desc = __( 'Browse premium properties with Estatein. Find your dream home with our curated real estate listings.', 'estatein' );
	} else {
		$desc = get_bloginfo( 'description' );
	}

	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
	}

	if ( is_singular() ) {
		echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
		echo '<meta property="og:type" content="website">' . "\n";
		echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
		if ( has_post_thumbnail() ) {
			echo '<meta property="og:image" content="' . esc_url( get_the_post_thumbnail_url( null, 'large' ) ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'estatein_meta_tags', 2 );
