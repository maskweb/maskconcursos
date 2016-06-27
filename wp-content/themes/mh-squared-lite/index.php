<?php get_header(); ?>
<div class="mh-row clearfix">
	<div id="main-content" class="mh-col-2-3"><?php
		mh_squared_lite_before_page_content();
		mh_squared_lite_page_title(); ?>
		<?php if (category_description()) : ?>
			<div class="cat-description">
				<span><?php echo wp_kses_post(category_description()); ?></span>
			</div>
		<?php endif;?>
		<div id="mh-infinite"><?php
			if (have_posts()) :
				while (have_posts()) : the_post();
					get_template_part('content');
				endwhile;
			else :
				get_template_part('content', 'none');
			endif; ?>
		</div>
		<?php mh_squared_lite_pagination(); ?>
	</div>
	<?php get_sidebar(); ?>
	</div>
<?php get_footer(); ?>