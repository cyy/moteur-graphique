<?php
/**
Template Name: Portfolio
 *
 */
global $apollo13; //for get_tempalte_part() calls
define( 'FULL_WIDTH', true );
/**** QUERY STRAT ****/
$original_query = $wp_query;
$title = get_the_title( $apollo13->get_option( 'portfolio_options', 'portfolio_page' ) );

//get from theme portfolio settings
$portfolio_mode = 'dynamic';
$portfolio_page = $apollo13->get_option( 'portfolio_options', 'portfolio_page' );
$per_page = $apollo13->get_option( 'portfolio_options', 'items_per_page' );
$offset = -1;
global $paged;
if( $per_page > -1 ){
	if($paged == 0)
		$offset = 0;
	else
	$offset = ($paged - 1) * $per_page;
}


$args = array(
	'posts_per_page' => $per_page,
	'offset' => $offset,
	'post_type' => PORTFOLIO_POST_TYPE,
	'post_status' => 'publish',
	'ignore_sticky_posts' => true,
//	'orderby'             => 'epo_custom'
);

$term_slug = get_query_var('term');
if( ! empty( $term_slug ) ){
	$portfolio_mode = 'static';
	$args['skills'] = $term_slug;//give portfolios from selected category
	$term_name = get_term_by( 'slug', $term_slug, 'skills');
	$title .= ' : ' . $term_name->name;
}
//get portfolio posts
$wp_query = new WP_Query( $args );

/*** QUERY END ***/

get_header(); ?>
		<?php
		?>
	<div id="content">
		<!-- <h1 class="page-title mm"><?php echo $title; ?></h1>  -->
		<?php
		if ($wp_query->have_posts()) :
			//get portfolio categories
			$terms = get_terms('skills', 'hide_empty=1');
			$separator = '<span>/</span>';
			$count_terms = count( $terms );
			$iter = 1;
			if( $count_terms ):
				echo '<div id="portfolioList" class="' . $portfolio_mode .'">';
				//if( $apollo13->get_option( 'portfolio_options', 'show_featured' ) == 'yes' && empty( $term_slug ) ){
				if( $apollo13->get_option( 'portfolio_options', 'show_featured' ) == 'yes' ){
					$slug = 'featured';
					//echo '<a class="' . PORTFOLIO_PRE_CLASS . $slug . '" href="' . site_url() . '?page_id=' . $portfolio_page . '">' . __( 'Featured', TPL_SLUG ) . '</a>';
					
					echo '<a class="' . PORTFOLIO_PRE_CLASS . $slug . '" href="' . site_url() . '?top=like">' . __( 'Top', TPL_SLUG ) . '</a>';
					echo $separator;
				}
				//echo '<a href="' . site_url() . '?page_id=' . $portfolio_page . '" class="' . PORTFOLIO_PRE_CLASS . 'all' . ( empty( $term_slug ) ? ' selected' : '' ) . '">' . __( 'All', TPL_SLUG ) . '</a>';
				
				echo '<a href="' . site_url() . '" class="' . PORTFOLIO_PRE_CLASS . 'all' . ( empty( $term_slug ) ? ' selected' : '' ) . '">' . __( 'Tous', TPL_SLUG ) . '</a>';
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
		endif;
		?>
		<div class="portfolio-elastic elastic">
	
			<?php
				get_template_part( 'loop', 'portfolio' );
			?>
		</div>
		<?php $apollo13->blog_nav(); ?>
		<?php if(function_exists('wp_paginate')) {
			wp_paginate(array(
				'page' => $paged,
				'pages' => intval(ceil($wp_query->found_posts / $per_page))
			));
		} ?>
		<div class="cleared"></div>
		<?php
			// Reset the global $the_post as this query will have stomped on it
			$wp_query = $original_query;
			wp_reset_postdata();
		?>
	</div>
<?php get_footer(); ?>