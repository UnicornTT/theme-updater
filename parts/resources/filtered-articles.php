<?php

/**
 * @var $args
 */

$posts = $args['posts'] ?? [];

if ( $posts ):
	foreach ( $posts as $index => $article ) : ?>
		<?php
			$post_title = get_the_title( $article );
			$post_description = wp_trim_words( get_field( 'single_post_description', $article->ID ), 40 );
			$post_thumbnail_id = get_post_thumbnail_id( $article );
	
			$post_author_id = $article->post_author;
			$post_author_name = null;
			$post_author_page_link = null;
	
			$post_author_name = 'by ' . get_the_author_meta( 'display_name', $post_author_id );
			$post_author_page_link = get_author_posts_url( $post_author_id );
	
			$post_date = get_the_date( 'F jS, Y', $article );
			$post_link = get_the_permalink( $article );
		?>
		<div class="section-resources__cell">
			<div class="resources-card <?= esc_attr( $index == 0 ? 'resources-card--full' : '' ) ?>" tabindex="0">
				<div class="resources-card__bg">
					<div class="background-img">
						<?= wp_get_attachment_image( $post_thumbnail_id, 'thumbs-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $post_thumbnail_id ) )] ); ?>
					</div>
				</div>

				<div class="resources-card__content">
					<div class="resources-card__body">
						<?php if ( $post_title ) : ?>
							<a href="<?= esc_url( $post_link ); ?>" class="resources-card__title section-title">
								<h3>
									<?= esc_html( $post_title ); ?>
								</h3>
							</a>
						<?php endif; ?>

						<?php if ( $post_description ) : ?>
							<div class="resources-card__description text-content">
								<?= nl2br( $post_description ) ?>
							</div>
						<?php endif; ?>

						<div class="resources-card__date">
							<?php if ( $post_date ) : ?>
								<span>
                                    <?= esc_html( $post_date . '|' ); ?>
                                </span>
							<?php endif; ?>

							<?php if ( $post_author_name ) : ?>
								<a class="resources-card__author" href="<?= esc_url( $post_author_page_link ); ?>" aria-label="<?= esc_attr( __( 'View ' . $post_author_name . ' profile', THEME_TEXTDOMAIN ) ); ?>">
									<?= esc_html( $post_author_name ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<div class="resources-card__button-wrapper">
						<a href="<?= esc_url( $post_link ); ?>" class="button button-bordered button-bordered-white resources-card__button">
							<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="section-resources__message">
		<?= esc_html( __( 'Sorry, but nothing matched your search terms. Please try again with different filters.', THEME_TEXTDOMAIN ) ) ?>
	</div>
<?php endif; ?>