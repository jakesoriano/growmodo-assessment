<?php
/**
 * Plugin Name: Estatein Settings
 * Description: Site settings for Estatein — announcement, contact info, socials, and CTA banner. Works with ACF Free.
 * Version: 1.0.0
 * Author: Growmodo Assessment
 * Text Domain: estatein-settings
 * Requires at least: 6.4
 * Requires PHP: 8.0
 *
 * @package EstateinSettings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default values (same keys the theme expects).
 *
 * @return array<string, string>
 */
function estatein_settings_defaults() {
	return array(
		'announcement_text' => 'Discover Your Dream Property with Estatein',
		'announcement_link' => '',
		'contact_email'     => 'info@estatein.com',
		'contact_phone'     => '+1 (123) 456-7890',
		'social_facebook'   => '#',
		'social_linkedin'   => '#',
		'social_twitter'    => '#',
		'social_youtube'    => '#',
		'cta_title'         => 'Start Your Real Estate Journey Today',
		'cta_description'   => 'Your dream property is just a click away. Whether you\'re looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way.',
		'cta_button_text'   => 'Explore Properties',
		'cta_button_url'    => '',
		'footer_copyright'  => 'All Rights Reserved.',
	);
}

/**
 * Get one setting (used by the theme via estatein_option()).
 *
 * @param string $key Setting key.
 * @return string
 */
function estatein_get_setting( $key ) {
	$settings = get_option( 'estatein_settings', array() );
	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	if ( isset( $settings[ $key ] ) && '' !== $settings[ $key ] ) {
		return $settings[ $key ];
	}

	$defaults = estatein_settings_defaults();
	return $defaults[ $key ] ?? '';
}

/**
 * Register settings page.
 */
function estatein_settings_menu() {
	add_menu_page(
		__( 'Estatein Settings', 'estatein-settings' ),
		__( 'Estatein', 'estatein-settings' ),
		'manage_options',
		'estatein-settings',
		'estatein_settings_render_page',
		'dashicons-admin-home',
		58
	);
}
add_action( 'admin_menu', 'estatein_settings_menu' );

/**
 * Register option + fields.
 */
function estatein_settings_register() {
	register_setting(
		'estatein_settings_group',
		'estatein_settings',
		array(
			'type'    => 'array',
			'default' => estatein_settings_defaults(),
		)
	);

	add_settings_section(
		'estatein_section_announcement',
		__( 'Announcement bar', 'estatein-settings' ),
		'__return_false',
		'estatein-settings'
	);

	add_settings_section(
		'estatein_section_contact',
		__( 'Contact', 'estatein-settings' ),
		'__return_false',
		'estatein-settings'
	);

	add_settings_section(
		'estatein_section_social',
		__( 'Social links', 'estatein-settings' ),
		'__return_false',
		'estatein-settings'
	);

	add_settings_section(
		'estatein_section_cta',
		__( 'CTA banner', 'estatein-settings' ),
		'__return_false',
		'estatein-settings'
	);

	add_settings_section(
		'estatein_section_footer',
		__( 'Footer', 'estatein-settings' ),
		'__return_false',
		'estatein-settings'
	);

	$fields = array(
		array( 'announcement_text', __( 'Announcement text', 'estatein-settings' ), 'text', 'estatein_section_announcement' ),
		array( 'announcement_link', __( 'Announcement link', 'estatein-settings' ), 'url', 'estatein_section_announcement' ),
		array( 'contact_email', __( 'Contact email', 'estatein-settings' ), 'email', 'estatein_section_contact' ),
		array( 'contact_phone', __( 'Contact phone', 'estatein-settings' ), 'text', 'estatein_section_contact' ),
		array( 'social_facebook', __( 'Facebook URL', 'estatein-settings' ), 'url', 'estatein_section_social' ),
		array( 'social_linkedin', __( 'LinkedIn URL', 'estatein-settings' ), 'url', 'estatein_section_social' ),
		array( 'social_twitter', __( 'Twitter / X URL', 'estatein-settings' ), 'url', 'estatein_section_social' ),
		array( 'social_youtube', __( 'YouTube URL', 'estatein-settings' ), 'url', 'estatein_section_social' ),
		array( 'cta_title', __( 'CTA title', 'estatein-settings' ), 'text', 'estatein_section_cta' ),
		array( 'cta_description', __( 'CTA description', 'estatein-settings' ), 'textarea', 'estatein_section_cta' ),
		array( 'cta_button_text', __( 'CTA button text', 'estatein-settings' ), 'text', 'estatein_section_cta' ),
		array( 'cta_button_url', __( 'CTA button URL', 'estatein-settings' ), 'url', 'estatein_section_cta' ),
		array( 'footer_copyright', __( 'Footer copyright text', 'estatein-settings' ), 'text', 'estatein_section_footer' ),
	);

	foreach ( $fields as $field ) {
		add_settings_field(
			$field[0],
			$field[1],
			'estatein_settings_field_cb',
			'estatein-settings',
			$field[3],
			array(
				'key'  => $field[0],
				'type' => $field[2],
			)
		);
	}
}
add_action( 'admin_init', 'estatein_settings_register' );

/**
 * Render one field.
 *
 * @param array $args Field args.
 */
function estatein_settings_field_cb( $args ) {
	$key      = $args['key'];
	$type     = $args['type'];
	$settings = get_option( 'estatein_settings', estatein_settings_defaults() );
	if ( ! is_array( $settings ) ) {
		$settings = estatein_settings_defaults();
	}
	$value = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
	$name  = 'estatein_settings[' . esc_attr( $key ) . ']';
	$id    = 'estatein_setting_' . esc_attr( $key );

	if ( 'textarea' === $type ) {
		printf(
			'<textarea class="large-text" rows="4" id="%1$s" name="%2$s">%3$s</textarea>',
			esc_attr( $id ),
			esc_attr( $name ),
			esc_textarea( $value )
		);
		return;
	}

	printf(
		'<input class="regular-text" type="%1$s" id="%2$s" name="%3$s" value="%4$s">',
		esc_attr( $type ),
		esc_attr( $id ),
		esc_attr( $name ),
		esc_attr( $value )
	);
}

/**
 * Settings page markup.
 */
function estatein_settings_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Estatein Settings', 'estatein-settings' ); ?></h1>
		<p><?php esc_html_e( 'These values power the announcement bar, contact tiles, social links, CTA banner, and footer.', 'estatein-settings' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'estatein_settings_group' );
			do_settings_sections( 'estatein-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Seed defaults once on activation.
 */
function estatein_settings_activate() {
	if ( false === get_option( 'estatein_settings' ) ) {
		update_option( 'estatein_settings', estatein_settings_defaults() );
	}
}
register_activation_hook( __FILE__, 'estatein_settings_activate' );
