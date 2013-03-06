<?php
/**
 * The Template for displaying all single posts.
 *
 */
$blog_sidebar_switch = $apollo13->get_option( 'settings', 'blog_sidebar_switch' ); 
if( $blog_sidebar_switch == 'off' )
	define( 'FULL_WIDTH', true );
else{
	define( 'SIDEBAR_POS', $blog_sidebar_switch );
}
get_header(); ?>
<?php get_sidebar(); ?>
	<div id="content">
		<?php
		/* Run the loop to output the post.
		 * If you want to overload this in a child theme then include a file
		 * called loop-single.php and that will be used instead.
		 */
		get_template_part( 'loop', 'single' );
		?>
	<?php $apollo13->blog_post_nav(); ?>
	</div>
<?php get_footer(); ?>