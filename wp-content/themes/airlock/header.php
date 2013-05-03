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
<link rel="shortcut icon" href="/favicon.ico" />
<script type='text/javascript'>
/* <![CDATA[ */
var social_skins = {
	'light': '<?php echo TPL_GFX . '/light/social'; ?>',
	'dark' : '<?php echo TPL_GFX . '/dark/social'; ?>'
};
/* ]]> */
</script>
<?php 
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);
?>
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
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
	?>
	
	<div id="header">
			<div id="logo"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"></a></div>
			 
			<div id="menu">
				<?php
					/**if ( has_nav_menu( 'header-menu' ) ): 
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
					endif;**/
				 ?>
				 <ul>
				 	<li id="menu_formation<?php if ($_SERVER['REQUEST_URI'] == '/formation/' || $wp_query->is_category){echo '_active';}?>">
				 		<a href="<?php echo home_url( '/' ); ?>formation/ecole-de-design/" title="Formation">Formation</a>
				 	</li>
				 	<li class="menu_li"></li>
				 	<li class="menu_li"></li>
				 	<li class="menu_li"></li>
				 	<li id="menu_contact<?php if ($_SERVER['REQUEST_URI'] == '/contact/'){echo '_active';}?>">
				 		<a href="<?php echo home_url( '/' ); ?>contact" title="Contact">Contact</a>
				 	</li>
				 	<li class="menu_search_form"> <?php get_search_form(); ?></li>
				 </ul>
			</div>
			<div style="clear: left;"></div>
			<?php 
				if( $apollo13->get_option( 'settings', 'theme_switcher' ) == 'on' ){
					$detect = '';
					if( $apollo13->get_option( 'settings', 'theme_detect' ) == 'on' )
						$detect = ' class="detector"';
					echo '<span id="night-toggle"' . $detect . '><span class="norm"></span><span class="hov"></span></span>';
				}
			?>
			
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
	<div id="sub_menu">
		<div id="menu_cafe">
			<div class="cafe_content">
				<p>“Si le site HD vous a été utile et que vous êtes satisfaits de notre contenu, vous pouvez toujours nous inviter à boire un café. :) “</p>
				<p>Merci</p>
				<p class="equipe_p">L‘équipe HD</p>
			</div>
			<div class="cafe_form">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="_xclick" id="J_cafe_form">
				<input type="hidden" name="cmd" value="_xclick" />
				<input type="hidden" name="business" value="omgdevil@gmail.com" />
				<input type="hidden" name="item_name" value="Un cafe pour Hello,Devil" />
				<input type="hidden" name="currency_code" value="EUR" />
				<div class="cafe_form_img">
					<a href="javascript:void(0);" id="J_buycafe">Cafe</a>
				</div>
				<div class="cafe_form_num">
					<select name="amount">
						<option value="1.5">Café 1.50 €
						<option value="3">Frozen café au lait 3.00 €
						<option value="5">Le dessert avec un bon café 5.00 €
					</select>
				</div>
				</form>
			</div>
		</div>
		<div class="sub_menu_cat"></div>
		<div id="menu_sign">
			<div class="fl">
				<p>Bonjour visiteur ! </p>
				<p>Il me semble que vous êtes nouveau ici. Si vous souhaitez participer à HD, cliquez sur un de ces boutons.</p>
			</div>
			<div class="sign_div">
					<a href="#" class="login_btn" id="J-login">login</a>
					<a href="#" class="reg_btn" id="J-register">register</a>
			</div>
			<div style="display: none;" id="loginDiv">
				<div class="sns_div">
					<div class="sns_big">
			            <input type="button" class="sns_pinterest" value="pinterest" />
			        </div>
			        <div class="sns_big">
			            <input type="button" class="sns_facebook" value="facebook" />
			        </div>
			        <div class="sns_big">
			            <input type="button" class="sns_twitter" value="twitter" />
			        </div>
			        <div class="sns_big">
			            <input type="button" class="sns_google" value="google" />
			        </div>
				</div>
				<div class="login_ou">OU</div>
				<div class="login_input">
					<ul>
						<li></li>
						<li></li>
					</ul>
				</div>
				<div class="login_btn">
					<div class="login_submit"></div>
				</div>
			</div>
			
			<div style="display: none;" id="registerDiv">
				<div class="sns_div">
					<div class="sns_big">
			            <input type="button" class="sns_pinterest" value="pinterest" />
			        </div>
			        <div class="sns_big">
			            <input type="button" class="sns_facebook" value="facebook" />
			        </div>
			        <div class="sns_big">
			            <input type="button" class="sns_twitter" value="twitter" />
			        </div>
			        <div class="sns_big">
			            <input type="button" class="sns_google" value="google" />
			        </div>
				</div>
				<div class="login_ou">OU</div>
				<div class="login_input">
					<ul>
						<li></li>
						<li></li>
					</ul>
				</div>
				<div class="login_btn">
					<div class="login_submit"></div>
				</div>
			</div>
			
		</div>
	</div>
	<div style="clear: left;"></div>
	<div id="root"<?php echo ($layout_width == '960')? ' class="narrow"' : ''; ?>>
		
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