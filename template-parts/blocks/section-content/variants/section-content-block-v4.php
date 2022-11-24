<?php
/**
 * Section Content Block.
 *
 * @var $args
 */

$block_id = $args['block_id'];

$section_title = get_field( 'content_block_section_title' );
$section_articles_section = get_field( 'content_block_section_type_cards' );
$section_articles = $section_articles_section['content_block_section_cards'] ?? '';
?>

<?php if (
	$section_title
	&& is_array($section_articles)
	&& !empty( $section_articles )
	&& $section_articles[0]['content_block_section_cards_title']
	&& $section_articles[0]['content_block_section_cards_description']
) : ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-content-block__bg',
			'field_prefix' => 'content_block_section_background',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="section-content-block__content js-font-adjustment" data-fj-min="37">
			<div class="container">
				<div class="section-content-block__header">
					<?php if ( $section_title ) : ?>
						<div class="section-title section-title--style1 section-content-block__title">
							<h2 class="js-font-title">
								<?= esc_html( $section_title ); ?>
							</h2>
						</div>
					<?php endif; ?>
				</div>

				<div class="section-content-block__body">
					<?php if ( is_array( $section_articles ) ) : ?>
						<div class="section-content-block__body-wrapper js-horizontal-scroll">
							<div class="row article-align">
								<?php foreach ( $section_articles as $article ) : ?>
									<?php extract( $article ); ?>
									<?php if ( !empty( $content_block_section_cards_title ) && !empty( $content_block_section_cards_description ) ) : ?>
										<div class="col article-grid">
											<article class="article">
												<?php if ( $content_block_section_cards_title ) : ?>
													<div class="article__title">
														<h3>
															<?= esc_html( $content_block_section_cards_title ) ?>
														</h3>
													</div>
												<?php endif; ?>

												<?php if ( $content_block_section_cards_description ) : ?>
													<div class="article__description">
														<div class="scrollbar-outer">
															<div class="article__content text-content">
																<?= apply_filters( 'the_content', $content_block_section_cards_description ); ?>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</article>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>