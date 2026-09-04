<?php
/**
 * 404 template.
 *
 * @package Estatein
 */

get_header();
?>

<section class="estatein-section estatein-container estatein-empty">
	<h1 class="estatein-h1"><?php esc_html_e( 'Page not found', 'estatein' ); ?></h1>
	<p class="estatein-text-muted" style="margin-top:16px"><?php esc_html_e( 'The page you are looking for does not exist.', 'estatein' ); ?></p>
	<p style="margin-top:24px">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="estatein-btn estatein-btn--primary"><?php esc_html_e( 'Back to Home', 'estatein' ); ?></a>
	</p>
</section>

<?php
get_footer();
