<?php
/**
 * The Template for displaying blog.
 *
 */

define( 'ORG_IS_FRONT_PAGE', true );

$hp_sidebar_switch   = $apollo13->get_option( 'design_options', 'hp_sidebar_switch' );
$hp_hello_switch     = $apollo13->get_option( 'design_options', 'hp_hello_switch' );
$hp_portfolio_switch = $apollo13->get_option( 'design_options', 'hp_portfolio_switch' );
$hp_blog_switch      = $apollo13->get_option( 'design_options', 'hp_blog_switch' );

$hp_hello_position     = $apollo13->get_option( 'design_options', 'hp_hello_position' );
$hp_portfolio_position = $apollo13->get_option( 'design_options', 'hp_portfolio_position' );
$hp_blog_position      = $apollo13->get_option( 'design_options', 'hp_blog_position' ); 


$modules = array();
if( $hp_hello_switch == 'on' )
	$modules[ $hp_hello_position ] = 'apollo13_hp_get_welcome';
if( $hp_blog_switch == 'on' )
	$modules[ $hp_blog_position ] = 'apollo13_hp_get_blog';
if( $hp_portfolio_switch == 'on' )
	$modules[ $hp_portfolio_position ] = 'apollo13_hp_get_portfolio';
if( $hp_sidebar_switch == 'off' )
	define( 'FULL_WIDTH', true );
else{
	define( 'SIDEBAR_POS', $hp_sidebar_switch );
}


ksort( $modules, SORT_NUMERIC );

get_header(); ?>
	<?php get_sidebar(); ?>
	<div id="content" class="front-page">
		<?php
			function apollo13_hp_get_blog(){
				
				global $wp_query, $apollo13;
				global $more;    // Declare global $more (before the loop).
                $more = 0;  
				$original_query = $wp_query;
				$hp_blog_title = trim( $apollo13->get_option( 'design_options', 'hp_blog_title' ) );
				$args = array(
					'posts_per_page'      => $apollo13->get_option( 'design_options', 'no_of_latest_posts' ),
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
//					'orderby'             => 'epo_custom'
				);
				$cat_slug = $apollo13->get_option( 'design_options', 'hp_blog_category' );
				switch( $cat_slug ){
					case 'apollo13-all':
						break;
					case 'apollo13-latest':
						$args['orderby'] = 'date';
						break; 
					case 'apollo13-popular':
						$args['orderby'] = 'comment_count';
						$args['order'] = 'DESC';
						break;
					default:
						$args['category_name'] = $cat_slug;
						break; 
				}
				
				
				$wp_query = new WP_Query( $args );
				if( strlen( $hp_blog_title ))
					echo '<h4 class="hp-blog-title">' . $hp_blog_title . '</h4>';
				echo '<div class="posts-elastic elastic">';
				//echo '<div class="portfolio-elastic elastic">';
					get_template_part( 'loop' );
					//get_template_part( 'loop', 'portfolio' );
				echo '</div>';
				
				$wp_query = $original_query;
				wp_reset_postdata();
			}
			
			function apollo13_hp_get_portfolio(){
				global $wp_query, $apollo13, $paged;
				$original_query = $wp_query;
				$portfolio_page = $apollo13->get_option( 'portfolio_options', 'portfolio_page' );
				$hp_portfolio_category = $apollo13->get_option( 'design_options', 'hp_portfolio_category' );
				$hp_portfolio_pagiantion = ($apollo13->get_option( 'design_options', 'hp_portfolio_pagination' ) == 'on') ? true : false;
				$portfolio_mode = 'dynamic';
				$per_page = $apollo13->get_option( 'design_options', 'hp_portfolio_items_per_page' );
				$offset = -1;
				$paged = 0;
				//fix for front page pagination
				if ( get_query_var('paged') ) {
				    $paged = get_query_var('paged');
				} elseif ( get_query_var('page') ) {
				    $paged = get_query_var('page');
				} else {
				    $paged = 1;
				}
				
				if( $per_page > -1 ){
					if($paged == 0)
						$offset = 0;
					else
					$offset = ($paged - 1) * $per_page;
				}
				$args = array(
					'posts_per_page'      => $per_page,
					'offset'              => $offset,
					'post_type'           => PORTFOLIO_POST_TYPE,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					//'meta_key'			  => '_likes',
					//'orderby'             => 'meta_value'
				);
				
				if( ! in_array( $hp_portfolio_category, array('all','featured') ) ){
					$args['skills'] = $hp_portfolio_category;//give portfolios from selected category
				}
				
				if ($_GET['top']) {
					$args['meta_key'] = '_likes';
					$args['orderby'] = 'meta_value_num';
				}
				
				$wp_query = new WP_Query( $args );
				
				if ($wp_query->have_posts()) :
					echo '<div class="portfolio-fp-container">';
					//get portfolio categories
					$terms = get_terms('skills', 'hide_empty=1');
					$separator = '<span>/</span>';
					$count_terms = count( $terms );
					$iter = 1;
					if( $count_terms ):
						echo '<div id="portfolioList" class="' . $portfolio_mode .'">';
						$term_slug = $hp_portfolio_category;
						if( $apollo13->get_option( 'portfolio_options', 'show_featured' ) == 'yes' ){
							$slug = 'featured';
							//echo '<a class="' . PORTFOLIO_PRE_CLASS . $slug . ( ($term_slug == 'featured') ? ' selected' : '' ) . '" href="' . site_url() . '?page_id=' . $portfolio_page . '">' . __( 'Featured', TPL_SLUG ) . '</a>';
							
							echo '<a class="' . PORTFOLIO_PRE_CLASS . $slug . ( ($term_slug == 'featured' || $_GET['top']) ? ' selected' : '' ) . '" href="' . site_url() .'?top=like">' . __( 'Top', TPL_SLUG ) . '</a>';
							echo $separator;
						}
						//echo '<a href="' . site_url() . '?page_id=' . $portfolio_page . '" class="' . PORTFOLIO_PRE_CLASS . 'all' . ( ($term_slug == 'all') ? ' selected' : '' ) . '">' . __( 'All', TPL_SLUG ) . '</a>';
						
						echo '<a href="' . site_url() . '" class="' . PORTFOLIO_PRE_CLASS . 'all' . ( ($term_slug == 'all' && !$_GET['top']) ? ' selected' : '' ) . '">' . __( 'Tous', TPL_SLUG ) . '</a>';
						echo $separator;
						foreach($terms as $term) {
							echo '<a class="' . PORTFOLIO_PRE_CLASS . $term->slug . ( ($term_slug == $term->slug) ? ' selected' : '' ) . '" href="' . get_term_link($term) . '">' . $term->name . '</a>';
							if( $count_terms != $iter ){
								echo $separator;
							}
							$iter++;
						}
						echo '</div>';
					endif;
					
					echo '<div class="portfolio-elastic elastic">';
						get_template_part( 'loop', 'portfolio' );
					echo '</div>';
					if( $hp_portfolio_pagiantion ){
						$apollo13->blog_nav();
						if(function_exists('wp_paginate')) {
							wp_paginate(array(
								'page' => $paged,
								'pages' => intval(ceil($wp_query->found_posts / $per_page))
							));
						} ?>
						<div class="cleared"></div>
						<?php
					}
					echo '</div>';
				endif;
				
				
				$wp_query = $original_query;
				wp_reset_postdata();
			}
			
			function apollo13_hp_get_welcome(){
				global $apollo13;
				$hp_hello_text = $apollo13->get_option( 'design_options', 'hp_hello_text' );
				echo '<h1 class="hello-text mm">' . nl2br( $hp_hello_text ) . '</h1>';
			}
			
			foreach( $modules as $module ){
				$module();
			}
			?>
	</div>
<?php get_footer(); ?>