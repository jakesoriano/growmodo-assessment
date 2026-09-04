<?php
/**
 * Single property — /properties/name/ (rewrite slug) or /property/name/.
 *
 * @package Estatein
 */

get_header();

while ( have_posts() ) :
	the_post();
	$price     = estatein_field( 'price', get_the_ID() );
	$location  = estatein_field( 'location', get_the_ID() );
	$beds      = estatein_field( 'bedrooms', get_the_ID() );
	$baths     = estatein_field( 'bathrooms', get_the_ID() );
	$area      = estatein_field( 'area', get_the_ID() );
	$amenities = estatein_lines( estatein_field( 'amenities', get_the_ID() ) );
	$gallery   = estatein_property_gallery_images( get_the_ID() );
	?>

	<div class="estatein-property-header">
		<div>
			<div class="estatein-property-header__title-row">
				<h1 class="estatein-h2"><?php the_title(); ?></h1>
				<?php if ( $location ) : ?>
					<span class="estatein-pill"><?php estatein_icon( 'pin' ); ?> <?php echo esc_html( $location ); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="estatein-property-header__price">
			<label><?php esc_html_e( 'Price', 'estatein' ); ?></label>
			<strong><?php echo esc_html( estatein_format_price( $price ) ); ?></strong>
		</div>
	</div>

	<section class="estatein-container" style="padding-bottom:40px">
		<div class="estatein-gallery" data-property-gallery>
			<?php if ( $gallery ) : ?>
				<?php if ( count( $gallery ) > 1 ) : ?>
					<div class="estatein-gallery__thumbs">
						<?php foreach ( $gallery as $index => $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url ); ?>"
								data-gallery-thumb
								data-full="<?php echo esc_url( $image_url ); ?>"
								alt="<?php echo esc_attr( get_the_title() ); ?>"
								class="<?php echo 0 === $index ? 'is-active' : ''; ?>"
								loading="lazy">
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<div class="estatein-gallery__main">
					<img data-gallery-main src="<?php echo esc_url( $gallery[0] ); ?>" alt="<?php the_title_attribute(); ?>">
					<?php if ( isset( $gallery[1] ) ) : ?>
						<img data-gallery-main src="<?php echo esc_url( $gallery[1] ); ?>" alt="<?php the_title_attribute(); ?>">
					<?php endif; ?>
				</div>
				<?php if ( count( $gallery ) > 2 ) : ?>
					<?php $slide_count = count( $gallery ) - 1; ?>
					<div class="estatein-gallery-nav">
						<button type="button" class="estatein-gallery-nav__btn" data-gallery-prev aria-label="<?php esc_attr_e( 'Previous images', 'estatein' ); ?>" disabled>
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 3L5 8L10 13" stroke="currentColor" stroke-width="1.5"/></svg>
						</button>
						<div class="estatein-gallery-nav__dots" data-gallery-dots>
							<?php for ( $i = 0; $i < $slide_count; $i++ ) : ?>
								<button type="button"
									class="estatein-gallery-nav__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
									data-gallery-dot="<?php echo esc_attr( (string) $i ); ?>"
									aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number. */ __( 'Go to slide %d', 'estatein' ), $i + 1 ) ); ?>"></button>
							<?php endfor; ?>
						</div>
						<button type="button" class="estatein-gallery-nav__btn" data-gallery-next aria-label="<?php esc_attr_e( 'Next images', 'estatein' ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="1.5"/></svg>
						</button>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="estatein-gallery__main">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/placeholder-property.svg' ); ?>" alt="<?php the_title_attribute(); ?>">
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="estatein-two-col estatein-section">
		<div class="estatein-two-col__card">
			<h2 class="estatein-h4"><?php esc_html_e( 'Description', 'estatein' ); ?></h2>
			<div class="estatein-text-body" style="margin-top:16px"><?php the_content(); ?></div>
			<div class="estatein-two-col__stats">
				<?php if ( $beds ) : ?>
					<div class="estatein-two-col__stat">
						<div class="estatein-two-col__stat-label">
							<span class="estatein-two-col__stat-icon"><?php estatein_icon( 'bed-gray' ); ?></span>
							<span><?php esc_html_e( 'Bedrooms', 'estatein' ); ?></span>
						</div>
						<strong><?php echo esc_html( str_pad( (string) intval( $beds ), 2, '0', STR_PAD_LEFT ) ); ?></strong>
					</div>
				<?php endif; ?>
				<?php if ( $baths ) : ?>
					<div class="estatein-two-col__stat">
						<div class="estatein-two-col__stat-label">
							<span class="estatein-two-col__stat-icon"><?php estatein_icon( 'bath-gray' ); ?></span>
							<span><?php esc_html_e( 'Bathrooms', 'estatein' ); ?></span>
						</div>
						<strong><?php echo esc_html( str_pad( (string) intval( $baths ), 2, '0', STR_PAD_LEFT ) ); ?></strong>
					</div>
				<?php endif; ?>
				<?php if ( $area ) : ?>
					<div class="estatein-two-col__stat">
						<div class="estatein-two-col__stat-label">
							<span class="estatein-two-col__stat-icon"><?php estatein_icon( 'area-gray' ); ?></span>
							<span><?php esc_html_e( 'Area', 'estatein' ); ?></span>
						</div>
						<strong><?php echo esc_html( $area ); ?></strong>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="estatein-two-col__card">
			<h2 class="estatein-h4"><?php esc_html_e( 'Key Features and Amenities', 'estatein' ); ?></h2>
			<?php if ( $amenities ) : ?>
				<ul class="estatein-two-col__amenities">
					<?php foreach ( $amenities as $text ) : ?>
						<li class="estatein-two-col__amenity">
							<span class="estatein-two-col__amenity-icon" aria-hidden="true"><?php estatein_icon( 'lightning' ); ?></span>
							<span class="estatein-two-col__amenity-text"><?php echo esc_html( $text ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>

	<section id="property-inquiry-form" class="estatein-split-form estatein-section">
		<div>
			<?php
			estatein_section_header(
				/* translators: %s: property title. */
				sprintf( __( 'Inquire About %s', 'estatein' ), get_the_title() ),
				__( 'Interested in this property? Fill out the form below, and our real estate experts will get back to you with more details.', 'estatein' )
			);
			?>
		</div>
		<div>
			<?php estatein_form_notice(); ?>
			<?php estatein_property_inquiry_form( get_the_ID() ); ?>
		</div>
	</section>

	<section class="estatein-section estatein-container">
		<?php
		estatein_section_header(
			__( 'Comprehensive Pricing Details', 'estatein' ),
			__( 'At Estatein, transparency is key. We want you to have a clear understanding of the costs associated with your property investment.', 'estatein' )
		);
		?>
		<div class="estatein-pricing">
			<div class="estatein-pricing__note">
				<strong class="estatein-pricing__note-label"><?php esc_html_e( 'Note', 'estatein' ); ?></strong>
				<span class="estatein-pricing__note-divider" aria-hidden="true">|</span>
				<p class="estatein-pricing__note-text"><?php esc_html_e( 'The figures provided above are estimates and may vary depending on the property, location, and individual circumstances.', 'estatein' ); ?></p>
			</div>
			<div class="estatein-pricing__intro">
				<div class="estatein-pricing__listing">
					<span class="estatein-pricing__listing-label"><?php esc_html_e( 'Listing Price', 'estatein' ); ?></span>
					<strong><?php echo esc_html( estatein_format_price( $price ) ); ?></strong>
				</div>
				<div class="estatein-pricing__intro-cards">
					<?php
					estatein_pricing_card(
						__( 'Additional Fees', 'estatein' ),
						array(
							array(
								'label' => __( 'Property Transfer Tax', 'estatein' ),
								'value' => '$25,000',
								'note'  => __( 'Based on the sale price and local regulations', 'estatein' ),
							),
							array(
								'label' => __( 'Legal Fees', 'estatein' ),
								'value' => '$3,000',
								'note'  => __( 'Approximate cost for legal services', 'estatein' ),
							),
							array(
								'label' => __( 'Home Inspection', 'estatein' ),
								'value' => '$500',
								'note'  => __( 'Cost of a professional property inspection', 'estatein' ),
							),
							array(
								'label' => __( 'Property Insurance', 'estatein' ),
								'value' => '$1,200',
								'note'  => __( 'Annual cost for comprehensive property insurance', 'estatein' ),
							),
						),
						'grid'
					);
					estatein_pricing_card(
						__( 'Monthly Costs', 'estatein' ),
						array(
							array(
								'label' => __( 'Property Taxes', 'estatein' ),
								'value' => '$1,250',
								'note'  => __( 'Approximate monthly property tax based on assessed value and local tax rates', 'estatein' ),
							),
							array(
								'label' => __( 'Homeowners\' Association Fee', 'estatein' ),
								'value' => '$300',
								'note'  => __( 'Monthly fee for common area maintenance and security', 'estatein' ),
							),
						)
					);
					estatein_pricing_card(
						__( 'Total Initial Costs', 'estatein' ),
						array(
							array(
								'label' => __( 'Listing Price', 'estatein' ),
								'value' => estatein_format_price( $price ),
							),
							array(
								'label' => __( 'Additional Fees', 'estatein' ),
								'value' => '$29,700',
								'note'  => __( 'Sum of one-time fees', 'estatein' ),
							),
							array(
								'label' => __( 'Down Payment', 'estatein' ),
								'value' => '$250,000',
								'note'  => '20%',
							),
							array(
								'label' => __( 'Mortgage Amount', 'estatein' ),
								'value' => '$1,000,000',
								'note'  => __( 'If applicable', 'estatein' ),
							),
						),
						'grid'
					);
					estatein_pricing_card(
						__( 'Monthly Expenses', 'estatein' ),
						array(
							array(
								'label' => __( 'Property Taxes', 'estatein' ),
								'value' => '$1,250',
							),
							array(
								'label' => __( 'Homeowners\' Association Fee', 'estatein' ),
								'value' => '$300',
							),
							array(
								'label' => __( 'Mortgage Payment', 'estatein' ),
								'value' => __( 'Varies based on loan terms and interest rates', 'estatein' ),
								'note'  => __( 'If applicable', 'estatein' ),
							),
							array(
								'label' => __( 'Property Insurance', 'estatein' ),
								'value' => '$100',
								'note'  => __( 'Approximate monthly cost for property insurance', 'estatein' ),
							),
						),
						'grid'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<?php estatein_faq_section(); ?>

<?php endwhile; ?>

<?php
get_footer();
