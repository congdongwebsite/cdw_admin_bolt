<section class="mb-3">
	<div class="card bg-light">
		<div class="card-body">
			<div class="d-flex">
				<div class="flex-shrink-0"><?php echo get_avatar(get_the_author_meta('ID'), '96', '', 'avatar', array('class' => 'rounded-circle')); ?></div>
				<div class="ms-3">
					<div class="fw-bold">
						<?php printf(
							 __('Tác giả: ',  'congdongtheme') . ' <a href="%1$s">%2$s</a>',

							get_author_posts_url(get_the_author_meta('ID')),

							get_the_author()
						); ?>
					</div>
					<?php echo get_the_author_meta('description'); ?>
				</div>
			</div>
		</div>
	</div>
</section>