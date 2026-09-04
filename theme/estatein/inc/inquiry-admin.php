<?php
/**
 * Form submission admin UI and capability mapping.
 *
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allow programmatic inquiry saves during public form handling.
 *
 * @param array  $caps    Required caps.
 * @param string $cap     Capability.
 * @param int    $user_id User ID.
 * @param array  $args    Extra args.
 * @return array
 */
function estatein_inquiry_map_meta_cap( $caps, $cap, $user_id, $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	if ( empty( $GLOBALS['estatein_saving_inquiry'] ) ) {
		return $caps;
	}

	if ( in_array( $cap, array( 'edit_post', 'edit_posts', 'create_posts', 'publish_posts' ), true ) ) {
		return array( 'exist' );
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'estatein_inquiry_map_meta_cap', 10, 4 );

/**
 * Admin list columns for form submissions.
 *
 * @param array $columns Columns.
 * @return array
 */
function estatein_inquiry_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['inquiry_type']  = __( 'Form', 'estatein' );
			$new['inquiry_email'] = __( 'Email', 'estatein' );
		}
	}
	return $new;
}
add_filter( 'manage_estatein_inquiry_posts_columns', 'estatein_inquiry_columns' );

/**
 * Render custom admin columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function estatein_inquiry_column_content( $column, $post_id ) {
	if ( 'inquiry_type' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_form_type', true ) );
		return;
	}

	if ( 'inquiry_email' === $column ) {
		$data = get_post_meta( $post_id, '_form_data', true );
		echo esc_html( is_array( $data ) && ! empty( $data['email'] ) ? $data['email'] : '—' );
	}
}
add_action( 'manage_estatein_inquiry_posts_custom_column', 'estatein_inquiry_column_content', 10, 2 );

/**
 * Submission details meta box.
 */
function estatein_inquiry_meta_box() {
	add_meta_box(
		'estatein-inquiry-details',
		__( 'Submission Details', 'estatein' ),
		'estatein_inquiry_meta_box_render',
		'estatein_inquiry',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'estatein_inquiry_meta_box' );

/**
 * Render inquiry submission meta box.
 *
 * @param WP_Post $post Post.
 */
function estatein_inquiry_meta_box_render( $post ) {
	$data = get_post_meta( $post->ID, '_form_data', true );
	if ( ! is_array( $data ) || ! $data ) {
		echo '<p>' . esc_html__( 'No submission data stored.', 'estatein' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped"><tbody>';
	foreach ( $data as $key => $value ) {
		if ( '' === $value ) {
			continue;
		}
		printf(
			'<tr><th scope="row" style="width:180px">%1$s</th><td>%2$s</td></tr>',
			esc_html( ucwords( str_replace( '_', ' ', $key ) ) ),
			esc_html( $value )
		);
	}
	echo '</tbody></table>';
}
