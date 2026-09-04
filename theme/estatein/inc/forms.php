<?php
/**
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Form success/error banner.
 */
function estatein_form_notice() {
	$status  = isset( $_GET['form_status'] ) ? sanitize_key( wp_unslash( $_GET['form_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$message = isset( $_GET['form_message'] ) ? sanitize_text_field( wp_unslash( urldecode( (string) $_GET['form_message'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( ! $status ) {
		return;
	}

	$class = 'success' === $status ? 'estatein-notice--success' : 'estatein-notice--error';
	$text  = $message ? $message : ( 'success' === $status ? __( 'Thank you! Your message has been sent.', 'estatein' ) : __( 'Something went wrong. Please try again.', 'estatein' ) );

	printf(
		'<div class="estatein-notice %1$s" role="alert"><p>%2$s</p></div>',
		esc_attr( $class ),
		esc_html( $text )
	);
}
/**
 * Contact form markup.
 */
function estatein_form() {
	$uid = 'contact';
	?>
	<form class="estatein-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-estatein-form>
		<input type="hidden" name="action" value="estatein_form">
		<?php wp_nonce_field( 'estatein_form', 'estatein_nonce' ); ?>
		<input type="hidden" name="form_type" value="contact">
		<div class="estatein-honeypot" aria-hidden="true">
			<label for="website-<?php echo esc_attr( $uid ); ?>">Website</label>
			<input type="text" name="website" id="website-<?php echo esc_attr( $uid ); ?>" tabindex="-1" autocomplete="off">
		</div>

		<div class="estatein-form__card">
			<div class="estatein-form__grid estatein-form__grid--3">
				<div class="estatein-form__field">
					<label class="estatein-form__label" for="first_name-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'First Name', 'estatein' ); ?></label>
					<input class="estatein-form__input" type="text" id="first_name-<?php echo esc_attr( $uid ); ?>" name="first_name" placeholder="<?php esc_attr_e( 'Enter First Name', 'estatein' ); ?>" required>
				</div>
				<div class="estatein-form__field">
					<label class="estatein-form__label" for="last_name-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Last Name', 'estatein' ); ?></label>
					<input class="estatein-form__input" type="text" id="last_name-<?php echo esc_attr( $uid ); ?>" name="last_name" placeholder="<?php esc_attr_e( 'Enter Last Name', 'estatein' ); ?>" required>
				</div>
				<div class="estatein-form__field">
					<label class="estatein-form__label" for="email-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Email', 'estatein' ); ?></label>
					<input class="estatein-form__input" type="email" id="email-<?php echo esc_attr( $uid ); ?>" name="email" placeholder="<?php esc_attr_e( 'Enter your Email', 'estatein' ); ?>" required>
				</div>
			</div>
			<div class="estatein-form__grid estatein-form__grid--3" style="margin-top:20px">
				<div class="estatein-form__field">
					<label class="estatein-form__label" for="phone-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Phone', 'estatein' ); ?></label>
					<input class="estatein-form__input" type="tel" id="phone-<?php echo esc_attr( $uid ); ?>" name="phone" placeholder="<?php esc_attr_e( 'Enter Phone Number', 'estatein' ); ?>">
				</div>
				<div class="estatein-form__field">
					<label class="estatein-form__label" for="inquiry_type-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Inquiry Type', 'estatein' ); ?></label>
					<select class="estatein-form__select" id="inquiry_type-<?php echo esc_attr( $uid ); ?>" name="inquiry_type">
						<option value=""><?php esc_html_e( 'Select Inquiry Type', 'estatein' ); ?></option>
						<option value="buying"><?php esc_html_e( 'Buying', 'estatein' ); ?></option>
						<option value="selling"><?php esc_html_e( 'Selling', 'estatein' ); ?></option>
						<option value="investment"><?php esc_html_e( 'Investment', 'estatein' ); ?></option>
					</select>
				</div>
				<div class="estatein-form__field">
					<label class="estatein-form__label" for="hear_about-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'How Did You Hear About Us?', 'estatein' ); ?></label>
					<select class="estatein-form__select" id="hear_about-<?php echo esc_attr( $uid ); ?>" name="hear_about">
						<option value=""><?php esc_html_e( 'Select', 'estatein' ); ?></option>
						<option value="social"><?php esc_html_e( 'Social Media', 'estatein' ); ?></option>
						<option value="referral"><?php esc_html_e( 'Referral', 'estatein' ); ?></option>
						<option value="search"><?php esc_html_e( 'Search Engine', 'estatein' ); ?></option>
					</select>
				</div>
			</div>
			<div class="estatein-form__field" style="margin-top:20px">
				<label class="estatein-form__label" for="message-<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Message', 'estatein' ); ?></label>
				<textarea class="estatein-form__textarea" id="message-<?php echo esc_attr( $uid ); ?>" name="message" placeholder="<?php esc_attr_e( 'Enter your Message here...', 'estatein' ); ?>" required></textarea>
			</div>
		</div>

		<div class="estatein-form__footer">
			<label class="estatein-form__checkbox">
				<input type="checkbox" name="terms" required>
				<span><?php esc_html_e( 'I agree with Terms of Use and Privacy Policy.', 'estatein' ); ?></span>
			</label>
			<button type="submit" class="estatein-btn estatein-btn--primary"><?php esc_html_e( 'Send Your Message', 'estatein' ); ?></button>
		</div>
	</form>
	<?php
}

/**
 * Email recipient for form notifications.
 *
 * @return string
 */
function estatein_form_recipient() {
	$email = estatein_option( 'contact_email' );
	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

/**
 * Collect sanitized form field values.
 *
 * @param array $fields Field names.
 * @return array<string, string>
 */
function estatein_form_collect_data( $fields ) {
	$data = array();

	foreach ( $fields as $field ) {
		if ( ! isset( $_POST[ $field ] ) || '' === wp_unslash( $_POST[ $field ] ) ) {
			continue;
		}

		$value = wp_unslash( $_POST[ $field ] );
		if ( 'message' === $field ) {
			$data[ $field ] = sanitize_textarea_field( $value );
		} elseif ( 'email' === $field ) {
			$data[ $field ] = sanitize_email( $value );
		} else {
			$data[ $field ] = sanitize_text_field( $value );
		}
	}

	return $data;
}

/**
 * Persist a form submission for admin review.
 *
 * @param string               $form_type Form type.
 * @param array<string, string> $data      Field values.
 * @return int|false
 */
function estatein_save_form_submission( $form_type, $data ) {
	$name  = trim( ( $data['first_name'] ?? '' ) . ' ' . ( $data['last_name'] ?? '' ) );
	$title = 'newsletter' === $form_type
		? sprintf( 'Newsletter — %s', $data['email'] ?? '' )
		: sprintf( '%s — %s', ucfirst( str_replace( '_', ' ', $form_type ) ), $name ?: ( $data['email'] ?? __( 'Submission', 'estatein' ) ) );

	$GLOBALS['estatein_saving_inquiry'] = true;
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'estatein_inquiry',
			'post_status' => 'private',
			'post_title'  => $title,
		),
		true
	);
	$GLOBALS['estatein_saving_inquiry'] = false;

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	update_post_meta( $post_id, '_form_type', $form_type );
	update_post_meta( $post_id, '_form_data', $data );

	return (int) $post_id;
}

/**
 * Validate contact form fields.
 *
 * @param array<string, string> $data Sanitized data.
 * @return string Error message or empty string.
 */
function estatein_validate_contact_form( $data ) {
	if ( empty( $data['first_name'] ) || empty( $data['last_name'] ) ) {
		return __( 'Please enter your first and last name.', 'estatein' );
	}

	if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
		return __( 'Please enter a valid email address.', 'estatein' );
	}

	if ( empty( $data['message'] ) ) {
		return __( 'Please enter a message.', 'estatein' );
	}

	if ( empty( $_POST['terms'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in handler.
		return __( 'Please agree to the Terms of Use and Privacy Policy.', 'estatein' );
	}

	return '';
}

/**
 * Handle form posts.
 */
function estatein_handle_form() {
	if ( ! isset( $_POST['estatein_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['estatein_nonce'] ) ), 'estatein_form' ) ) {
		estatein_form_redirect( 'error', __( 'Security check failed.', 'estatein' ) );
	}

	if ( ! empty( $_POST['website'] ) ) {
		estatein_form_redirect( 'error' );
	}

	$form_type = isset( $_POST['form_type'] ) ? sanitize_key( wp_unslash( $_POST['form_type'] ) ) : 'contact';
	$allowed   = array(
		'first_name',
		'last_name',
		'email',
		'phone',
		'message',
		'inquiry_type',
		'hear_about',
	);
	$data      = estatein_form_collect_data( $allowed );
	$error     = '';

	if ( 'newsletter' === $form_type ) {
		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			estatein_form_redirect( 'error', __( 'Please enter a valid email address.', 'estatein' ) );
		}
	} else {
		$error = estatein_validate_contact_form( $data );
	}

	if ( $error ) {
		estatein_form_redirect( 'error', $error );
	}

	$lines   = array( 'Form Type: ' . $form_type, '' );
	foreach ( $data as $field => $value ) {
		$lines[] = ucwords( str_replace( '_', ' ', $field ) ) . ': ' . $value;
	}

	$subject = sprintf( '[Estatein] New %s form submission', ucfirst( str_replace( '_', ' ', $form_type ) ) );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
		$headers[] = 'Reply-To: ' . $data['email'];
	}

	$saved = estatein_save_form_submission( $form_type, $data );
	$sent  = wp_mail( estatein_form_recipient(), $subject, implode( "\n", $lines ), $headers );

	if ( ! $saved && ! $sent ) {
		estatein_form_redirect( 'error', __( 'Unable to send message. Please try again.', 'estatein' ) );
	}

	$success_message = 'newsletter' === $form_type
		? __( 'Thank you for subscribing!', 'estatein' )
		: __( 'Thank you! Your message has been sent.', 'estatein' );

	estatein_form_redirect( 'success', $success_message );
}
add_action( 'admin_post_estatein_form', 'estatein_handle_form' );
add_action( 'admin_post_nopriv_estatein_form', 'estatein_handle_form' );

/**
 * Redirect after form submit.
 *
 * @param string $status  success|error.
 * @param string $message Optional message.
 */
function estatein_form_redirect( $status, $message = '' ) {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$redirect = remove_query_arg( array( 'form_status', 'form_message' ), $redirect );

	$path = wp_parse_url( $redirect, PHP_URL_PATH );
	if ( $path && false !== strpos( $path, '/contact' ) ) {
		$redirect = strtok( $redirect, '#' ) . '#contact-form';
	}

	$args = array(
		'form_status' => $status,
	);
	if ( $message ) {
		$args['form_message'] = rawurlencode( $message );
	}

	wp_safe_redirect( add_query_arg( $args, $redirect ) );
	exit;
}
