<?php get_header(); ?>
<div class="mh-row clearfix">
	<div id="main-content" class="mh-content"><?php
		mh_squared_lite_before_post_content();
		while (have_posts()) : the_post();
			get_template_part('content', 'single');
			mh_squared_lite_postnav();
			mh_squared_lite_authorbox();
			comments_template();
		endwhile; ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>