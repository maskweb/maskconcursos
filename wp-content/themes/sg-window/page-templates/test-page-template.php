<?php
/**
 * Template Name: Test Page Template
 *
 * @package WordPress
 * @subpackage sgwindow
 *
 */


get_header();

?>
<div class="main-wrapper <?php echo esc_attr( sgwindow_get_theme_mod('layout_page') ); ?> ">

	<div class="site-content"> 
			<?php
				if ( have_posts() ) : ?>
				
					<div class="content"> 

				<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'content-mkc', 'page' );

						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						
					endwhile; ?>
					
					</div><!-- .content -->
					<div class="clear"></div>
				
				<?php

					sgwindow_paging_nav();
					
				else :  
				?>
					<div class="content"> 
					<?php 
						get_template_part( 'content', 'none' );
					?>
					
					</div><!-- .content -->
				<?php 
				endif;
?>
	</div><!-- .site-content -->
	<?php
	sgwindow_get_sidebar( sgwindow_get_theme_mod('layout_page') );
	?>
</div> <!-- .main-wrapper -->

<?php
get_footer();
