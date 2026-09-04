<?php
/**
 * Footer.
 *
 * @package Estatein
 */

$cta_title  = estatein_option( 'cta_title' );
$cta_desc   = estatein_option( 'cta_description' );
$cta_btn    = estatein_option( 'cta_button_text' );
$cta_url    = estatein_option( 'cta_button_url' );
$copyright  = estatein_option( 'footer_copyright' );
$socials    = array(
	'facebook' => estatein_option( 'social_facebook' ),
	'linkedin' => estatein_option( 'social_linkedin' ),
	'twitter'  => estatein_option( 'social_twitter' ),
	'youtube'  => estatein_option( 'social_youtube' ),
);
$footer_nav = array(
	__( 'Home', 'estatein' )       => array( __( 'Hero Section', 'estatein' ), __( 'Features', 'estatein' ), __( 'Properties', 'estatein' ), __( 'Testimonials', 'estatein' ), __( "FAQ's", 'estatein' ) ),
	__( 'Properties', 'estatein' ) => array( __( 'Portfolio', 'estatein' ), __( 'Categories', 'estatein' ) ),
	__( 'Contact Us', 'estatein' ) => array( __( 'Contact Form', 'estatein' ), __( 'Our Offices', 'estatein' ) ),
);

?>
</main>

<?php if ( $cta_title || $cta_desc ) : ?>
<section class="estatein-cta-banner" aria-labelledby="cta-banner-title">
	<div class="estatein-container">
		<div class="estatein-cta-banner__inner">
			<div class="estatein-cta-banner__content">
				<?php if ( $cta_title ) : ?>
					<h2 id="cta-banner-title" class="estatein-section-header__title estatein-h3"><?php echo esc_html( $cta_title ); ?></h2>
				<?php endif; ?>
				<?php if ( $cta_desc ) : ?>
					<p class="estatein-section-header__desc"><?php echo esc_html( $cta_desc ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $cta_btn && $cta_url ) : ?>
				<a href="<?php echo esc_url( $cta_url ); ?>" class="estatein-btn estatein-btn--primary"><?php echo esc_html( $cta_btn ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<footer class="estatein-footer" role="contentinfo">
	<div class="estatein-footer__main">
		<div class="estatein-footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="estatein-footer__logo">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php esc_attr_e( 'Estatein Logo', 'estatein' ); ?>" loading="lazy" width="100" height="100">
			</a>
			<form class="estatein-footer__newsletter" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="estatein_form">
				<?php wp_nonce_field( 'estatein_form', 'estatein_nonce' ); ?>
				<input type="hidden" name="form_type" value="newsletter">
				<label class="sr-only" for="newsletter-email"><?php esc_html_e( 'Email', 'estatein' ); ?></label>
				<span class="estatein-footer__newsletter-icon" aria-hidden="true"><?php estatein_icon( 'email' ); ?></span>
				<input type="email" id="newsletter-email" name="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'estatein' ); ?>" required>
				<button type="submit" aria-label="<?php esc_attr_e( 'Subscribe', 'estatein' ); ?>">
					<?php estatein_icon( 'send' ); ?>
				</button>
			</form>
		</div>

		<div class="estatein-footer__nav">
			<?php foreach ( $footer_nav as $heading => $links ) : ?>
				<div class="estatein-footer__nav-col">
					<h4><?php echo esc_html( $heading ); ?></h4>
					<ul>
						<?php foreach ( $links as $nav_item ) : ?>
							<li><a href="#"><?php echo esc_html( $nav_item ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="estatein-footer__bottom">
		<p>&copy;<?php echo esc_html( gmdate( 'Y' ) ); ?> Estatein<?php echo $copyright ? '. ' . esc_html( $copyright ) : ''; ?> <a href="#"><?php esc_html_e( 'Terms & Conditions', 'estatein' ); ?></a></p>
		<div class="estatein-footer__social">
			<?php foreach ( $socials as $network => $url ) : ?>
				<?php
				if ( ! $url ) {
					continue; }
				?>
				<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>" target="_blank" rel="noopener noreferrer">
					<?php estatein_icon( $network ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
