<?php
/**
 * Fallback template. WordPress uses this when no more specific file exists.
 *
 * @package Estatein
 */

get_header();
?>

<section class="estatein-section estatein-container">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article <?php post_class(); ?> style="margin-bottom:40px">
				<h2 class="estatein-h2">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<div class="estatein-text-body" style="margin-top:16px">
					<?php the_excerpt(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<h1 class="estatein-h1"><?php esc_html_e( 'Nothing found', 'estatein' ); ?></h1>
		<p class="estatein-text-muted" style="margin-top:16px"><?php esc_html_e( 'Try a different page from the menu.', 'estatein' ); ?></p>
	<?php endif; ?>
</section>

<?php
get_footer();
