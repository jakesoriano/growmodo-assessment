<?php
/**
 * Contact page (slug: contact).
 *
 * @package Estatein
 */

get_header();

$email       = estatein_option( 'contact_email' );
$phone       = estatein_option( 'contact_phone' );
$socials     = array(
	'Instagram' => estatein_option( 'social_instagram' ),
	'LinkedIn'  => estatein_option( 'social_linkedin' ),
	'Facebook'  => estatein_option( 'social_facebook' ),
);
$images_uri  = get_template_directory_uri() . '/assets/images/';

$offices = array(
	array(
		'label'       => 'Main Headquarters',
		'address'     => '123 Estatein Plaza, City Center, Metropolis',
		'description' => 'Welcome to Estatein\'s Main Headquarters!',
		'email'       => 'info@estatein.com',
		'phone'       => '+1 (123) 456-7890',
		'city'        => 'Metropolis',
		'type'        => 'headquarters',
	),
	array(
		'label'       => 'Regional Offices',
		'address'     => '456 Urban Avenue, Downtown District, Metropolis',
		'description' => 'Discover convenience and accessibility with our Regional Offices.',
		'email'       => 'info@restatein.com',
		'phone'       => '+1 (123) 628-7890',
		'city'        => 'Metropolis',
		'type'        => 'regional',
	),
	array(
		'label'       => 'International Office',
		'address'     => '789 Global Street, Kensington, London, United Kingdom',
		'description' => 'Connecting clients worldwide with premium real estate opportunities through our international hub.',
		'email'       => 'info@estatein.com',
		'phone'       => '+44 20 1234 5678',
		'city'        => 'London',
		'type'        => 'international',
	),
);
?>

<div class="estatein-section-stack estatein-section-stack--flat">
	<section class="estatein-section estatein-section--intro">
		<div class="estatein-container">
			<?php
			estatein_section_header(
				__( 'Get in Touch with Estatein', 'estatein' ),
				__( 'Welcome to Estatein\'s Contact Us page. We\'re here to assist you with any inquiries, requests, or feedback you may have.', 'estatein' ),
				'',
				'',
				array(
					'heading_tag' => 'h1',
					'sparkle'     => false,
				)
			);
			?>
		</div>
	</section>

	<?php
	estatein_features_grid(
		__( 'Contact Information', 'estatein' ),
		array(
			array(
				'icon'  => 'email-purple',
				'title' => $email,
				'url'   => 'mailto:' . $email,
			),
			array(
				'icon'  => 'phone-purple',
				'title' => $phone,
				'url'   => 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ),
			),
			array(
				'icon'  => 'pin-purple',
				'title' => __( 'Main Headquarters', 'estatein' ),
			),
			array(
				'icon'  => 'logo-purple',
				'links' => array_filter( $socials ),
			),
		)
	);
	?>
</div>

<section id="contact-form" class="estatein-section estatein-container">
	<?php estatein_form_notice(); ?>
	<?php
	estatein_section_header(
		__( 'Let\'s Connect', 'estatein' ),
		__( 'We\'re excited to connect with you and learn more about your real estate goals. Use the form below to get in touch with Estatein.', 'estatein' )
	);
	estatein_form();
	?>
</section>

<section class="estatein-section estatein-container">
	<?php
	estatein_section_header(
		__( 'Discover Our Office Locations', 'estatein' ),
		__( 'Estatein is here to serve you across various locations. Whether you\'re looking to meet our team, explore properties, or simply connect with us, our offices are open.', 'estatein' )
	);
	?>
	<div class="estatein-tabs" data-office-filter>
		<button type="button" class="estatein-tabs__btn estatein-tabs__btn--active" data-filter="all"><?php esc_html_e( 'All', 'estatein' ); ?></button>
		<button type="button" class="estatein-tabs__btn" data-filter="regional"><?php esc_html_e( 'Regional', 'estatein' ); ?></button>
		<button type="button" class="estatein-tabs__btn" data-filter="international"><?php esc_html_e( 'International', 'estatein' ); ?></button>
	</div>
	<div class="estatein-carousel__grid estatein-office-grid" data-office-grid>
		<?php foreach ( $offices as $office ) : ?>
			<article class="estatein-office-card" data-office-type="<?php echo esc_attr( $office['type'] ); ?>">
				<p class="estatein-office-card__label"><?php echo esc_html( $office['label'] ); ?></p>
				<h3 class="estatein-office-card__title"><?php echo esc_html( $office['address'] ); ?></h3>
				<p class="estatein-office-card__desc"><?php echo esc_html( $office['description'] ); ?></p>
				<div class="estatein-office-card__meta">
					<span class="estatein-contact-pill"><?php estatein_icon( 'email-white' ); ?> <?php echo esc_html( $office['email'] ); ?></span>
					<span class="estatein-contact-pill"><?php estatein_icon( 'phone-white' ); ?> <?php echo esc_html( $office['phone'] ); ?></span>
					<span class="estatein-contact-pill"><?php estatein_icon( 'pin-white' ); ?> <?php echo esc_html( $office['city'] ); ?></span>
				</div>
				<a
					href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $office['address'] ) ); ?>"
					class="estatein-btn estatein-btn--primary estatein-btn--full estatein-office-card__direction"
					target="_blank"
					rel="noopener noreferrer"
				>
					<?php esc_html_e( 'Get Direction', 'estatein' ); ?>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
</section>

<section class="estatein-section estatein-container">
	<div class="estatein-explore">
		<div class="estatein-explore__grid">
			<img
				class="estatein-explore__top"
				src="<?php echo esc_url( $images_uri . 'explore-img-top.png' ); ?>"
				alt="<?php esc_attr_e( 'Estatein team and workspace gallery', 'estatein' ); ?>"
				loading="lazy"
				width="1200"
				height="600"
			>
			<div class="estatein-explore__text">
				<div class="estatein-section-header__sparkle" aria-hidden="true"><?php estatein_icon( 'section' ); ?></div>
				<h2 class="estatein-h2"><?php esc_html_e( 'Explore Estatein\'s World', 'estatein' ); ?></h2>
				<p class="estatein-explore__desc"><?php esc_html_e( 'Step inside the world of Estatein, where professionalism meets warmth, and expertise meets passion. Our gallery offers a glimpse into our team and workspaces, inviting you to get to know us better.', 'estatein' ); ?></p>
			</div>
			<img
				class="estatein-explore__btm"
				src="<?php echo esc_url( $images_uri . 'explore-img-btm.png' ); ?>"
				alt="<?php esc_attr_e( 'Estatein professionals shaking hands', 'estatein' ); ?>"
				loading="lazy"
				width="800"
				height="400"
			>
		</div>
	</div>
</section>

<?php
get_footer();
