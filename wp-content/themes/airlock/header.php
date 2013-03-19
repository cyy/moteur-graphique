<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', TPL_SLUG ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta name="author" content="Apollo13 Team" />

<!-- Fav Icon -->
<link rel="shortcut icon" href="<?php echo TPL_GFX;?>/icon.png" />

<script type='text/javascript'>
/* <![CDATA[ */
var social_skins = {
	'light': '<?php echo TPL_GFX . '/light/social'; ?>',
	'dark' : '<?php echo TPL_GFX . '/dark/social'; ?>'
};
/* ]]> */
</script>
<?php wp_head(); ?>
</head>

<body <?php body_class( 'theme-color-' . TPL_COLOR ); ?>>
	<div id="access" role="navigation">
		<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
		<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', TPL_SLUG ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
	</div><!-- #access -->

	<?php 
		global $apollo13;
		$layout_width = $apollo13->get_option( 'settings', 'layout_width' );
	?>
	<div id="root"<?php echo ($layout_width == '960')? ' class="narrow"' : ''; ?>>
		<div id="header">
			<div id="logo"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"></a></div>
				
			<div id="menu">
				<?php 
					if ( has_nav_menu( 'header-menu' ) ): 
				/* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */
						wp_nav_menu( array( 
							'container'       => false,
							'link_before'     => '<span>',
							'link_after'      => '</span>',
						 	'theme_location'  => 'header-menu' ) 
						); 
					else: 
						wp_nav_menu( array( 
							'container'       => false,
							'link_before'     => '<span>',
							'link_after'      => '</span>' ) 
						);
					endif;
				 ?>
			</div>
			
			<?php 
				if( $apollo13->get_option( 'settings', 'theme_switcher' ) == 'on' ){
					$detect = '';
					if( $apollo13->get_option( 'settings', 'theme_detect' ) == 'on' )
						$detect = ' class="detector"';
					echo '<span id="night-toggle"' . $detect . '><span class="norm"></span><span class="hov"></span></span>';
				}
			?>
			
			<?php get_search_form(); ?>
			
			<?php 
			$social_width = 39 * $apollo13->get_option( 'social_options', 'social_number_of_visible' ); 
			$icons_set = $apollo13->get_option( 'social_options', 'social-icons-set' );
			?>
			<div class="socials" style="width: <?php echo $social_width; ?>px">
				<div class="slide">
				<?php
					foreach( (array)$apollo13->get_option( 'social_options', 'social_services' ) as $id => $value ){
						if( ! empty($value) ){
							echo '<a target="_blank" href="' . $value . '" title="' . __( 'Follow us on ', TPL_SLUG ) . $apollo13->all_theme_options[ 'social_options' ][ 'social_services' ][ $id ] . '"><img src="' . TPL_GFX . '/' . TPL_COLOR . '/' . $icons_set . '/' . $id . '.png" alt="" /></a>';
						}
					}
				?>
				</div>
			</div>
		</div>
		
		<?php
			$mid_class = '';
			if( defined('FULL_WIDTH') && FULL_WIDTH )
				$mid_class = ' class="full-width"';
			elseif( defined('SIDEBAR_POS') && SIDEBAR_POS == 'right' )
				$mid_class = ' class="sidebar-right"';
			elseif( !defined('SIDEBAR_POS') ){
				$elsewhere_sidebar_switch = $apollo13->get_option( 'settings', 'sidebar_switch' ); 
				if( $elsewhere_sidebar_switch == 'off' )
					$mid_class = ' class="full-width"';
				elseif( $elsewhere_sidebar_switch == 'right' )
					$mid_class = ' class="sidebar-right"';
			}
				
		
		?>
		<?php if(function_exists('wp_content_slider')) { wp_content_slider(); } ?>
		<div id="mid"<?php echo $mid_class; ?>>
<?php if( 0 && WP_DEBUG ): ?>
	<?php echo "is front page " . is_front_page(); ?><br /> 
	<?php echo "is home " . is_home(); ?><br /> 
	<?php echo "is page " . is_page(); ?> <br />
	<?php echo "is single " . is_single(); ?> <br />
	<?php echo "is singular " . is_singular(); ?> <br />
	<?php echo "is 404 " . is_404(); ?> <br />
	<?php echo "is archive " . is_archive(); ?> <br />
	<?php echo "is category " . is_category(); ?> <br />
	<?php echo "is attachment " . is_attachment(); ?> <br />
	<?php echo "is search " . is_search(); ?> <br />
<?php endif; ?>