<?php
/**
 * Template tags and reusable markup helpers.
 *
 * @package Estatein
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render one card in the features grid.
 *
 * @param array $card Card configuration.
 *     @type string $icon  Icon name for estatein_icon().
 *     @type string $title Optional card title.
 *     @type string $url   Optional link URL; omit for a static card.
 *     @type array  $links Optional map of label => URL for multi-link cards (e.g. social).
 */
function estatein_features_card( $card ) {
	$icon  = $card['icon'] ?? '';
	$title = $card['title'] ?? '';
	$url   = $card['url'] ?? '';
	$links = $card['links'] ?? array();

	if ( $url ) {
		printf(
			'<a class="estatein-features__card estatein-card" href="%s">',
			esc_url( $url )
		);
	} else {
		echo '<div class="estatein-features__card estatein-card">';
	}
	?>
	<span class="estatein-card__arrow"><?php estatein_icon( 'arrow-up' ); ?></span>
	<?php
	if ( $icon ) {
		estatein_icon( $icon );
	}

	if ( $links ) {
		echo '<div class="estatein-contact-tiles__social-links">';
		foreach ( $links as $label => $link_url ) {
			if ( ! $link_url ) {
				continue;
			}
			printf(
				'<h3><a href="%1$s">%2$s</a></h3>',
				esc_url( $link_url ),
				esc_html( $label )
			);
		}
		echo '</div>';
	} elseif ( $title ) {
		printf( '<h3>%s</h3>', esc_html( $title ) );
	}

	if ( $url ) {
		echo '</a>';
	} else {
		echo '</div>';
	}
}

/**
 * Features grid section (homepage services, contact tiles, etc.).
 *
 * @param string $aria_label Accessible section label.
 * @param array  $cards      Card definitions for estatein_features_card().
 */
function estatein_features_grid( $aria_label, $cards ) {
	if ( ! $cards ) {
		return;
	}
	?>
	<section class="estatein-features" aria-label="<?php echo esc_attr( $aria_label ); ?>">
		<div class="estatein-features__grid">
			<?php foreach ( $cards as $card ) : ?>
				<?php estatein_features_card( $card ); ?>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/**
 * Section heading used on every marketing block.
 *
 * @param string $title       Title.
 * @param string $description Description.
 * @param string $button_text Optional button.
 * @param string $button_url  Optional URL.
 * @param array  $args        Optional: heading_tag (h1|h2|h3), sparkle (bool).
 */
function estatein_section_header( $title, $description = '', $button_text = '', $button_url = '', $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'heading_tag' => 'h2',
			'sparkle'     => true,
		)
	);

	$heading_tag = in_array( $args['heading_tag'], array( 'h1', 'h2', 'h3' ), true ) ? $args['heading_tag'] : 'h2';
	$title_class = 'h3' === $heading_tag ? 'estatein-h3' : 'estatein-h2';
	?>
	<div class="estatein-section-header">
		<?php if ( $args['sparkle'] ) : ?>
			<div class="estatein-section-header__sparkle" aria-hidden="true">
				<?php estatein_icon( 'section' ); ?>
			</div>
		<?php endif; ?>
		<div class="estatein-section-header__row">
			<div class="estatein-section-header__content">
				<?php if ( $title ) : ?>
					<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $heading_tag is allowlisted above. ?>
					<<?php echo $heading_tag; ?> class="estatein-section-header__title <?php echo esc_attr( $title_class ); ?>"><?php echo esc_html( $title ); ?></<?php echo $heading_tag; ?>>
				<?php endif; ?>
				<?php if ( $description ) : ?>
					<p class="estatein-section-header__desc"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $button_text && $button_url ) : ?>
				<div class="estatein-section-header__action">
					<a href="<?php echo esc_url( $button_url ); ?>" class="estatein-btn estatein-btn--outline"><?php echo esc_html( $button_text ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Single pricing field (label, value, optional pill note).
 *
 * @param string $label Field label.
 * @param string $value Display value.
 * @param string $note  Optional pill text.
 */
function estatein_pricing_item( $label, $value, $note = '' ) {
	?>
	<div class="estatein-pricing__item">
		<span class="estatein-pricing__item-label"><?php echo esc_html( $label ); ?></span>
		<div class="estatein-pricing__item-value">
			<strong><?php echo esc_html( $value ); ?></strong>
			<?php if ( $note ) : ?>
				<span class="estatein-pricing__pill"><?php echo esc_html( $note ); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Pricing card with optional grid or list body layout.
 *
 * @param string $title  Card heading.
 * @param array  $rows   Rows with label, value, and optional note keys.
 * @param string $layout list|grid.
 */
function estatein_pricing_card( $title, $rows, $layout = 'list' ) {
	$layout_class = 'list' === $layout ? 'estatein-pricing__card-body--list' : 'estatein-pricing__card-body--grid';
	?>
	<div class="estatein-pricing__card">
		<div class="estatein-pricing__card-head">
			<h4 class="estatein-h4"><?php echo esc_html( $title ); ?></h4>
			<a href="#" class="estatein-btn estatein-btn--outline estatein-btn--sm"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
		</div>
		<div class="estatein-pricing__card-body <?php echo esc_attr( $layout_class ); ?>">
			<?php
			foreach ( $rows as $row ) {
				estatein_pricing_item( $row['label'], $row['value'], $row['note'] ?? '' );
			}
			?>
		</div>
	</div>
	<?php
}

/**
 * Carousel prev/next + count.
 *
 * @param int $total Slide count.
 */
function estatein_carousel_nav( $total ) {
	?>
	<div class="estatein-carousel-nav">
		<span class="estatein-carousel-nav__count" data-carousel-count>01 of <?php echo esc_html( str_pad( (string) $total, 2, '0', STR_PAD_LEFT ) ); ?></span>
		<div class="estatein-carousel-nav__buttons">
			<button type="button" class="estatein-carousel-nav__btn" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous slide', 'estatein' ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 3L5 8L10 13" stroke="currentColor" stroke-width="1.5"/></svg>
			</button>
			<button type="button" class="estatein-carousel-nav__btn estatein-carousel-nav__btn--next" data-carousel-next aria-label="<?php esc_attr_e( 'Next slide', 'estatein' ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="1.5"/></svg>
			</button>
		</div>
	</div>
	<?php
}

/**
 * Property card.
 *
 * @param WP_Post $property Property post.
 * @param string  $variant  featured|listing.
 */
function estatein_property_card( $property, $variant = 'featured' ) {
	$price    = estatein_field( 'price', $property->ID );
	$beds     = estatein_field( 'bedrooms', $property->ID );
	$baths    = estatein_field( 'bathrooms', $property->ID );
	$type     = estatein_field( 'property_type_label', $property->ID );
	$tag      = estatein_field( 'category_tag', $property->ID );
	$image    = get_the_post_thumbnail_url( $property, 'estatein-card' );
	$fallback = get_template_directory_uri() . '/assets/images/placeholder-property.svg';
	?>
	<article class="estatein-property-card">
		<a href="<?php echo esc_url( get_permalink( $property ) ); ?>" class="estatein-property-card__image">
			<img src="<?php echo esc_url( $image ? $image : $fallback ); ?>" alt="<?php echo esc_attr( get_the_title( $property ) ); ?>" loading="lazy" width="640" height="480">
		</a>
		<?php if ( 'listing' === $variant && $tag ) : ?>
			<span class="estatein-tag estatein-property-card__tag"><?php echo esc_html( $tag ); ?></span>
		<?php endif; ?>
		<h3 class="estatein-property-card__title">
			<a href="<?php echo esc_url( get_permalink( $property ) ); ?>"><?php echo esc_html( get_the_title( $property ) ); ?></a>
		</h3>
		<p class="estatein-property-card__desc">
			<?php
			$excerpt = get_the_excerpt( $property );
			if ( ! $excerpt ) {
				$excerpt = $property->post_content;
			}
			echo esc_html( estatein_excerpt( $excerpt, 65 ) );
			?>
			<a href="<?php echo esc_url( get_permalink( $property ) ); ?>" class="estatein-link"><?php esc_html_e( 'Read More', 'estatein' ); ?></a>
		</p>
		<?php if ( 'featured' === $variant ) : ?>
			<div class="estatein-property-card__meta">
				<?php
				if ( $beds ) :
					?>
					<span class="estatein-tag estatein-tag--icon"><?php estatein_icon( 'bed' ); ?><?php echo esc_html( $beds ); ?>-<?php esc_html_e( 'Bedroom', 'estatein' ); ?></span><?php endif; ?>
				<?php
				if ( $baths ) :
					?>
					<span class="estatein-tag estatein-tag--icon"><?php estatein_icon( 'bath' ); ?><?php echo esc_html( $baths ); ?>-<?php esc_html_e( 'Bathroom', 'estatein' ); ?></span><?php endif; ?>
				<?php
				if ( $type ) :
					?>
					<span class="estatein-tag estatein-tag--icon"><?php estatein_icon( 'villa' ); ?><?php echo esc_html( $type ); ?></span><?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="estatein-property-card__footer">
			<div class="estatein-property-card__price">
				<?php if ( $price ) : ?>
					<label><?php esc_html_e( 'Price', 'estatein' ); ?></label>
					<strong><?php echo esc_html( estatein_format_price( $price ) ); ?></strong>
				<?php endif; ?>
			</div>
			<a href="<?php echo esc_url( get_permalink( $property ) ); ?>" class="estatein-btn estatein-btn--primary estatein-btn--sm">
				<?php esc_html_e( 'View Property Details', 'estatein' ); ?>
			</a>
		</div>
	</article>
	<?php
}

/**
 * Shared FAQ block.
 */
function estatein_faq_section() {
	$faqs    = array(
		array(
			'question' => 'How do I search for properties on Estatein?',
			'answer'   => 'Learn how to use our user-friendly search tools to find properties that match your criteria.',
		),
		array(
			'question' => 'What documents do I need to sell my property through Estatein?',
			'answer'   => 'Find out about the necessary documentation for listing your property with us.',
		),
		array(
			'question' => 'How can I contact an Estatein agent?',
			'answer'   => 'Discover the different ways you can get in touch with our experienced agents.',
		),
	);
	$contact = estatein_page_url( 'contact' );
	?>
	<section class="estatein-section estatein-container" aria-labelledby="faq-section-title">
		<?php
		estatein_section_header(
			__( 'Frequently Asked Questions', 'estatein' ),
			__( 'Find answers to common questions about Estatein\'s services, property listings, and the real estate process. We\'re here to provide clarity and assist you every step of the way.', 'estatein' ),
			__( 'View All FAQ\'s', 'estatein' ),
			$contact
		);
		?>
		<div class="estatein-carousel" data-carousel data-slides-per-view="3">
			<div class="estatein-carousel__track">
				<?php foreach ( $faqs as $faq ) : ?>
					<div class="estatein-carousel__slide">
						<article class="estatein-faq-card">
							<h3 class="estatein-faq-card__question"><?php echo esc_html( $faq['question'] ); ?></h3>
							<p class="estatein-faq-card__answer"><?php echo esc_html( $faq['answer'] ); ?></p>
							<a href="<?php echo esc_url( $contact ); ?>" class="estatein-btn estatein-btn--outline estatein-btn--sm"><?php esc_html_e( 'Read More', 'estatein' ); ?></a>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php estatein_carousel_nav( count( $faqs ) ); ?>
	</section>
	<?php
}
