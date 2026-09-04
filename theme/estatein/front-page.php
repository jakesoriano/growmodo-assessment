<?php
/**
 * Front page (Settings → Reading → static homepage).
 *
 * @package Estatein
 */

get_header();

$properties_url = estatein_properties_url();
$hero_image     = get_template_directory_uri() . '/assets/images/hero-building.svg';
$images_uri     = get_template_directory_uri() . '/assets/images/';

$properties = get_posts(
	array(
		'post_type'      => 'property',
		'posts_per_page' => 12,
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- featured flag is a small set of properties.
		'meta_query'     => array(
			array(
				'key'   => 'is_featured',
				'value' => '1',
			),
		),
	)
);
if ( ! $properties ) {
	$properties = get_posts(
		array(
			'post_type'      => 'property',
			'posts_per_page' => 12,
		)
	);
}

$features = array(
	array(
		'title' => 'Find Your Dream Home',
		'url'   => $properties_url,
		'icon'  => 'shop',
	),
	array(
		'title' => 'Unlock Property Value',
		'url'   => $properties_url,
		'icon'  => 'building',
	),
	array(
		'title' => 'Effortless Property Management',
		'url'   => $properties_url,
		'icon'  => 'camera',
	),
	array(
		'title' => 'Smart Investments, Informed Decisions',
		'url'   => $properties_url,
		'icon'  => 'sun',
	),
);

$testimonials = array(
	array(
		'title'    => 'Exceptional Service!',
		'quote'    => 'Our experience with Estatein was outstanding. Their team\'s dedication and professionalism made finding our dream home a breeze. Highly recommended!',
		'author'   => 'Wade Warren',
		'location' => 'USA, California',
		'rating'   => 5,
		'avatar'   => $images_uri . 'testimonial-wade.png',
	),
	array(
		'title'    => 'Efficient and Reliable',
		'quote'    => 'Estatein provided us with top-notch service. They helped us sell our property quickly and at a great price.',
		'author'   => 'Emelie Thomson',
		'location' => 'USA, Florida',
		'rating'   => 4,
		'avatar'   => $images_uri . 'testimonial-emelie.png',
	),
	array(
		'title'    => 'Trusted Advisors',
		'quote'    => 'The Estatein team guided us through the entire buying process. Their knowledge and commitment to our needs were impressive.',
		'author'   => 'John Mans',
		'location' => 'USA, Nevada',
		'rating'   => 5,
		'avatar'   => $images_uri . 'testimonial-john.png',
	),
);
?>

<section class="estatein-hero">
	<div class="estatein-hero__inner">
		<div class="estatein-hero__content">
			<h1 class="estatein-hero__title estatein-h1"><?php esc_html_e( 'Discover Your Dream Property with Estatein', 'estatein' ); ?></h1>
			<p class="estatein-hero__desc"><?php esc_html_e( 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.', 'estatein' ); ?></p>
			<div class="estatein-hero__actions">
				<a href="<?php echo esc_url( $properties_url ); ?>" class="estatein-btn estatein-btn--ghost"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
				<a href="<?php echo esc_url( $properties_url ); ?>" class="estatein-btn estatein-btn--primary"><?php esc_html_e( 'Browse Properties', 'estatein' ); ?></a>
			</div>
			<div class="estatein-hero__stats">
				<div class="estatein-hero__stat"><strong>200+</strong><span><?php esc_html_e( 'Happy Customers', 'estatein' ); ?></span></div>
				<div class="estatein-hero__stat"><strong>10k+</strong><span><?php esc_html_e( 'Properties For Clients', 'estatein' ); ?></span></div>
				<div class="estatein-hero__stat"><strong>16+</strong><span><?php esc_html_e( 'Years of Experience', 'estatein' ); ?></span></div>
			</div>
		</div>
		<div class="estatein-hero__visual">
			<div class="estatein-hero__image-container">
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Hero Image', 'estatein' ); ?>">
			</div>
			<div class="estatein-hero__badge" aria-hidden="true">
				<svg class="estatein-hero__badge-ring" viewBox="0 0 140 140" width="140" height="140">
					<defs>
						<path id="estatein-badge-path" d="M 70,70 m -48,0 a 48,48 0 1,1 96,0 a 48,48 0 1,1 -96,0" fill="none"/>
					</defs>
					<text class="estatein-hero__badge-text">
						<textPath href="#estatein-badge-path" startOffset="0%">
							Discover Your Dream Property ✨
						</textPath>
					</text>
				</svg>
				<span class="estatein-hero__badge-arrow">↗</span>
			</div>
		</div>
	</div>
</section>

<?php estatein_features_grid( __( 'Features', 'estatein' ), $features ); ?>

<?php if ( $properties ) : ?>
<section class="estatein-section estatein-container">
	<?php
	estatein_section_header(
		__( 'Featured Properties', 'estatein' ),
		__( 'Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein.', 'estatein' ),
		__( 'View All Properties', 'estatein' ),
		$properties_url
	);
	?>
	<div class="estatein-carousel" data-carousel data-slides-per-view="3">
		<div class="estatein-carousel__track">
			<?php foreach ( $properties as $property ) : ?>
				<div class="estatein-carousel__slide">
					<?php estatein_property_card( $property, 'featured' ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php estatein_carousel_nav( count( $properties ) ); ?>
</section>
<?php endif; ?>

<section class="estatein-section estatein-container">
	<?php
	estatein_section_header(
		__( 'What Our Clients Say', 'estatein' ),
		__( 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.', 'estatein' )
	);
	?>
	<div class="estatein-carousel" data-carousel data-slides-per-view="3">
		<div class="estatein-carousel__track">
			<?php foreach ( $testimonials as $item ) : ?>
				<?php
				$rating = isset( $item['rating'] ) ? (int) $item['rating'] : 5;
				$rating = max( 1, min( 5, $rating ) );
				/* translators: %d: star rating from 1 to 5. */
				$stars_label = sprintf( __( '%1$d out of 5 stars', 'estatein' ), $rating );
				?>
				<div class="estatein-carousel__slide">
					<article class="estatein-testimonial-card">
						<div class="estatein-testimonial-card__content">
							<div class="estatein-testimonial-card__stars" aria-label="<?php echo esc_attr( $stars_label ); ?>">
								<?php
								for ( $i = 0; $i < $rating; $i++ ) {
									estatein_icon( 'star' );
								}
								?>
							</div>
							<h3 class="estatein-testimonial-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
							<p class="estatein-testimonial-card__quote"><?php echo esc_html( $item['quote'] ); ?></p>
						</div>
						<div class="estatein-testimonial-card__author">
							<img src="<?php echo esc_url( $item['avatar'] ); ?>" alt="<?php echo esc_attr( $item['author'] ); ?>" width="48" height="48" loading="lazy">
							<div>
								<strong><?php echo esc_html( $item['author'] ); ?></strong>
								<span><?php echo esc_html( $item['location'] ); ?></span>
							</div>
						</div>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php estatein_carousel_nav( count( $testimonials ) ); ?>
</section>

<?php
estatein_faq_section();
get_footer();
