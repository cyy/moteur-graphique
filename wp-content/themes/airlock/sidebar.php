<?php
/**
 * The Sidebar
 *
 */
	if( defined('FULL_WIDTH') && FULL_WIDTH ){}
	else{
?>
			<div id="primary" class="widget-area" role="complementary">
				<?php 
					if( is_front_page() )
						dynamic_sidebar( 'frontpage-widget-area' );
					elseif( defined('PORTFOLIO_PAGE') && PORTFOLIO_PAGE )
						dynamic_sidebar( 'portfolio-widget-area' );
					elseif(  is_home() && ! is_front_page() )
						dynamic_sidebar( 'blog-widget-area' );
					elseif(  is_single() )
						dynamic_sidebar( 'blog-post-widget-area' );
					else{
						//in every other page	
						dynamic_sidebar( 'primary-widget-area' );
					} 
				?>
			</div><!-- #primary .widget-area -->
<?php } ?>