<?php
/**
 * The loop that displays a page.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 */
global $apollo13;
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php 
			$wide = ' ' . get_post_meta(get_the_ID(), '_layout', true);
			if( $wide == ' full-width' )
				$wide = '';
		?>
		<h1 class="page-title mm"><?php the_title(); ?></h1>
		<div id="post-<?php the_ID(); ?>" <?php post_class('item' . $wide); ?>>
		<?php
		$image = $apollo13->prepare_path( get_post_meta(get_the_ID(), '_top_image', true) );
		if( !empty( $image ) ){
				$src = TPL_ADV . '/inc/timthumb.php?src=' . $image . '&amp;w=640&amp;zc=1';
				$attrs = get_post_meta(get_the_ID(), '_top_image_attr', true);
	 			?>
		 	<div class="item-image">
		 		<img src="<?php echo $src; ?>" <?php echo $attrs; ?> />
		 	</div> 
		 <?php } ?>
			<?php edit_post_link( __( 'Edit', TPL_SLUG ), '<div class="post-info">', '</div>' ); ?>
			<div class="post-text">
				<?php the_content(); ?>
				<div class="clear"></div>
			</div>
		</div>
<?php endwhile; // end of the loop. ?>