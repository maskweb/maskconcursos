<?php $mh_squared_lite_options = mh_squared_lite_theme_options(); ?>
<article <?php post_class('content-list clearfix'); ?>>
	<?php if (has_category()) { ?>
    	<span class="content-list-category">
    		<?php $category = get_the_category(); echo $category[0]->cat_name; ?>
    	</span>
    <?php } ?>
    <div class="content-list-item clearfix">
    	<?php if (has_post_thumbnail()) { ?>
    		<div class="content-list-thumb">
    			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
    				<?php the_post_thumbnail('mh-squared-content'); ?>
				</a>
			</div>
    	<?php } ?>
		<header class="content-list-header">
        	<h2 class="content-list-title">
        		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
        			<?php the_title(); ?>
        		</a>
        	</h2>
			<?php mh_squared_lite_post_meta(); ?>
		</header>
        <div class="content-list-excerpt">
        	<?php the_excerpt(); ?>
        </div>
        <?php if ($mh_squared_lite_options['read_more'] != '') { ?>
            <div class="content-list-more">
            	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
            		<span><?php echo esc_attr($mh_squared_lite_options['read_more']); ?></span>
            	</a>
            </div>
		<?php } ?>
    </div>
</article>