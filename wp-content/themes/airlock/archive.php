<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>
	<?php get_sidebar(); ?>
	<div id="content">
	<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>
				<h1 class="page-title mm">
<?php if ( is_author() ) : ?>
				<?php printf( __( 'Author Archives: %s',  TPL_SLUG  ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" ); ?>
<?php elseif ( is_category() ) : ?>
				<?php printf( __( 'Category Archives: %s',  TPL_SLUG  ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
<?php elseif ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>', TPL_SLUG ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>', TPL_SLUG ), get_the_date( 'F Y' ) ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>', TPL_SLUG ), get_the_date( 'Y' ) ); ?>
<?php else : ?>
				<?php _e( 'Blog Archives', TPL_SLUG ); ?>
<?php endif; ?>
				</h1>
		
		<div class="posts-elastic elastic">

			<?php
			/* Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();
			
			/* Run the loop to output the post.
			 * If you want to overload this in a child theme then include a file
			 * called loop-single.php and that will be used instead.
			 */
			get_template_part( 'loop' );
			?>
		</div>
		<?php $apollo13->blog_nav(); ?>
		<?php if(function_exists('wp_paginate')) {
			wp_paginate();
		} ?>
		<div class="cleared"></div>
</div>
<?php get_footer(); ?>
