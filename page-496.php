<?php get_header(); ?>
<div class="content">
	<section id="main-content" class="full-width">
		<?php
		if (post_password_required()) :
			echo get_the_password_form();
		else :
			if (have_posts()) : while (have_posts()) : the_post(); ?>

					<?php echo get_the_content(get_the_ID()); ?>

				<?php endwhile; ?>
			<?php else : ?>
				<?php get_template_part('content', 'none'); ?>
		<?php endif;


		// if password not required or password cookie is present
		// your protected content here
		endif;
		?>
	</section>
</div>
<?php get_footer(); ?>