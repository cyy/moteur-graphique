<?php
/**
 * The loop that displays a single post.
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
		
		<div class="inner-640 post-blog">
			<h1 class="page-title mm"><?php the_title() ?></h1>
			<div class="post-info">
				<?php $apollo13->posted_on(); ?><span>/</span>
				<?php echo ' <a class="comments" href="' . get_comments_link() . '" title="">' . get_comments_number() . ' ' . __( 'comment(s)', TPL_SLUG ). '</a>'; ?>
				<span>/</span>
				<span class="categories"><?php $apollo13->posted_in( ', ' ); ?></span>
				<?php edit_post_link( __( 'Edit', TPL_SLUG ),'<span>|</span> ' ); ?>
			</div>
			<?php
				$apollo13->make_collection('slider');
			?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>
				<div class="post-text">
					<?php the_content(); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php comments_template( '', true ); ?>
		</div>


<?php endwhile; // end of the loop. ?>