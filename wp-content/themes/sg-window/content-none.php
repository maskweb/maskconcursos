<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage sgwindow
 * @since SG Window 1.0.0
 */
?>
<div id="primary" class="content-area">
	<div class="nothing-found">
		<article <?php post_class(); ?>>

			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Ohhh!! No hemos encontrado nada', 'sg-window' ); ?></h1>
			</header>


			<div class="entry-content">

			<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( '¿Estas lista para publicar tu primer post? <a href="%1$s">Comienza aquí</a>.', 'sg-window' ), admin_url( 'post-new.php' ) ); ?></p>

			<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Lo sentimos, pero no hemos encontrado nada que coincida con los términos de tu búsqueda. Por favor, inténtalo de nuevo con palabras clave diferentes.', 'sg-window' ); ?></p>
			<?php get_search_form(); ?>

			<?php else : ?>

			<p><?php _e( 'Parece que no encontramos lo que buscas, quizás puedes probar con otros términos.', 'sg-window' ); ?></p>
			<?php get_search_form(); ?>

			<?php endif; ?>
			
			<footer class="entry-footer">
				<?php do_action( 'sgwindow_after_content' ); ?>	
			</footer><!-- .entry-footer -->	
			
		</article>
		
	</div><!-- .nothing-found -->
</div><!-- #content-area-->