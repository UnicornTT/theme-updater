<?php
add_action( 'wp_enqueue_scripts', 'hmt_enqueue_styles_author' );
function hmt_enqueue_styles_author() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-author-styles', THEME_ASSETS_URL . '/theme/css/blocks/section-author.css' );
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-related-styles', THEME_ASSETS_URL . '/theme/css/blocks/section-related.css' );
}

add_action( 'wp_enqueue_scripts', 'hmt_enqueue_scripts_author' );
function hmt_enqueue_scripts_author() {
	wp_enqueue_script( THEME_TEXTDOMAIN . '-section-related-scripts', THEME_ASSETS_URL . '/theme/js/blocks/section-related.js', array('jquery'), false, true );
}

get_header()
?>


<?php
$author_id = get_the_author_meta( 'ID' );
$author_posts = hmt_get_user_post_list( $author_id );
$related_title = 'Author’s Articles';

$id = '';
$block_style = '';

$class_name = 'section section-author';
$class_name .= ' section-author--style-v2' . $block_style;

$author_id = get_the_author_meta( 'ID' );

$background_type = 'none';
$user_image_id = get_field( 'user_avatar', 'user_' . $author_id );
$user_description = get_field( 'user_description', 'user_' . $author_id );
$user_position = get_field( 'user_position', 'user_' . $author_id );
?>


<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-author__bg',
			'field_prefix' => 'author_section_background',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="section-author__content">
			<div class="container">
				<div class="section-author__top">
					<a href="<?= esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="author-link section-author-link--back">
						<span class="author-link__icon icon icon-wrap" aria-hidden="true">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
						</span>

						<span class="author-link__text">
							<?= esc_html( __( 'Go Back', THEME_TEXTDOMAIN ) ); ?>
						</span>
					</a>
				</div>

				<div class="row justify-content-between">
					<div class="col-xl-4 col-md-6 col-12">
						<div class="section-author__left">
							<?php if ( !empty( $user_image_id ) ) : ?>
								<div class="section-author__image-cover">
									<picture>
										<source srcset="<?= esc_url( wp_get_attachment_image_url( $user_image_id, 'section-background-4k' ) ) ?>" media="(min-width: 1920px)">
										<source srcset="<?= esc_url( wp_get_attachment_image_url( $user_image_id, 'section-background-desktop' ) ) ?>" media="(min-width: 1280px)">
										<source srcset="<?= esc_url( wp_get_attachment_image_url( $user_image_id, 'section-background-tablet' ) ) ?>" media="(max-width: 1279px)">
										<source srcset="<?= esc_url( wp_get_attachment_image_url( $user_image_id, 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
										<img src="<?= esc_url( wp_get_attachment_image_url( $user_image_id, 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $user_image_id ) ); ?>">
									</picture>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="col-xl-7 col-md-6 col-12">
						<div class="section-author__right">
							<div class="section-author__bio">
								<div class="section-author__header">
									<div class="section-author__subtitle section-title section-title--style6">
										<?= esc_html( __( 'Author Profile', THEME_TEXTDOMAIN ) ); ?>
									</div>

									<div class="section-title section-title--style1 section-author__name">
										<h1>
											<?= esc_html( get_the_author() ); ?>
										</h1>
									</div>
								</div>

								<?php if ( !empty( $user_position ) ) : ?>
									<div class="section-author__position section-title section-title--style4">
										<?= wp_strip_all_tags( $user_position ); ?>
									</div>
								<?php endif; ?>

								<?php if ( !empty( $user_description ) ) : ?>
									<div class="section-author__description text-content">
										<?= apply_filters( 'the_content', $user_description ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if( $author_posts ): ?>
	<?= get_template_part( 'template-parts/blocks/section-related/section', 'related', ['posts_list' => $author_posts, 'section_title' => $related_title] ); ?>
<?php endif; ?>

<?php get_footer() ?>