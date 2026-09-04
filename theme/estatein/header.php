<?php
/**
 * Header.
 *
 * @package Estatein
 */

$contact_url     = estatein_page_url( 'contact' );
$is_contact_page = is_page( 'contact' );
$announce_text   = estatein_option( 'announcement_text' );
$announce_link   = estatein_option( 'announcement_link' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="estatein-skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'estatein' ); ?></a>

<?php if ( $announce_text ) : ?>
	<div class="estatein-announcement" role="region" aria-label="<?php esc_attr_e( 'Announcement', 'estatein' ); ?>">
		<div class="estatein-announcement__inner">
			<span>✨ <?php echo esc_html( $announce_text ); ?></span>
			<?php if ( $announce_link ) : ?>
				<a href="<?php echo esc_url( $announce_link ); ?>" class="estatein-announcement__link"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
			<?php endif; ?>
		</div>
		<button class="estatein-announcement__close" aria-label="<?php esc_attr_e( 'Dismiss announcement', 'estatein' ); ?>">
			<?php estatein_icon( 'close' ); ?>
		</button>
	</div>
<?php endif; ?>

<header class="estatein-header" role="banner">
	<div class="estatein-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="estatein-header__logo" aria-label="<?php esc_attr_e( 'Estatein Home', 'estatein' ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php esc_attr_e( 'Estatein Logo', 'estatein' ); ?>" width="100" height="100">
		</a>

		<nav class="estatein-header__nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'estatein' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'estatein_fallback_menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<div class="estatein-header__actions">
			<a href="<?php echo esc_url( $contact_url ); ?>" class="estatein-btn estatein-btn--<?php echo $is_contact_page ? 'primary estatein-header__contact-btn--filled' : 'ghost'; ?> estatein-header__contact-btn">
				<?php esc_html_e( 'Contact Us', 'estatein' ); ?>
			</a>
			<button class="estatein-header__toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'estatein' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<nav class="estatein-header__mobile-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'estatein' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'fallback_cb'    => 'estatein_fallback_menu',
			'depth'          => 1,
		)
	);
	?>
	<a href="<?php echo esc_url( $contact_url ); ?>" class="estatein-btn estatein-btn--<?php echo $is_contact_page ? 'primary estatein-header__contact-btn--filled' : 'primary'; ?> estatein-header__mobile-contact">
		<?php esc_html_e( 'Contact Us', 'estatein' ); ?>
	</a>
</nav>

<main id="main-content">
<?php if ( ! is_page( 'contact' ) ) : ?>
	<?php estatein_form_notice(); ?>
<?php endif; ?>
