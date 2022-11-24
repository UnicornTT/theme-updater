		<?php if ( !is_404() ) : ?>
			<?= get_template_part( 'parts/footer/footer' ); ?>

			</div><!-- .main-wrapper -->

			<?php \Harbinger_Marketing\Modal_Action::render_modals() ?>
		<?php endif; ?>

		<?php wp_footer() ?>
	</body>
</html>