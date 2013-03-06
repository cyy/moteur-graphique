<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 */
global $apollo13;
?>


<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h2><?php _e( 'Apologies, but no results were found for the requested archive. ', TPL_SLUG ); ?></h2>
	</div>
<?php endif; ?>

<?php
	/* Start the Loop.
****************************
	*/ ?>
<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>
		 	
			<?php if( is_home() || is_archive() || ( defined( 'ORG_IS_FRONT_PAGE' ) && ORG_IS_FRONT_PAGE ) ) $apollo13->top_image_video( get_the_ID() ); ?>
			
			<div class="item-content">
				<?php 
			 		echo '<h2><a href="'. get_permalink() . '">' . get_the_title() . '</a></h2>'; 
				?>
				<div class="post-info">
					<?php
						$apollo13->posted_on(); 
					?><span>/</span>
					<?php echo ' <a class="comments" href="' . get_comments_link() . '" title="">' . get_comments_number() . ' ' . __( 'comment(s)', TPL_SLUG ). '</a>'; ?>
					<?php edit_post_link( __( 'Edit', TPL_SLUG ),'<span>|</span> ' ); ?>
				</div>
				<div class="post-text">
					<?php the_excerpt(); ?>
					<div class="clear"></div>
				</div>
				<div class="post-categories">
					<?php $apollo13->posted_in(); ?>
				</div>
			</div>
			
	<!--BEGIN .entry-meta .entry-header-->
    <ul class="entry-meta entry-header clearfix">
             
    <li class="published">
        <a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>">
            <span class="icon"></span>
            <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'framework'); ?>
        </a>
    </li>
             
    <?php if(!is_singular()) : ?>
    <li class="like-count">
        <?php tz_printLikes(get_the_ID()); ?>
    </li>
    <?php endif; ?>
             
    <li class="comment-count">
        <?php comments_popup_link(__('<span class="icon"></span> 0', 'framework'), __('<span class="icon"></span> 1', 'framework'), __('<span class="icon"></span> %', 'framework')); ?>
    </li>
             
    <?php if(is_singular()) : ?>
    <li class="like-count">
        <?php tz_printLikes(get_the_ID()); ?>
    </li>
    <?php endif; ?>
             
    <?php //edit_post_link( __('[Edit]', 'framework'), '<li class="edit-post">', '</li>' ); ?>
             
    <!--END .entry-meta entry-header -->
    </ul>
    
		</div><!-- #post-## -->

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
