<?php
/**
Template Name: Contact
 * The template for displaying Contact form.
 *
 */
get_header(); ?>
	<?php get_sidebar(); ?>
	<div id="content">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php echo '<h1 class="page-title mm">' . get_the_title() . '</h1>'; ?>
			
		<div id="post-<?php the_ID(); ?>" <?php post_class('contact-page'); ?>>
			
			<?php echo $apollo13->contact_form( $apollo13->get_option( 'settings', 'contact_email' ) ); ?>
			<div class="text">
				<?php the_content(); ?>
			</div>
			
			<div class="clear"></div>
		</div>
	
	<?php endwhile; // end of the loop. ?>
	</div>		
<?php get_footer(); ?>