<?php
/**
 * The Template for displaying portfolios.
 *
 */
define( 'PORTFOLIO_PAGE', true );
$sidebar_switch = $apollo13->get_option( 'portfolio_options', 'sidebar_switch' ); 
if( $sidebar_switch == 'off' )
	define( 'FULL_WIDTH', true );
else{
	define( 'SIDEBAR_POS', $sidebar_switch );
}

get_header(); 

?>
<?php get_sidebar(); ?>
	<div id="content">
		<div class="inner-640 post-blog post-portfolio">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<h1 class="page-title mm"><?php the_title() ?></h1>
		<div class="post-info">
			<?php
				$url = trim( get_post_meta(get_the_ID(), '_project_url', true) );
				$vis_url = $url;
				if (preg_match("/(http:\/\/)?(.+)/s", $url, $matches)){
					$vis_url = $matches[2];
				}
				if( strlen( $url ) ){
					echo __( 'Website: ', TPL_SLUG ) . '<a href="' . $url . '" target="_blank">' . $vis_url . '</a><span>/</span>';   
				}
				$term_list = wp_get_post_terms($post->ID, 'skills', array("fields" => "all"));
				echo '<span class="categories">';
				$apollo13->portfolio_posted_in( $term_list, ', ' );
				echo '</span>';
				edit_post_link( __( 'Edit', TPL_SLUG ),'<span>|</span> ' );
				$content = get_the_content(); 
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
			?>
		</div>
		<?php if( strlen( $content ) ):  ?>
		<div id="post-<?php the_ID(); ?>">
			<div class="post-text">
				<?php echo $content; ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php endif; ?>
		<?php
			$variant = substr( get_post_meta(get_the_ID(), '_variant', true), 8 );
			//special case if liquid
			if( $variant == 'liquid' ) echo '</div>';
			
			$apollo13->make_collection( $variant );
			
			if( $variant == 'liquid' ) echo '<div class="inner-640 post-blog post-portfolio">';
		
			$comments_switch = $apollo13->get_option( 'portfolio_options', 'portfolio_comments' );
			if( $comments_switch == 'on' )
				comments_template( '', true );
			else{
				$off_comments_text = trim( $apollo13->get_option( 'portfolio_options', 'portfolio_comments_disabled' ) );
				if ( strlen( $off_comments_text )){
					echo '<div class="comments-area">
							<p class="nocomments">' . $off_comments_text . '</p>
						</div>';
				}
			}
		?>
<?php
	$custom = get_post_custom($post->ID);
?>
	
<?php endwhile; // end of the loop. ?>
	</div>
<?php $apollo13->blog_post_nav(); ?>
</div>
<?php get_footer(); ?>