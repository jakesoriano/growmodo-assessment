<?php
/**
 * Property archive — /properties/
 *
 * @package Estatein
 */

get_header();

$keyword  = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$location = isset( $_GET['location'] ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$type     = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$price    = isset( $_GET['price'] ) ? sanitize_key( wp_unslash( $_GET['price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$properties = get_posts(
	estatein_property_search_args(
		array(
			'q'        => $keyword,
			'location' => $location,
			'type'     => $type,
			'price'    => $price,
		)
	)
);

$location_options = estatein_property_filter_locations();
$type_options     = estatein_property_filter_types();
$price_options    = estatein_property_filter_price_ranges();
?>

<div class="estatein-section-stack">
	<section class="estatein-section estatein-section--intro">
		<div class="estatein-container">
			<?php
			estatein_section_header(
				__( 'Find Your Dream Property', 'estatein' ),
				__( 'Welcome to Estatein, where your dream property awaits in every corner of our beautiful world. Explore our curated selection of properties, each offering a unique story and a chance to redefine your life.', 'estatein' ),
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

	<section class="estatein-section estatein-section--search estatein-container">
		<form class="estatein-search" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>">
			<div class="estatein-search__row">
				<div class="estatein-search__input-wrap">
					<input class="estatein-search__input" type="search" name="q" value="<?php echo esc_attr( $keyword ); ?>" placeholder="<?php esc_attr_e( 'Search For A Property', 'estatein' ); ?>">
					<button type="submit" class="estatein-btn estatein-btn--primary">
						<?php estatein_icon( 'search' ); ?>
						<?php esc_html_e( 'Find Property', 'estatein' ); ?>
					</button>
				</div>
			</div>
			<div class="estatein-search__filters">
				<label class="estatein-search__filter">
					<?php estatein_icon( 'pin' ); ?>
					<span class="estatein-search__filter-divider" aria-hidden="true">|</span>
					<select class="estatein-search__filter-select" name="location" onchange="this.form.requestSubmit()">
						<option value=""><?php esc_html_e( 'Location', 'estatein' ); ?></option>
						<?php foreach ( $location_options as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <? selected( $location, $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php estatein_icon( 'chevron' ); ?>
				</label>
				<label class="estatein-search__filter">
					<?php estatein_icon( 'property' ); ?>
					<span class="estatein-search__filter-divider" aria-hidden="true">|</span>
					<select class="estatein-search__filter-select" name="type" onchange="this.form.requestSubmit()">
						<option value=""><?php esc_html_e( 'Property Type', 'estatein' ); ?></option>
						<?php foreach ( $type_options as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <? selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php estatein_icon( 'chevron' ); ?>
				</label>
				<label class="estatein-search__filter">
					<?php estatein_icon( 'price' ); ?>
					<span class="estatein-search__filter-divider" aria-hidden="true">|</span>
					<select class="estatein-search__filter-select" name="price" onchange="this.form.requestSubmit()">
						<option value=""><?php esc_html_e( 'Pricing Range', 'estatein' ); ?></option>
						<?php foreach ( $price_options as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <? selected( $price, $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php estatein_icon( 'chevron' ); ?>
				</label>
				<div class="estatein-search__filter estatein-search__filter--static">
					<?php estatein_icon( 'box' ); ?>
					<span class="estatein-search__filter-divider" aria-hidden="true">|</span>
					<span class="estatein-search__filter-label"><?php esc_html_e( 'Property Size', 'estatein' ); ?></span>
					<?php estatein_icon( 'chevron' ); ?>
				</div>
				<div class="estatein-search__filter estatein-search__filter--static">
					<?php estatein_icon( 'calendar' ); ?>
					<span class="estatein-search__filter-divider" aria-hidden="true">|</span>
					<span class="estatein-search__filter-label"><?php esc_html_e( 'Build Year', 'estatein' ); ?></span>
					<?php estatein_icon( 'chevron' ); ?>
				</div>
			</div>
		</form>
	</section>
</div>

<?php if ( $properties ) : ?>
<section class="estatein-section estatein-container">
	<?php
	estatein_section_header(
		__( 'Discover a World of Possibilities', 'estatein' ),
		__( 'Our portfolio of properties is as diverse as your dreams. Explore the following categories to find the perfect property that resonates with your vision of home.', 'estatein' )
	);
	?>
	<div class="estatein-carousel" data-carousel data-slides-per-view="3">
		<div class="estatein-carousel__track">
			<?php foreach ( $properties as $property ) : ?>
				<div class="estatein-carousel__slide">
					<?php estatein_property_card( $property, 'listing' ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php estatein_carousel_nav( count( $properties ) ); ?>
</section>
<?php else : ?>
<section class="estatein-section estatein-container">
	<p class="estatein-empty"><?php esc_html_e( 'No properties found.', 'estatein' ); ?></p>
</section>
<?php endif; ?>

<section class="estatein-section estatein-container">
	<?php
	estatein_section_header(
		__( 'Let\'s Make it Happen', 'estatein' ),
		__( 'Ready to take the first step toward your dream property? Get in touch and our real estate team will help you find your perfect match.', 'estatein' )
	);
	?>
	<a href="<?php echo esc_url( estatein_page_url( 'contact' ) . '#contact-form' ); ?>" class="estatein-btn estatein-btn--primary">
		<?php esc_html_e( 'Contact Us', 'estatein' ); ?>
	</a>
</section>

<?php
get_footer();
