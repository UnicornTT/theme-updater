<div class="section-resources__cell">
	<div class="resources-card " tabindex="0">
		<div class="resources-card__bg">
			<div class="background-img">
				<?= wp_get_attachment_image( the_post_thumbnail(), 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( get_post_thumbnail_id( get_the_ID() ) ) )] ); ?>
			</div>
		</div>

		<div class="resources-card__content">
			<div class="resources-card__body">
				<a href="<?= esc_url( get_permalink() ) ?>" class="resources-card__title section-title">
					<h3>
						<?= esc_html( the_title() ) ?>
					</h3>
				</a>

				<div class="resources-card__description text-content">
					<?= apply_filters( 'the_content', get_field( 'single_post_description', get_the_ID() ) ) ?>
				</div>

				<div class="resources-card__date">
					<span>
						<?= esc_html( get_the_date( 'M jS, Y' ) ); ?> |
					</span>

					<a class="resources-card__author" href="<?= esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
						<?= esc_html( __( 'by ', THEME_TEXTDOMAIN ) . get_the_author() ) ?>
					</a>
				</div>
			</div>

			<div class="resources-card__button-wrapper">
				<a href="<?= esc_url( get_permalink() ) ?>" class="button button-bordered button-bordered-white resources-card__button">
					<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
				</a>
			</div>
		</div>
	</div>
</div>