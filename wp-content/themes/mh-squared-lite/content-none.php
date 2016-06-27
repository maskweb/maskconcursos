<article class="post-wrapper">
	<div class="entry-content"><?php
		if (is_search()) : ?>
			<p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'mh-squared-lite'); ?></p><?php
		else : ?>
			<p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for, perhaps searching can help?', 'mh-squared-lite'); ?></p><?php
		endif;
		get_search_form(); ?>
	</div>
</article>