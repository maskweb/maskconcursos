<?php
/**
 * The template for displaying SUB-Category pages
 *
 * @package WordPress
 * @subpackage sgwindow
 * @since SG Window 1.0.0
 */

get_header(); 
$sgwindow_layout = sgwindow_get_theme_mod( 'layout_archive' );
$sgwindow_layout_content = sgwindow_get_theme_mod( 'layout_archive_content' );
?>


<div class="main-wrapper <?php echo esc_attr(sgwindow_content_class( $sgwindow_layout_content )); ?> <?php echo esc_attr( $sgwindow_layout ); ?> ">
	
	<div class="site-content">

		<?php	/* maskconcursos developers */	?>
		<?php  $objCategory = get_category( get_query_var('cat') ); ?>
		<header class="archive-header">
			<h1 id="novedades">NOVEDADES</h1>
		</header>
		<div class="content">

		<?php $recent = new WP_Query("cat=".get_query_var('cat')."&showposts=6");
			while($recent->have_posts()) : $recent->the_post();
				get_template_part( 'content', sgwindow_get_content_prefix() );
				?>

			<?php endwhile; ?>
		</div>
		<div class="clear"></div>

		<?php   /* END  maskconcursos developers */   ?>

		<?php
				if ( have_posts() ) : ?>
				
					<header class="archive-header">
						<?php /* maskconcursos developers */ ?>
						<?php /* ?>
						<h1 class="archive-title"><?php printf( __( 'Ver todas las entradas de %s', 'sg-window' ), single_cat_title( '', false ) ); ?></h1>
 						<?php */ ?>
						<h1 class="archive-title"><?php printf( single_cat_title( '', false ) ); ?></h1>
					</header><!-- .archive-header -->
				
					<div class="content"> 

				<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'content', sgwindow_get_content_prefix() );
						
					endwhile; ?>
					</div><!-- .content -->
					<div class="clear"></div>
				
				<?php

					sgwindow_paging_nav();
				?>
					
					<div class="content-search">
						<?php do_action( 'sgwindow_after_content' ); ?>
					</div><!-- .content-search -->

				<?php	
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
	sgwindow_get_sidebar( sgwindow_get_theme_mod( 'layout_archive' ) );
	?>
</div> <!-- .main-wrapper -->

<?php
get_footer();