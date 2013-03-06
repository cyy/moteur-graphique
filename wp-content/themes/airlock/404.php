<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 */
define( 'FULL_WIDTH', true );
get_header(); ?>
	<div id="content" class="full error-page">
		<h1 class="mm"><?php _e( 'Error 404. The page you’re looking for can’t be found', TPL_SLUG ); ?></h1>
		<div class="links">
			<a href="javascript:history.go(-1)"><?php _e( 'Go back', TPL_SLUG ); ?></a>
			<span>/</span>
			<?php printf( __( '<a href="%1$s" title="Home Page">Go to Home Page</a>', TPL_SLUG ), get_bloginfo('url') ); ?>
		</div>
	</div>
		
<?php get_footer(); ?>