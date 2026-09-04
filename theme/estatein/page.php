<?php
/**
 * Default page template.
 *
 * @package Estatein
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="estatein-section estatein-container">
		<h1 class="estatein-h1"><?php the_title(); ?></h1>
		<div class="estatein-text-body" style="margin-top:24px">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
