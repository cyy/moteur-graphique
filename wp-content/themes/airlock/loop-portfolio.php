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
//<script type="text/javascript" src="img/jquery.isotope.min.js"></script>
wp_enqueue_script('jquery.isotope', TPL_JS . '/jquery.isotope.js', array( 'jquery' ) );
wp_enqueue_script('go-top', TPL_JS . '/go-top.js', array( 'jquery' ) );
wp_enqueue_script('ext', TPL_JS . '/ext.js', array( 'jquery' ) );

?>


<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h2 class="mm"><?php _e( 'Apologies, but no results were found for the requested archive. ', TPL_SLUG ); ?></h2>
	</div>
<?php endif; ?>

<?php
	/* Start the Loop.
****************************
	*/ ?>
<?php while ( have_posts() ) : the_post(); ?>
		 	<?php
		 	$style = '';
		 	$selected_slug = '';
		 	//for home page display 
		 	$only_images = ($apollo13->get_option( 'design_options', 'hp_portfolio_images_switch' ) == 'on')? true : false;
		 	if( defined( 'ORG_IS_FRONT_PAGE' ) && ORG_IS_FRONT_PAGE ){
			 	$selected_slug = $apollo13->get_option( 'design_options', 'hp_portfolio_category' );
			 	if( $selected_slug != 'all' ) 
		 			$style = ' style="display: none;"';
		 		else
				 	$selected_slug = '';
		 	}
//		 	echo 'sb_tumb ' . get_post_meta(get_the_ID(), '_sidebar_thumb', true) . '<br />';
		 	$item_desc = trim( get_post_meta(get_the_ID(), '_project_desc', true) );
			$featured = get_post_meta(get_the_ID(), '_featured', true);
		 	
			$term_list = wp_get_post_terms($post->ID, 'skills', array("fields" => "all"));
			$item_classes = '';
			
			//featured check
			if( $featured == 'yes' ){
				$item_classes .= ' ' . PORTFOLIO_PRE_CLASS . 'featured';
				//for home page display 
				if( $selected_slug && $selected_slug == 'featured' ){
					$style = ' style="display: block;"';
				}
			}
			
			//only images class
			if( defined( 'ORG_IS_FRONT_PAGE' ) && ORG_IS_FRONT_PAGE && $only_images )
				$item_classes .= ' no-bottom-border';
				
			if( count( $term_list ) ):
				foreach($term_list as $term) {
					$item_classes .= ' ' . PORTFOLIO_PRE_CLASS . $term->slug;
					//for home page display 
					if( $selected_slug && $selected_slug == $term->slug )
						$style = ' style="display: block;"';
				}
			endif;
			?>
		<div id="post-<?php the_ID(); ?>" class="item<?php echo $item_classes ?>"<?php echo $style; ?>>
			<?php
				$item_height = $apollo13->get_option( 'thumbs_options', 'portfolio_thumb' );
				$apollo13->portfolio_top_image_video( 220, $item_height ); 
			?> 
			<?php 
				if( defined( 'ORG_IS_FRONT_PAGE' ) && ORG_IS_FRONT_PAGE && $only_images ):
				else:
			?>
			<div class="item-content">
				<?php 
			 		echo '<h2><a href="'. get_permalink() . '">' . get_the_title() . '</a></h2>'; 
				?>
				<div class="post-text">
					<?php echo strlen( $item_desc )? $item_desc : ''; ?>
				</div>
				<div class="post-categories">
					<?php $apollo13->portfolio_posted_in( $term_list ); ?>
					<span class="fr" >par <?php echo get_the_author()?></span>
				</div>
			</div>
	<!--BEGIN .entry-meta .entry-header-->
	<div class="entry-div">
    <ul class="entry-meta entry-header clearfix">
             
    <li class="published">
        <a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>">
            <span class="icon"></span>
            <?php //echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'framework'); ?>
            <?php echo 'Il ya'.' '.human_time_diff(get_the_time('U'), current_time('timestamp')); ?>
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
    <!--END .entry-meta entry-header -->
    </ul>
    </div>
			<?php endif; ?>
			
		</div><!-- #post-## -->

<?php endwhile; // End the loop. Whew. ?>