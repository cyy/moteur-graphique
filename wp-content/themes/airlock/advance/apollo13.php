<?php

class Apollo13 {
	
	//current theme settings
	private $theme_options = array();
	//all possible theme settings
	public $all_theme_options = array();

	function start() {
	
		/* Define bunch of helpful paths */
		define('TPL_SLUG', 'airlock');
		define('TPL_NAME', 'Airlock');
		define('TPL_URI', get_template_directory_uri());
		define('TPL_DIR', get_template_directory());
		define('TPL_COMMON', TPL_URI . '/common');
		define('TPL_COMMON_DIR', TPL_DIR . '/common');
		define('TPL_GFX', TPL_COMMON . '/gfx');
//		define('TPL_COLOR', 'light or dark' ); //defined in theme_color()
		define('TPL_CSS', TPL_COMMON . '/css');
		define('TPL_JS', TPL_COMMON . '/js');
		define('TPL_FONTS', TPL_COMMON . '/fonts');
		define('TPL_FONTS_DIR', TPL_COMMON_DIR . '/fonts');
		define('TPL_ADV', TPL_URI . '/advance');
		define('TPL_ADV_DIR', TPL_DIR . '/advance');
		define('TPL_PLUGINS', TPL_ADV . '/plugins');
		define('TPL_PLUGINS_DIR', TPL_ADV_DIR . '/plugins');
		define('USER_GENERATED', TPL_URI . '/user');
		define('USER_GENERATED_DIR', TPL_DIR . '/user');
		define('TPL_SHORTCODES_DIR', TPL_ADV_DIR . '/shortcodes');
		define('PORTFOLIO_POST_TYPE', 'portfolio');
//		define('PORTFOLIO_POST_SLUG', 'portfolio'); //defined a bit lower
		define('PORTFOLIO_PRE_CLASS', 'portfolio_cat-');//not to change(JS use it)
//		define('USE_RELATIVE_PATHS', false); //defined a bit lower
		
		
		
		/****** GET THEME OPTIONS ******/
		$this->set_options();
		if ( isset( $_POST[ 'theme_updated' ] ) ) {
			$options_name = str_replace( 'apollo13_', '', $_GET['page']);
			$this->update_options( $options_name );
		}
		$this->theme_color();
		
		define('PORTFOLIO_POST_SLUG', $this->theme_options[ 'portfolio_options' ][ 'portfolio_post_type' ]);
		define('USE_RELATIVE_PATHS', ( $this->theme_options[ 'advance_options' ][ 'timthumb_relative_paths' ] == 'yes' ? true : false ) );
		
		
		/****** ADD SHORTCODES ******/
		$this->shortcodes();
		
		/****** ACTION HOOKS ******/
		/* Languages */
		add_action( 'init' , array( &$this , 'set_lang') );
		
		/* ADMIN PART */
		if ( is_admin() ) {
			add_action( 'admin_init', array( &$this , 'admin_head' ) );
			add_action( 'admin_menu', array( &$this , 'admin_pages' ) );
			/* === Remove Featured Image Meta === */
			add_action( 'do_meta_boxes', array( &$this , 'remove_image_box' ) );
			add_action( 'add_meta_boxes', array( &$this , 'admin_meta_boxes' ) );
			/* Do something with the data entered */
			add_action( 'save_post', array( &$this , 'save_post' ) );

		}
		
		/* AFTER SETUP(supports and other settings, widgets, plugins) */
		add_action( 'after_setup_theme', array( &$this, 'setup' ) );
		add_action( 'widgets_init', array( &$this, 'widgets_and_plugins' ) );
		add_action( 'init', array( &$this, 'portfolio_register' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'portfolio_custom_columns' ) );
		add_filter( 'manage_edit-' . PORTFOLIO_POST_TYPE . '_columns', array( &$this, 'portfolio_edit_columns' ) );
		
		/* THEME SCRIPTS */
		add_action( 'wp_print_scripts', array( &$this, 'theme_scripts' ) );
		add_action( 'wp_print_styles', array( &$this, 'theme_styles' ) );
		add_action( 'wp_head',  array( &$this, 'theme_head' ) );
		
		/****** FILTER HOOKS ******/
		add_filter( 'get_search_form', array( &$this , 'search_form' ) );
		add_filter( 'excerpt_length', array( &$this , 'excerpt_length' ) );
		add_filter( 'excerpt_more', array( &$this , 'new_excerpt_more' ) );
		add_filter( 'post_limits', array( &$this , 'portfolio_post_limits' ) );
		add_filter( 'comments_open', array( &$this , 'portfolio_comments_open' ), 10, 2 );
		
		/********* AJAX ********/
		add_action('wp_ajax_portfolio_multi_upload', array( &$this , 'portfolio_multi_upload') );	

//		print_r( $this->theme_options );
//		print_r( $this->all_theme_options );
	}
	
	/* Languages support */
	function set_lang() {
		load_theme_textdomain( TPL_SLUG , TEMPLATEPATH . '/languages' );
		
		$locale = get_locale();
		$locale_file = TEMPLATEPATH."/languages/$locale.php";
		if ( is_readable($locale_file) )
    		require_once($locale_file);
	}
	
	function setup() {

		// This theme uses post thumbnails
//		add_theme_support( 'post-thumbnails' );
//		add_image_size( 'news-thumb', 180, 110, true ); 
//		add_image_size( 'air-post-thumb', 595, 183, true); //(cropped)
//		add_image_size( 'air-portfolio-big-thumb', 595, 250, true); //(cropped)
//		add_image_size( 'air-portfolio-thumb', 280, 180, true); //(cropped)
	
		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );
		
		// Add naviagation menu ability. 
		add_theme_support('menus');
		
		register_nav_menus(array(
			'header-menu' => __('Site Navigation', TPL_SLUG )
		));
		
		if(!is_writeable(USER_GENERATED_DIR))
			add_action( 'admin_notices', 'custom_error_notice' );
		function custom_error_notice(){
			echo '<div class="error"><p>Warning - ' . USER_GENERATED_DIR . ' is not writable.</p></div>';
		}
	}
	
	private function set_options(){
		require_once (TPL_ADV_DIR . '/options.php');
		
		$option_func = array(
			'settings',
			'design_options',
			'thumbs_options',
			'fonts_options',
			'color_options',
			'footer_options',
			'portfolio_options',
			'social_options',
			'advance_options',
		);
		
		foreach($option_func as $function){
			$function_to_call = 'apollo13_' . $function;
			
			//firstly collect all default setting
			foreach( $function_to_call() as $option) {
				//get current settings
				if (isset($option['default'])) {
					$this->theme_options[ $function ][ $option['id'] ] = $option['default'];
				}
				//get possible settings
				if (isset($option['options'])) {
					$this->all_theme_options[ $function ][ $option['id'] ] = $option['options'];
				}
			}
			
			$get_opt = get_option( TPL_SLUG . '_' . $function );
			
			//secondly overwrite with current settings
			if( ! empty( $get_opt ) && is_array( $get_opt ) ){
				$this->theme_options[ $function ] = array_merge( (array) $this->theme_options[ $function ] , $get_opt );
			}
			
			//clear data
			foreach( $this->theme_options[ $function ] as $key => $value ) {
				if( ! is_array( $value ))
					$this->theme_options[ $function ][ $key ] = stripslashes( $value );
				//TO DO: strip in array	
			}
		}
	}
	
	public function theme_color(){
		if( $this->theme_options[ 'settings' ][ 'theme_styles' ] == 'style-light' )
			define('TPL_COLOR','light');
		else
			define('TPL_COLOR','dark');
	}
	
	public function get_option( $index1, $index2 ){
		return $this->theme_options[ $index1 ][ $index2 ];
	}
	
	function widgets_and_plugins() {
		require_once (TPL_ADV_DIR . '/widgets.php');
		require_once (TPL_PLUGINS_DIR . '/flickrpress/flickr.php');
		
		// Shown on Home Page
		register_sidebar( array(
			'name' => __( 'Home Page sidebar', TPL_SLUG ),
			'id' => 'frontpage-widget-area',
			'description' => __( 'Widgets from this sidebar will appear on Home Page', TPL_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="title">',
			'after_title' => '</h3>',
		) );
		// Shown on main site of blog
		register_sidebar( array(
			'name' => __( 'Blog sidebar', TPL_SLUG ),
			'id' => 'blog-widget-area',
			'description' => __( 'Widgets from this sidebar will appear on main blog page', TPL_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="title">',
			'after_title' => '</h3>',
		) );
		// Shown on main site of blog
		register_sidebar( array(
			'name' => __( 'Blog post sidebar', TPL_SLUG ),
			'id' => 'blog-post-widget-area',
			'description' => __( 'Widgets from this sidebar will appear on blog posts', TPL_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="title">',
			'after_title' => '</h3>',
		) );
		// Shown on every site of portfolio(not on main)
		register_sidebar( array(
			'name' => __( 'Portfolio sidebar', TPL_SLUG ),
			'id' => 'portfolio-widget-area',
			'description' => __( 'Widgets from this sidebar will appear on every site of portfolio(not on main)', TPL_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="title">',
			'after_title' => '</h3>',
		) );
		// Shown on every site on bottom od sidebar.
		register_sidebar( array(
			'name' => __( 'Other pages sidebar', TPL_SLUG ),
			'id' => 'primary-widget-area',
			'description' => __( 'Widgets from this sidebar will appear on every other site with sidebar', TPL_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="title">',
			'after_title' => '</h3>',
		) );
	}
	function admin_pages() {
		add_menu_page(TPL_NAME, TPL_NAME, 'manage_options', 'apollo13_settings', array(&$this,'settings'), TPL_GFX . '/icon.png' );
		add_submenu_page('apollo13_settings', __( 'Main settings', TPL_SLUG ), __( 'Main settings', TPL_SLUG ), 'manage_options', 'apollo13_settings', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Home Page Design', TPL_SLUG ), __( 'Home Page Design', TPL_SLUG ), 'manage_options', 'apollo13_design_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Thumbs', TPL_SLUG ), __( 'Thumbs', TPL_SLUG ), 'manage_options', 'apollo13_thumbs_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Colors', TPL_SLUG ), __( 'Colors', TPL_SLUG ), 'manage_options', 'apollo13_color_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Fonts', TPL_SLUG ), __( 'Fonts', TPL_SLUG ), 'manage_options', 'apollo13_fonts_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Footer texts', TPL_SLUG ), __( 'Footer texts', TPL_SLUG ), 'manage_options', 'apollo13_footer_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Portfolio', TPL_SLUG ), __( 'Portfolio', TPL_SLUG ), 'manage_options', 'apollo13_portfolio_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Social', TPL_SLUG ), __( 'Social', TPL_SLUG ), 'manage_options', 'apollo13_social_options', array(&$this,'settings'));
		add_submenu_page('apollo13_settings', __( 'Advance', TPL_SLUG ), __( 'Advance', TPL_SLUG ), 'manage_options', 'apollo13_advance_options', array(&$this,'settings'));
	}
	
	function settings() {
		if (!current_user_can('manage_options')){
			wp_die( __( 'You do not have sufficient permissions to access this page.', TPL_SLUG ) );
		}
		global $title;  //get the title of page from <title> tag
//		require_once (TPL_ADV_DIR . '/options.php'); //done while collecting theme_options
		$option_list = $_GET['page']();
		//get name of options we will change
		$options_name = str_replace( 'apollo13_', '', $_GET['page']);
		
		?>
		<div class="wrap apollo13-settings metabox-holder" id="apollo13-settings">
			
			<h2><img style="vertical-align: -2px; margin-right: 10px;" src="<?php echo TPL_GFX; ?>/light/logo.png" /><?php _e( $title ); ?></h2>
			<?php 
				if ( isset( $_POST[ 'theme_updated' ] ) ) {
//					$this->update_options( $options_name );//not here anymore
			?>
					<div id="message" class="updated">
						<p><?php printf( __( 'Template updated. <a href="%s">Visit your site</a> to see how it looks.', TPL_SLUG ), home_url( '/' ) ); ?></p>
					</div>
			<?php } 
				$this->print_options( $option_list, $options_name ); 
			?>

		</div>
		<?php 
	}
	
	function print_options( &$options, $opt_name ){
		?>
			<form method="post" action="">
				<?php 
					$fieldset_open = false;
					$block_open = false;
					foreach( $options as $option ) {
						if ( $option['type'] == 'fieldset' ) {
							if ( $fieldset_open ) {
								if( $block_open ){
									echo '</div>
									</div>';
									$block_open = false;
								}
								echo '</div>
									</div>';
								?>
								<div class="save-opts"><input type="submit" name="theme_updated" class="button-primary autowidth" value="<?php _e( 'Save Changes', TPL_SLUG ); ?>" /></div>
								<?php
							}
							echo '<div class="postbox">
									<h3><span>' . $option['name'] . '</span></h3>
									<div class="inside">';
							$fieldset_open = true;
						}
						elseif ( $option['type'] == 'block' ) {
							if( $block_open ){
								echo '</div>
								</div>';
							}
							echo '<div class="postbox block">
									<h3><span>' . $option['name'] . '</span></h3>
									<div class="inside">';
							$block_open = true;
						}
						elseif ( $option['type'] == 'upload' ) {
							?>
							<div class="upload-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<input id="<?php echo $option['id']; ?>" type="text" size="36" name="<?php echo $option['id']; ?>" value="<?php echo $this->theme_options[ $opt_name ][ $option['id'] ]; ?>" />
									<input id="upload_<?php echo $option['id']; ?>" class="upload-image-button" type="button" value="<?php _e( 'Upload Image', TPL_SLUG ); ?>" />
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'input' ) {
							?>
							<div class="text-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<input id="<?php echo $option['id']; ?>" type="text" size="36" name="<?php echo $option['id']; ?>" value="<?php echo $this->theme_options[ $opt_name ][ $option['id'] ]; ?>" />
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'textarea' ) {
							?>
							<div class="textarea-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<textarea class="large-text" id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>"><?php echo stripslashes(esc_textarea( $this->theme_options[ $opt_name ][ $option['id'] ] )); ?></textarea>
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'select' ) {
							$selected = $this->theme_options[ $opt_name ][ $option['id'] ];
							?>
							<div class="select-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<select id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>">
										<?php 
											foreach( $option['options'] as $html_value => $html_option ) { 
												$selected_attr = '';
												if ( $html_value == $selected ){
													$selected_attr = ' selected="selected"';
												}
												echo '<option value="' . $html_value . '"' . $selected_attr . '>' . $html_option . '</option>';
											}
										?>								
									</select>
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'wp_dropdown_pages' ) {
							$selected = $this->theme_options[ $opt_name ][ $option['id'] ];
							?>
							<div class="select-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<?php wp_dropdown_pages( array( 'selected' => $selected, 'name' => $option['id'] ) ); ?>
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'dropdown_portfolio_categories' ) {
							$selected = $this->theme_options[ $opt_name ][ $option['id'] ];
							?>
							<div class="select-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<select id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>">
										<option value="all"<?php echo ( $selected == 'all' )? ' selected="selected"' : ''; ?>><?php _e( 'All', TPL_SLUG ); ?></option>
										<option value="featured"<?php echo ( $selected == 'featured' )? ' selected="selected"' : ''; ?>><?php _e( 'Featured', TPL_SLUG ); ?></option>
										<?php
											$terms = get_terms('skills', 'hide_empty=1');
											if( count( $terms ) ){
												echo '<optgroup label="' . __( 'Your Categories', TPL_SLUG ) . '">';
												foreach($terms as $term) {
													$selected_attr = '';
													if ( $term->slug == $selected ){
														$selected_attr = ' selected="selected"';
													}
													echo '<option value="' . $term->slug . '"' . $selected_attr . '>' . $term->name . '</option>';
												}
												echo '</optgroup>';
											}
										?>
									</select>
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'dropdown_blog_categories' ) {
							$selected = $this->theme_options[ $opt_name ][ $option['id'] ];
							?>
							<div class="select-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<select id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>">
										<option value="apollo13-all"<?php echo ( $selected == 'all' )? ' selected="selected"' : ''; ?>><?php _e( 'All', TPL_SLUG ); ?></option>
										<option value="apollo13-latest"<?php echo ( $selected == 'apollo13-latest' )? ' selected="selected"' : ''; ?>><?php _e( 'Latest', TPL_SLUG ); ?></option>
										<option value="apollo13-popular"<?php echo ( $selected == 'apollo13-popular' )? ' selected="selected"' : ''; ?>><?php _e( 'Popular', TPL_SLUG ); ?></option>
										<?php
											$terms = get_categories();
											if( count( $terms ) ){
												echo '<optgroup label="' . __( 'Your Categories', TPL_SLUG ) . '">';
												foreach($terms as $term) {
													$selected_attr = '';
													if ( $term->slug == $selected ){
														$selected_attr = ' selected="selected"';
													}
													echo '<option value="' . $term->slug . '"' . $selected_attr . '>' . $term->name . '</option>';
												}
												echo '</optgroup>';
											}
										?>
									</select>
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'cufon' ) {
							$selected = $this->theme_options[ $opt_name ][ $option['id'] ];
							$sample_text = 'Sample text with <strong>some bold words</strong> and numbers 1 2 3 4 5 6 7 8 9 69 ;-)';
							?>
							<div class="cufon-input">
								<p class="desc"><?php echo $option['desc']; ?></p>
								<p><?php echo $option['name']; ?></p>
								<?php 
									foreach( $option['options'] as $font_name => $font ) { 
										$selected_attr = '';
										if ( $font_name == $selected ){
											$selected_attr = ' checked="checked"';
										}
										echo '<div>';
										echo '<label><input type="radio" name="' . $option['id'] . '" value="' . $font_name . '"' . $selected_attr . ' />' . ucwords( str_replace( '_', ' ', $font_name ) ) . '</label>';
										echo '<div class="sample-text" id="sample-for-' . $font_name . '">' . $sample_text . ' </div>';
										$font_path = TPL_FONTS . '/' . $this->all_theme_options['fonts_options']['cufon_fonts'][$font_name];
										echo '<script type="text/javascript" src="' .  $font_path . '"></script>';
										echo '<script type="text/javascript">Cufon.replace(\'#sample-for-' . $font_name . '\', { fontFamily: \'' . ucwords( str_replace( '_', ' ', $font_name ) ) . '\'});</script>';
										echo '</div>';
									}
								?>								
							</div>
							<?php
						}
						elseif ( $option['type'] == 'radio' ) {
							$selected = $this->theme_options[ $opt_name ][ $option['id'] ];
							?>
							<div class="radio-input">
								<span class="label-like"><?php echo $option['name']; ?></span>
								<div class="input-desc">
									<?php 
										foreach( $option['options'] as $html_value => $html_option ) { 
											$selected_attr = '';
											if ( $html_value == $selected ){
												$selected_attr = ' checked="checked"';
											}
											echo '<label><input type="radio" name="' . $option['id'] . '" value="' . $html_value . '"' . $selected_attr . ' />' . $html_option . '</label>';
										}
									?>								
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'color' ) {
							?>
							<div class="color-input">
								<label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
								<div class="input-desc">
									<input id="<?php echo $option['id']; ?>" type="text" class="with-color" name="<?php echo $option['id']; ?>" value="<?php echo $this->theme_options[ $opt_name ][ $option['id'] ]; ?>" />
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
						elseif ( $option['type'] == 'shortcodes' ) {
							echo '<div>'; 
							$this->meta_shortcodes(1);
							echo '</div>'; 
						}
						elseif ( $option['type'] == 'social' ) {
						?>
							<input id="<?php echo $option['id']; ?>" type="hidden" name="<?php echo $option['id']; ?>" value="_array" />
							<p class="desc" style="padding-left: 275px;"><?php echo $option['desc']; ?></p>
						<?php
							$icons_set = $this->get_option( 'social_options', 'social-icons-set' );	
							foreach($option['options'] as $id => $name):
							?>
							<div class="text-input">
								<label for="<?php echo $id; ?>"><img style="vertical-align: -10px; margin-right: 5px;" src="<?php echo TPL_GFX . '/light/' . $icons_set . '/' . $id; ?>.png" /><?php echo $name; ?></label>
								<div class="input-desc">
									<input id="<?php echo $id; ?>" type="text" size="36" name="<?php echo $id; ?>" value="<?php echo $this->theme_options[ $opt_name ][ $option['id'] ][ $id ]; ?>" />
								</div>
							</div>
							<?php
							endforeach;
						}
						elseif ( $option['type'] == 'mover' ) {
							?>
							<div class="mover">
								<span class="label-like"><?php echo $option['name']; ?></span>
								<div class="input-desc">
									<span class="button move-up"><span></span>Move up</span>
									<span class="button move-down"><span></span>Move down</span>
									<input id="<?php echo $option['id']; ?>" type="text" class="position" name="<?php echo $option['id']; ?>" value="<?php echo $this->theme_options[ $opt_name ][ $option['id'] ]; ?>" />
									<p class="desc"><?php echo $option['desc']; ?></p>
								</div>
							</div>
							<?php
						}
					}
					?>
					<?php
					/* Close last options div */
					if ( $fieldset_open ) {
						if( $block_open ){
							echo '</div>
							</div>';
						}
						echo '</div>
							</div>';
					}
					?>
					<div class="save-opts"><input type="submit" name="theme_updated" class="button-primary autowidth" value="<?php _e( 'Save Changes', TPL_SLUG ); ?>" /></div>
			</form>
		<?php
	}
	
	function update_options( $options_name ){
		$copy_to_compare = $this->theme_options[ $options_name ];
		
		foreach( $this->theme_options[ $options_name ] as $option => $value ){
			if ( isset( $_POST[ $option ] )) {
				
				//if array
				if( $_POST[ $option ] == '_array'){
					$collector = array();
					foreach( $this->all_theme_options[ $options_name ][ $option ] as $id => $val ){
						if ( isset( $_POST[ $id ] )) {
							$collector[$id] = $_POST[ $id ];  
						}
					}
					//save
					$this->theme_options[ $options_name ][ $option ] = $collector;
				}
				
				//if single option
				else
				$this->theme_options[ $options_name ][ $option ] = $_POST[ $option ];
			}
		}
		if ( $this->theme_options[ $options_name ] != $copy_to_compare ) {
			update_option(TPL_SLUG . '_' . $options_name, $this->theme_options[ $options_name ] );
			$this->generate_user_css();
			define('AIRLOCK_SETTINGS_CHANGED', true);
		}
	}
	
	function generate_user_css(){
		if ( is_writable( USER_GENERATED_DIR ) ) {
			$file = USER_GENERATED_DIR . '/user.css';
			$fh = @fopen( $file, 'w+' );
			$css = include( TPL_ADV_DIR . '/user-css.php' );
			if ( $fh ) {
				fwrite( $fh, $css, strlen( $css ) );
			}
		}
	}
	
	function admin_head(){
		/*** UPLOAD ***/
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_register_script('cufon', TPL_JS . '/cufon-yui.js', array('jquery'), '1.09i' );
		wp_enqueue_script('cufon');
		/* Since Airlock 1.5 WP 3.3 */
		global $wp_version;
		if( version_compare($wp_version,"3.3",">=") ){
			wp_enqueue_script('plupload-all');
		}
		/*** color picker ***/
		wp_register_script('jquery-wheelcolorpicker', TPL_JS . '/jquery.wheelcolorpicker/jquery.wheelcolorpicker.js', array('jquery'), '1.2.2' );
		
		wp_register_script('apollo13-admin', TPL_JS . '/admin-script.js', array('jquery','media-upload','thickbox','jquery-wheelcolorpicker'));
		$admin_params = array( 
			'colorDir' => TPL_JS . '/jquery.wheelcolorpicker' 
		);
		wp_enqueue_script('apollo13-admin');
		wp_localize_script( 'apollo13-admin', 'AdminParams', $admin_params );
		
		wp_register_script('apollo13-shortcodes', TPL_JS . '/admin-shortcodes.js', array('jquery','apollo13-admin'));
		wp_enqueue_script('apollo13-shortcodes');
		
		wp_enqueue_style( 'admin-css', TPL_CSS . '/admin-css.css', false, false, 'all' );
		
	}
	
	function theme_scripts(){
		if(is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])){
				return;
		}
		/* We add some JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-apollo13', TPL_JS . '/jquery-ui.min.js', array( 'jquery' ), '1.8.12' );
		
		$apollo_params = array(
			'cufon' => 'off',
			'cufon_name' => '' 
		);
		
		// Cufon
		if( $this->theme_options['fonts_options']['cufon_switch'] == 'enable' ) {
			$font_name = $this->theme_options['fonts_options']['cufon_fonts'];
			$font_path = TPL_FONTS . '/' . $this->all_theme_options['fonts_options']['cufon_fonts'][$font_name];
			$apollo_params['cufon_name'] = $font_name_norm = ucwords( str_replace( '_', ' ', $font_name ) );
			$apollo_params['cufon'] = 'on';
			
			wp_enqueue_script('cufon', TPL_JS . '/cufon-yui.js', array('jquery'), '1.09i' );
			wp_enqueue_script('cufon_font', $font_path, array('jquery', 'cufon'), '1.09i' );
		}
	
		// MAIN SCRIPTS 
		wp_enqueue_script('apollo13-scripts-setup', TPL_JS . '/scripts.js', array( 'jquery' ) );
		wp_localize_script( 'apollo13-scripts-setup', 'ApolloParams', $apollo_params );
		wp_enqueue_script('jquery-hover-intent', TPL_JS . '/jquery.hoverIntent.js', array( 'jquery' ) );
		wp_enqueue_script('jquery-scroll-to', TPL_JS . '/jquery.ScrollTo.js', array( 'jquery' ) );
		wp_enqueue_script('jquery-cookie', TPL_JS . '/jquery.cookie.js', array( 'jquery' ) );
		wp_enqueue_script('jquery-tweet', TPL_JS . '/jquery.tweet.js', array( 'jquery' ) );
		wp_enqueue_script('stylesheet-toggle', TPL_JS . '/stylesheetToggle.js', array( 'jquery' ) );
		wp_enqueue_script('jquery-ba-resize', TPL_JS . '/jquery.ba-resize.min.js', array( 'jquery' ), '1.1' );

		// FancyBox
		wp_enqueue_script('jquery-mousewheel', TPL_JS . '/jquery.mousewheel-3.0.4.pack.js', array( 'jquery' ), '3.0.4' );
		wp_enqueue_script('jquery-fancybox', TPL_JS . '/jquery.fancybox-1.3.4.js', array( 'jquery', 'jquery-mousewheel' ), '1.3.4' );
		wp_enqueue_script('jquery-fancybox-settings', TPL_JS . '/jquery.fancybox.settings.js', array( 'jquery-fancybox' ), '1.3.4' );
		
		//Masonary
		wp_enqueue_script('jquery-masonry', TPL_JS . '/jquery.masonry.min.js', array( 'jquery' ), '2.0.11' );
		wp_enqueue_script('modernizr-transitions', TPL_JS . '/modernizr-transitions.js', array( 'jquery' ), '1.7' );
		wp_enqueue_script('jquery-imagesloaded', TPL_JS . '/jquery.imagesloaded.js', array( 'jquery' ), '1.0.4' );
		wp_enqueue_script('box-maker', TPL_JS . '/box-maker.js', array( 'jquery' ), '2.0.11' );
		wp_enqueue_script('grayscale', TPL_JS . '/grayscale.js', array( 'jquery' ) );
		
		// ANYTHING SLIDER
		wp_enqueue_script('jquery.anythingslider', TPL_JS . '/jquery.anythingslider.min.js', array( 'jquery' ), '1.7.16' );
		wp_enqueue_script('jquery.anythingslider.video', TPL_JS . '/jquery.anythingslider.video.min.js', array( 'jquery' ), '1.7.16' );
		
		//EXT JS
       // wp_enqueue_script('ext', TPL_JS . '/ext.js', array( 'jquery' ) );
        
        //EXT JS
        wp_enqueue_script('jquery-ias', TPL_PLUGINS . '/infinite-ajax-scroll/jquery-ias.js', array( 'jquery' ) );
	}

	function theme_styles(){
		global $wp_styles;
		if(is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])){
				return;
		}
		
		$main_css = TPL_CSS . '/' . $this->theme_options['settings']['theme_styles'] . '.css';
		$user_css = USER_GENERATED . '/user.css';
		
		// RESET
		wp_enqueue_style('css-reset', TPL_CSS . '/reset-min.css');
		
		// FANCYBOX
		wp_enqueue_style('fancybox', TPL_CSS . '/jquery.fancybox-1.3.4.css');

		// ANYTHING SLIDER
		wp_enqueue_style('anythingslider', TPL_CSS . '/anythingslider.css');
		wp_enqueue_style('anythingslider-theme', TPL_CSS . '/theme-minimalist-round.css');
		
		// MAIN STYLES
		wp_enqueue_style('main-css', $main_css);
		
		//EXT STYLES
        wp_enqueue_style('ext', TPL_CSS . '/ext.css');
        wp_enqueue_style('jquery.ias', TPL_PLUGINS . '/infinite-ajax-scroll/css/jquery.ias.css');
		
		if( $this->theme_options['settings']['theme_switcher'] == 'on' ){
			$alt = 'light';
			$norm = 'dark';
			if($this->theme_options['settings']['theme_styles'] == 'style-light' ){
				$alt = 'dark';
				$norm = 'light';
			}
			wp_enqueue_style('switcher-css', $main_css);
			$wp_styles->add_data('switcher-css','title', 'switch');
			
			$wp_styles->add_data('main-css','title', $norm);
			$wp_styles->add_data('main-css','alt', true);
			// ALTTERANTE STYLE
			wp_enqueue_style('alt-css', TPL_CSS . '/style-' . $alt . '.css');
			$wp_styles->add_data('alt-css','alt', true);
			$wp_styles->add_data('alt-css','title', $alt);
		}
		
		wp_enqueue_style('user-css', $user_css);
		wp_enqueue_style('ie7', TPL_CSS . '/ie7.css');
		$wp_styles->add_data( 'ie7', 'conditional', 'lte IE 7');
		wp_enqueue_style('ie8', TPL_CSS . '/ie8.css');
		$wp_styles->add_data( 'ie8', 'conditional', 'IE 8');
	}
	
	
	function theme_head(){
		if(is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])){
			return;
		}
		//if there is somthing that can't be added in theme_styles or theme_scripts, add it here
	}
	
	function portfolio_register(){
	 	
		$labels = array(
			'name' => _x('My Portfolio', 'post type general name'),
			'singular_name' => _x('Portfolio', 'post type singular name'),
			'add_new' => _x('Add New', 'portfolio item'),
			'add_new_item' => __('Add New Portfolio Item'),
			'edit_item' => __('Edit Portfolio Item'),
			'new_item' => __('New Portfolio Item'),
			'view_item' => __('View Portfolio Item'),
			'search_items' => __('Search Portfolio'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);
	 
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => '',
			'menu_position' => 5,
			'rewrite' =>  array('slug' => PORTFOLIO_POST_SLUG),
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor' )
		  ); 
	 
		register_post_type( PORTFOLIO_POST_TYPE , $args );
//		flush_rewrite_rules();
	
		register_taxonomy("skills", array(PORTFOLIO_POST_TYPE), array("hierarchical" => true, "label" => "Portfolio Categories", "singular_label" => "Portfolio Category", "rewrite" => true));
		
		//flush if settings has changed
		if ( defined( 'AIRLOCK_SETTINGS_CHANGED' ) && AIRLOCK_SETTINGS_CHANGED ) {
			global $wp_rewrite;
			//Call flush_rules() as a method of the $wp_rewrite object
			$wp_rewrite->flush_rules(); // to refresh new slugs if set
		}
		
	}
	
	function portfolio_edit_columns($columns){
		$columns = array(
			"cb"          => "<input type=\"checkbox\" />",
			"title"       => "Portfolio Title",
			"description" => "Description",
			"skills"      => "Categories",
		);
	 
		return $columns;
	}
	
	function portfolio_custom_columns($column){
		global $post;
	 
		switch ($column) {
			case "description":
				the_excerpt();
				break;
			case "skills":
				echo get_the_term_list($post->ID, 'skills', '', ', ','');
				break;
		}
	}
	
	function portfolio_multi_upload(){
				
	  check_ajax_referer('photo-upload');
	
	  // you can use WP's wp_handle_upload() function:
	  $file = $_FILES['async-upload'];
	  $status = wp_handle_upload($file, array('test_form'=>false));
	
	  // and output the results or something...
		echo $status['url'];
	  exit;
	}
	
	function posted_on( $date_format = '' ) {
		printf( __('<a href="%1$s" rel="bookmark" class="date">%2$s</a>', TPL_SLUG ),
			get_permalink(),
			( strlen( $date_format ) ? get_the_date( $date_format ) : get_the_date() )
		);
	}
	
	function posted_in( $separator = '<span>/</span>' ) {
		// Retrieves tag list of current post, separated by commas.
		if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
			$posted_in = __( '%1$s', TPL_SLUG );
		} else {
			$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', TPL_SLUG );
		}
		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list( $separator ),
			'',//$tag_list,
			get_permalink(),
			the_title_attribute( 'echo=0' )
		);
	}
	
	function portfolio_posted_in( $term_list, $separator = '<span>/</span>' ) {
		$count_terms = count( $term_list );
		$iter = 1;
		if( $count_terms ){
			foreach($term_list as $term) {
				echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
				if( $count_terms != $iter ){
					echo $separator;
				}
				$iter++;
			}
		}
	}
	
	function search_form( $form ) {
		static $search_id = 1;
		$field_search = '';
		$helper_search = get_search_query() == '' ? true : false; 
		$field_search = '<input ' .
						' class="placeholder" title="' . __( 'Type and enter ...', TPL_SLUG ) . '" ' .
						'type="text" name="s" id="s' . $search_id . '" value="' . 
						( $helper_search ? __( 'Type and enter ...', TPL_SLUG ) : get_search_query() ) . 
						'" />';
	
		$form = '
			<form class="search-form" role="search" method="get" id="searchform' . $search_id . '" action="' . home_url( '/' ) . '" >
				<fieldset class="semantic">
					<div class="search-left"><label for="s' . $search_id . '"></label></div>
					<div class="search-right">
						<div>
							<span class="close"></span>
							' . $field_search . '
							<input type="submit" id="searchsubmit' . $search_id . '" value="'. __('Search', TPL_SLUG ) .'" />
						</div>
					</div>
				</fieldset>
			</form>';
		$search_id++;
		return $form;
	}
	
	function shortcodes() {
		require_once (TPL_SHORTCODES_DIR . '/fix.php');
		require_once (TPL_SHORTCODES_DIR . '/typography.php');
		require_once (TPL_SHORTCODES_DIR . '/columns.php');
		require_once (TPL_SHORTCODES_DIR . '/toggles.php');
		require_once (TPL_SHORTCODES_DIR . '/gallery.php');
		require_once (TPL_SHORTCODES_DIR . '/video.php');
	}
	
	function remove_image_box() {
		remove_meta_box( 'postimagediv','post','side' );
		remove_meta_box( 'postimagediv','page','side' );
		remove_meta_box( 'postimagediv',PORTFOLIO_POST_TYPE,'side' );
	}
	
	function admin_meta_boxes(){
		add_meta_box(
			'apollo13_shortocodes',
			__( TPL_NAME . ' Shortocdes', TPL_SLUG ),
			array( &$this , 'meta_shortcodes' ),
			'post',
			'normal',
			'high'
		);
		add_meta_box( 
			'apollo13_theme_options',
			__( 'Blog post Detail -  Add Image and videos', TPL_SLUG ),
			array( &$this , 'meta_main_opts' ),
			'post',
			'normal',
			'high', 
			array('func' => 'apollo13_metaboxes_post')//callback 
		);
		add_meta_box( 
			'apollo13_theme_options',
			__( 'Page Detail', TPL_SLUG ),
			array( &$this , 'meta_main_opts' ),
			'page',
			'normal',
			'low', 
			array('func' => 'apollo13_metaboxes_page')//callback 
		);
		add_meta_box( 
			'apollo13_theme_options',
			__( 'Portfolio details', TPL_SLUG ),
			array( &$this , 'meta_main_opts' ),
			PORTFOLIO_POST_TYPE,
			'normal',
			'low', 
			array('func' => 'apollo13_metaboxes_portfolio')//callback 
		);
		add_meta_box( 
			'apollo13_theme_options_1',
			__( 'Portfolio details - Add Image and videos', TPL_SLUG ),
			array( &$this , 'meta_main_opts' ),
			PORTFOLIO_POST_TYPE,
			'normal',
			'low', 
			array('func' => 'apollo13_metaboxes_portfolio_images')//callback 
		);
		add_meta_box( 
			'apollo13_shortocodes',
			__( TPL_NAME . ' Shortocdes', TPL_SLUG ),
			array( &$this , 'meta_shortcodes' ),
			'page',
			'normal',
			'high' 
		);
		add_meta_box( 
			'apollo13_shortocodes',
			__( TPL_NAME . ' Shortocdes', TPL_SLUG ),
			array( &$this , 'meta_shortcodes' ),
			PORTFOLIO_POST_TYPE,
			'normal',
			'high' 
		);
	}
	
	function meta_shortcodes( $post ){
		require_once (TPL_SHORTCODES_DIR . '/shortcodes-generate.php');
		$raw = apollo13_shortcodes();
			
		$categories = '<select id="shortcode-categories" name="shortcode-categories"><option value="">' . __( 'Select category', TPL_SLUG ) . '</option>';
		$inHtml = '';
			
		foreach($raw as $cats){
			$categories .= '<option value="' . $cats['id'] . '">' . $cats['name'] . '</option>';
			$bufor = '';
			$subcategories = '';
			//generate subcats and fields
			foreach($cats['codes'] as $code){
				$subcategories .= '<option value="' . $code['code'] . '">' . $code['name'] . '</option>';
				
				$fields = '<div id="apollo13-' . $code['code'] . '-fields" class="shortcodes-fields apollo13-settings">';
				foreach($code['fields'] as $field){
					$fields .= apollo13_shortcodes_make_field( $field, $code['code'] );
				}
				$fields .= '</div>';
				$bufor .= $fields;
			}
			if ( $subcategories != '' ){
				$subid = 'apollo13-' . $cats['id'] . '-codes'; 
				$subcategories = '<select id="' . $subid . '" name="' . $subid . '" class="shortcodes-codes">' . $subcategories . '</select>';
			}
			$inHtml .= $subcategories;
			$inHtml .= $bufor;
		}

		$categories .= '</select>';
		
		$html = '<div id="shortcode-generator">';
		$html .= $categories;	
		$html .= $inHtml;
		$html .= '<span class="button" id="send-to-editor">Insert code in editor</span>';
		$html .= '</div>';
		echo $html;
	}
	
	function meta_main_opts( $post, $metabox ){
		// Use nonce for verification
		wp_nonce_field( 'apollo13_customization' , 'apollo13_noncename' );
		
		require_once (TPL_ADV_DIR . '/meta.php');
		$metaboxes = $metabox['args']['func']();
		
		$fieldset_open = false;
		$switch_mode = false;
		$additive_mode = false;
		$switch_value = '';
		
		echo '<div class="apollo13-settings apollo13-metas">';
			
		foreach( $metaboxes as &$meta ){
			//modes and modificators
			$value = '';
			$style = '';
			if ( isset( $meta['id'] ) ){
				$value = get_post_meta($post->ID, '_' . $meta['id'] , true);
				if( empty($value) ){
					$value = ( isset( $meta['default'] )? $meta['default'] : '' );
				}
				
				//switch mode
				if( $switch_mode ){
				    if(strlen($switch_value)){
					    //search for group of fields (id^=$temp_switch_value) not just one field(id=$switch_value)
				        $temp_switch_value = str_replace(
	                                            substr($switch_value, strrpos($switch_value, '_')),
	                                            '',
	                                            $switch_value
	                                        );
					    //if there is no $temp_switch_value in $meta['id']                    
	                    if( strpos($meta['id'],$temp_switch_value) === false ){
	                        $style = ' style="display: none;"';
	                    }
				    }
				    else{
				        //if nothing is selected in switch then also hide input fields
				        $style = ' style="display: none;"';
				    }
				}
			}
			
			
			
			//print tag according to type
			if ( $meta['type'] == 'fieldset' ) {
				if ( $fieldset_open ) {
					echo '</div>';
				}
				
				$title = '';
				$class = ' static';
				if( isset( $meta['additive'] ) && $meta['additive'] == true ){
					$class = ' additive';
					$title = ' title="' . $meta['title'] . '"';//number of element
					$additive_mode = true;
					//only one copy of counter input
					if( $meta['title'] == 1 ){
						echo '<input id="' . $meta['id'] . '" name="' . $meta['id'] . '" type="hidden" class="counter-input" value="' . $value . '" />';
						//we change table here 
						$this->additive_array_trick($metaboxes, $value);
					}
				}
				echo '<div class="fieldset' . $class . '"' . $title . '>';
				$fieldset_open = true;
			}
			elseif ( $meta['type'] == 'upload' ) {
				?>
				<div class="upload-input input-parent"<?php echo $style; ?>>
					<label for="<?php echo $meta['id']; ?>"><?php echo $meta['name']; ?>&nbsp;</label>
					<div class="input-desc">
						<input id="<?php echo $meta['id']; ?>" type="text" size="36" name="<?php echo $meta['id']; ?>" value="<?php echo esc_attr($value); ?>" />
						<input id="upload_<?php echo $meta['id']; ?>" class="upload-image-button" type="button" value="<?php _e( 'Upload', TPL_SLUG ); ?>" />
						<p class="desc"><?php echo $meta['desc']; ?></p>
					</div>
				</div>
				<?php
			}
			elseif ( $meta['type'] == 'input' ) {
			     $inp_class = (isset($meta['class'])? ' ' . $meta['class'] : '' ); 
				?>
				<div class="text-input input-parent<?php echo $inp_class; ?>"<?php echo $style; ?>>
					<label for="<?php echo $meta['id']; ?>"><?php echo $meta['name']; ?>&nbsp;</label>
					<div class="input-desc">
						<input id="<?php echo $meta['id']; ?>" type="text" size="36" name="<?php echo $meta['id']; ?>" value="<?php echo esc_attr($value); ?>" />
						<p class="desc"><?php echo $meta['desc']; ?></p>
					</div>
				</div>
				<?php
			}
			elseif ( $meta['type'] == 'select' ) {
				$selected = $value;
				?>
				<div class="select-input input-parent"<?php echo $style; ?>>
					<label for="<?php echo $meta['id']; ?>"><?php echo $meta['name']; ?></label>
					<div class="input-desc">
						<select id="<?php echo $meta['id']; ?>" name="<?php echo $meta['id']; ?>">
							<?php 
								foreach( $meta['options'] as $html_value => $html_option ) { 
									$selected_attr = '';
									if ( $html_value == $selected ){
										$selected_attr = ' selected="selected"';
									}
									echo '<option value="' . esc_attr($html_value) . '"' . $selected_attr . '>' . $html_option . '</option>';
								}
							?>								
						</select>
						<p class="desc"><?php echo $meta['desc']; ?></p>
					</div>
				</div>
				<?php
			}
			elseif ( $meta['type'] == 'textarea' ) {
				?>
				<div class="textarea-input input-parent"<?php echo $style; ?>>
					<label for="<?php echo $meta['id']; ?>"><?php echo $meta['name']; ?>&nbsp;</label>
					<div class="input-desc">
						<textarea class="large-text" id="<?php echo $meta['id']; ?>" name="<?php echo $meta['id']; ?>"><?php echo stripslashes(esc_textarea( $value )); ?></textarea>
						<p class="desc"><?php echo $meta['desc']; ?></p>
					</div>
				</div>
				<?php
			}
			elseif ( $meta['type'] == 'switch' ) {
				echo '<div id="' . $meta['id'] . '" class="switch">';
				$selected = $value;
				$switch_mode = true;
				$switch_value = $value;
				?>
				<div class="radio-input">
					<span class="label-like"><?php echo $meta['name']; ?></span>
					<div class="input-desc">
						<?php
							foreach( $meta['options'] as $html_value => $html_option ) { 
								$selected_attr = '';
								if ( $html_value == $selected ){
									$selected_attr = ' checked="checked"';
								}
								echo '<label><input type="radio" name="' . $meta['id'] . '" value="' . $html_value . '"' . $selected_attr . ' />' . $html_option . '</label>';
							}
						?>								
						<p class="desc"><?php echo $meta['desc']; ?></p>
					</div>
				</div>
				<?php
			}
			elseif ( $meta['type'] == 'end-switch' ) {
				$switch_mode = false;
				echo '</div>';
			}
			elseif ( $meta['type'] == 'mover' ) {
				?>
				<div class="mover">
					<span class="label-like"><?php echo $meta['name']; ?></span>
					<div class="input-desc">
						<span class="button move-up"><span></span>Move up</span>
						<span class="button move-down"><span></span>Move down</span>
						<input id="<?php echo $meta['id']; ?>" type="text" class="position" name="<?php echo $meta['id']; ?>" value="<?php echo $value; ?>" />
						<p class="desc"><?php echo $meta['desc']; ?></p>
					</div>
				</div>
				<?php
			}
			elseif ( $meta['type'] == 'adder' ) {
				if ( $fieldset_open ) {
					echo '</div>';
					$fieldset_open = false;
					$additive_mode = false;
				}
				echo '<div class="add-more-parent"><span class="button add-more-fields"><span>+</span>' . $meta['name'] . '</span></div>';
			}
			elseif ( $meta['type'] == 'multi-upload' ) {
			global $wp_version;
			if( version_compare($wp_version,"3.3",">=") ){
				$wp_version
				// so here's the actual uploader
				// most of the code comes from media.php and handlers.js
				?>
					<h4 style="text-align: center;">Multi upload area</h4>
				   <div id="plupload-upload-ui" class="hide-if-no-js">
				     <div id="drag-drop-area">
				       <div class="drag-drop-inside">
				       	<div class="loading">
				       		<p class="drag-drop-info"><?php _e('Uploading files...'); ?></p>
				       	</div>
				       	<div class="not-loading">
					        <p class="drag-drop-info"><?php _e('Drop files here'); ?></p>
					        <p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
					        <p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" /></p>
				        </div>
				      </div>
				     </div>
				  </div>
				
				  <?php
				
				  $plupload_init = array(
				    'runtimes'            => 'html5,silverlight,flash,html4',
				    'browse_button'       => 'plupload-browse-button',
				    'container'           => 'plupload-upload-ui',
				    'drop_element'        => 'drag-drop-area',
				    'file_data_name'      => 'async-upload',            
				    'multiple_queues'     => true,
				    'max_file_size'       => wp_max_upload_size().'b',
				    'url'                 => admin_url('admin-ajax.php'),
				    'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
				    'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
				    'filters'             => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
				    'multipart'           => true,
				    'urlstream_upload'    => true,
				
				    // additional post data to send to our ajax hook
				    'multipart_params'    => array(
				      '_ajax_nonce' => wp_create_nonce('photo-upload'),
				      'action'      => 'portfolio_multi_upload',            // the ajax action name
				    ),
				  );
				
				  // we should probably not apply this filter, plugins may expect wp's media uploader...
				//  $plupload_init = apply_filters('plupload_init', $plupload_init); ?>
				
				  <script type="text/javascript">
				
				    jQuery(document).ready(function($){
				
				      // create the uploader and pass the config from above
				      var apollo_uploader = new plupload.Uploader(<?php echo json_encode($plupload_init); ?>);
				
				      // checks if browser supports drag and drop upload, makes some css adjustments if necessary
				      apollo_uploader.bind('Init', function(up){
				        var uploaddiv = $('#plupload-upload-ui');
				
				        if(up.features.dragdrop){
				          uploaddiv.addClass('drag-drop');
				            $('#drag-drop-area')
				              .bind('dragover.wp-uploader', function(){ uploaddiv.addClass('drag-over'); })
				              .bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv.removeClass('drag-over'); });
				
				        }else{
				          uploaddiv.removeClass('drag-drop');
				          $('#drag-drop-area').unbind('.wp-uploader');
				        }
				      });
				
				      apollo_uploader.init();
				
				      // a file was added in the queue
				      apollo_uploader.bind('FilesAdded', function(up, files){
				        var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
				
				        plupload.each(files, function(file){
				          if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5'){
				            // file size error?
				
				          }else{
				
				            // a file was added, you may want to update your DOM here...
				//            console.log('FilesAdded',file);
				          }
				        });
				
				        up.refresh();
				        up.start();
				      });
				
				    // a file was uploaded 
					apollo_uploader.bind('FileUploaded', function(up, file, response) {
						added_set = add_more_fields_meta( $('.apollo13-metas .add-more-fields') );
						number = added_set.attr('title');
						$('input[name="image_or_video_' + number + '"][value="post_image_' + number + '"]').val(['post_image_' + number]).change();
						$('#post_image_' + number).val(response['response']);
				    });
				    
					apollo_uploader.bind('FilesAdded', function(up, files) {
						$('#drag-drop-area').addClass('loading');
				    });   
					apollo_uploader.bind('UploadComplete', function(up, files) {
						$('#drag-drop-area').removeClass('loading');
				    });   
			    });   
				
				</script>
				<?php
			}
			}
			
		}
		unset($meta);
		
		//close fieldset
		if ( $fieldset_open ) {
			echo '</div>';
		}

		echo '</div>';//.apollo13-settings .apollo13-metas
		
	}
	
	function save_post($post_id){
		static $done = 0;
		$done++;
		if( $done > 1 ){
			return;//no double saving same things
		}
		
		// verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;
		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		
		if( ! isset( $_POST['apollo13_noncename'] ) )
			return;
		
		if ( !wp_verify_nonce( $_POST['apollo13_noncename'], 'apollo13_customization' ) )
			return;
		
		require_once (TPL_ADV_DIR . '/meta.php');
		$metaboxes = array();
		switch( $_POST['post_type'] ){
			case 'post':
				$metaboxes = apollo13_metaboxes_post();
				break;
			case 'page':
				$metaboxes = apollo13_metaboxes_page();
				break;
			case PORTFOLIO_POST_TYPE:
				$metaboxes = array_merge( apollo13_metaboxes_portfolio(), apollo13_metaboxes_portfolio_images() );
				break;
		}
		
		//saving meta
		foreach( $metaboxes as &$meta ){
			if( $meta['type'] == 'fieldset' && isset( $meta['additive'] ) && $meta['additive'] == true ){
				if( $meta['title'] == 1 ){
					//we change table here
					$this->additive_array_trick($metaboxes, $_POST[ $meta['id'] ]);
				}
			}
			if( isset( $meta['id'] ) && isset( $_POST[ $meta['id'] ] ) && $meta['type'] != 'adder' ){
				update_post_meta( $post_id, '_' . $meta['id'] , $_POST[ $meta['id'] ] );
			}
		}
	}
	
	function additive_array_trick(&$array, $iterations = 1){
		//rewind one element to save current position, so we can return to it after operations
		prev($array);
		$current = key( $array );
		$array_part_copy = array();
		//get all fields from additive fieldset
		do{
			$array_part_copy[ key( $array ) ] = current( $array );
		}
		while( ($next_elem = next( $array )) && $next_elem['type'] != 'adder' );
		$length_to_cut_off = count($array_part_copy);
		
		//make new array part
		$new_array_part = array();
		for( $iter = 1; $iter <= $iterations; $iter++ ){
			foreach( $array_part_copy as $meta ){
				if( $meta['type'] == 'fieldset'){
					$meta['title'] = $iter;
				}
				else{
					if( isset( $meta['id'] ) ){
						$meta['id'] .= '_' . $iter;
					}
					if( isset( $meta['options'] ) ){
						$new_arr = array();
						foreach( $meta['options'] as $html_value => $html_option ) {
							$new_arr[ $html_value . '_' . $iter ] = $html_option;
						}
						$meta['options'] = $new_arr;
					}
				}
				$new_array_part[] = $meta;
			}
		}
		//combine tables
		array_splice( $array, $current, $length_to_cut_off, $new_array_part );
		//rewind array to proper place
		reset( $array );
		while ( key( $array ) !== $current ) next( $array );
		//point to next 
		next( $array );
	}
	
	function contact_form( $email_to = '', $show_in_widget = false ){
		$name_error     = false;
		$email_error    = false;
		$content_error  = false;
		$subject_error  = false;
		$title_msg      = '';
		$name_tag       = 'value=""';
		$email_tag      = 'value=""';
		$subject_tag    = 'value="' . __( 'General question ...', TPL_SLUG ) . '" title="' . __( 'General question ...', TPL_SLUG ) . '"';
		$content_tag    = '';
		
		if( isset( $_POST['apollo13-contact-form'] ) ){
			$site = get_bloginfo('name');
			
			if( empty( $email_to ) || ! is_email( $email_to ) ){
				$email_to = get_option('admin_email'); 
			}
			
			$name    = isset($_POST['apollo13-contact-name'])?trim($_POST['apollo13-contact-name']):'';
			$email   = isset($_POST['apollo13-contact-email'])?trim($_POST['apollo13-contact-email']):'';
			$subject = isset($_POST['apollo13-contact-subject'])?trim($_POST['apollo13-contact-subject']):'';
			$content = isset($_POST['apollo13-contact-content'])?trim($_POST['apollo13-contact-content']):'';
	
			if( empty( $name ) )
				$name_error = true;
			if( empty( $email ) || ! is_email( $email ) )
				$email_error = true;
			if( empty( $subject ) )
				$subject_error = true;
			if( empty( $content ) )
				$content_error = true;
				
				
			if( $name_error == false && $email_error == false && $content_error == false && $subject_error == false ){
				$subject = $site . __( ' - message from contact form', TPL_SLUG );
				$body = __( 'Site: ', TPL_SLUG ) . $site . "\n\n"
					  . __( 'Name: ', TPL_SLUG ) . $name . "\n\n"
					  . __( 'Email: ', TPL_SLUG ) . $email . "\n\n"
					  . __( 'Subject: ', TPL_SLUG ) . $subject . "\n\n"
					  . __( 'Message: ', TPL_SLUG ) . $content;
				$headers = "From: $name <$email>\r\n";
				$headers .= "Reply-To: $email\r\n";
				
				
				if( wp_mail( $email_to, $subject, $body, $headers ) ){
					$title_msg = __( 'Success sending form', TPL_SLUG );
				}
				else
					$title_msg = __( 'Something wrong. Try again!', TPL_SLUG );
			}
			else{
				$title_msg = __( 'Error in form', TPL_SLUG );
				if( ! empty( $name ) )
					$name_tag = 'value="' . $name . '"';
				if( ! empty( $email ) )
					$email_tag = 'value="' . $email . '"';
				if( ! empty( $subject ) )
					$phone_tag = 'value="' . $subject . '" title="' . __( 'General question ...', TPL_SLUG ) . '"';
				if( ! empty( $content ) )
					$content_tag = $content;
			}
		}
		
		static $form_iter = 0;
		$form_iter++;
		
		$ssss = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		
		$html = '
					<form action="http' . $ssss . '://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . '" method="post" id="contact-form-' . $form_iter . '" class="contact-form styled-form">';
		if( ! $show_in_widget )
			$html .= (empty( $title_msg ) ? '' : '<h4 class="mm">' . $title_msg . '</h4>' );
		$html .= '		<ul>
							<li' . ($name_error ? ' class="error"' : '') . '>
								<label for="apollo13-contact-name">' . __( 'Name <span>(required)</span>', TPL_SLUG ) . '</label>
								<input id="apollo13-contact-name" name="apollo13-contact-name" type="text" ' . $name_tag . ' />
							</li>
							<li' . ($email_error ? ' class="error"' : '') . '>
								<label for="apollo13-contact-email">' . __( 'Email <span>(required)</span>', TPL_SLUG ) . '</label>
								<input id="apollo13-contact-email" name="apollo13-contact-email" type="text" ' . $email_tag . ' class="email" />
							</li>
							<li' . ($subject_error ? ' class="error"' : '') . '>
								<label for="apollo13-contact-subject">' . __( 'Subject <span>(required)</span>', TPL_SLUG ) . '</label>
								<input class="placeholder" id="apollo13-contact-subject" name="apollo13-contact-subject" type="text" ' . $subject_tag . ' />
							</li>
							<li' . ($content_error ? ' class="error"' : '') . '>
								<label for="apollo13-contact-content">' . __( 'Message <span>(required)</span>', TPL_SLUG ) . '</label>
								<textarea id="apollo13-contact-content" name="apollo13-contact-content" rows="10" cols="40">' . $content_tag . '</textarea>
							</li>
							<li>
								<input type="hidden" name="apollo13-contact-form" value="send" />
								<input type="submit" value="' . __( 'Submit form', TPL_SLUG ) . '" />
							</li>
						</ul>
					</form>';
		
		return $html;
	}

	//Sets the post excerpt length to 40 words.
	function excerpt_length( $length ) {
		return 40;
	}
	
	function new_excerpt_more($more) {
		return ' (...)';
	}

	// Enable comments on portfolio
	function portfolio_comments_open( $open, $post_id ) {
	
		$post = get_post( $post_id );
	
		if ( PORTFOLIO_POST_TYPE == $post->post_type )
			$open = true;
	
		return $open;
	}
	
	function get_comment_excerpt($comment_ID = 0, $num_words = 20) {
		$comment = get_comment( $comment_ID );
		$comment_text = strip_tags($comment->comment_content);
		$blah = explode(' ', $comment_text);
		if (count($blah) > $num_words) {
			$k = $num_words;
			$use_dotdotdot = 1;
		} else {
			$k = count($blah);
			$use_dotdotdot = 0;
		}
		$excerpt = '';
		for ($i=0; $i<$k; $i++) {
			$excerpt .= $blah[$i] . ' ';
		}
		$excerpt .= ($use_dotdotdot) ? '[...]' : '';
		return apply_filters('get_comment_excerpt', $excerpt);
	}
	
	function portfolio_post_limits( $limits ) {
		global $wp_query;
//		print_r($wp_query->query_vars);
		if( isset( $wp_query->query_vars['skills'] )) {
			$port_limit = $this->theme_options[ 'portfolio_options' ][ 'items_per_page'];
			$paged = get_query_var('paged');
			if($paged != 0)
				$paged--;
			if( $port_limit <= 0) //fix for crushing categories when limit set to <= 0
				return;
			$limits = 'LIMIT ' . ($paged * $port_limit) . ', ' . $port_limit;
//			echo "limits are $limits" . ' qqq' . get_query_var('paged');
		}	
		return $limits;
	}

	function comment_end( $comment, $args, $depth ) {
		echo '</div>';
		return;
	}
	
	function comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		static $level = 0;
		switch ( $comment->comment_type ) :
			case '' :
		?>
				<div <?php comment_class( 'comment-block' ); ?> id="comment-<?php comment_ID(); ?>">
					
					<div class="comment-inside">
						<a class="avatar" href="<?php comment_author_url(); ?>" title=""><?php echo get_avatar( $comment, 60 ) ; ?></a>
						<div class="comment-info">
							<span class="date"><?php
								printf( '<a href="%1$s">%2$s</a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									/* translators: 1: date, 2: time */
									sprintf( __( '%1$s at %2$s', TPL_SLUG ), get_comment_date(), get_comment_time() )
								);
							?></span>
							<strong class="author"><?php comment_author_link(); ?></strong>
							<span class="sep">/</span>
						<?php
							comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); 
						?>
						</div>
						<div class="comment-text">
							<?php if ( $comment->comment_approved == '0' ) : ?>
								<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', TPL_SLUG ); ?></em>
								<br />
							<?php endif; ?>
							<?php comment_text(); ?>
						</div>
						<div class="clear"></div>
						<span class="arrow"></span>
					</div>
		<?php
				break;
			case 'pingback'  :
			case 'trackback' :
		?>
		<div class="comment-inside">
			<p><?php _e( 'Pingback:', TPL_SLUG ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', TPL_SLUG ), ' ' ); ?></p>
		</div>
		<?php
				break;
		endswitch;
	}
	
	function top_image_video($post_id, $width = 295, $height = 0 ){
		$height_param = '';
		if( $height > 0){
			$height_param = '&amp;h=' . $height;
		}
		$img_or_vid = get_post_meta($post_id, '_image_or_video_1', true);
		if( empty( $img_or_vid ) )
			return;
		if( $img_or_vid == 'post_image_1' ){
			$src = $this->prepare_path( get_post_meta($post_id, '_post_image_1', true) );
			$attrs = get_post_meta($post_id, '_post_image_attr_1', true);
	 		if( !empty( $src ) ){
				$src = TPL_ADV . '/inc/timthumb.php?src=' . $src . '&amp;w=' . $width . $height_param . '&amp;zc=1';
			 	?>
				 	<div class="item-image">
				 		<a class="alpha-scope article" href="<?php echo get_permalink( $post_id ); ?>"><img src="<?php echo $src; ?>" <?php echo $attrs; ?> /></a>
				 	</div> 
			 	<?php
	 		}
		}
		elseif( $img_or_vid == 'post_video_1' ){
			$src = get_post_meta($post_id, '_post_video_1', true);
	 		if( !empty( $src ) ){
			 	?>
				 	<div class="item-video">
				 		<?php echo $this->detect_movie( $src, false, $width, $height ); ?>
				 	</div> 
			 	<?php
	 		}
		}
	}
	
	function portfolio_top_image_video( $width = 220, $height = 220, $sidebar = false  ){
		if( $sidebar ){
			$thumb = get_post_meta(get_the_ID(), '_sidebar_thumb', true);
			$src = TPL_GFX . '/' . TPL_COLOR . '/photo_small.jpg';
			$attrs = get_post_meta(get_the_ID(), '_sidebar_thumb_attr', true);
		}
		else{
			$thumb = get_post_meta(get_the_ID(), '_homepage_thumb', true);
			$src = TPL_GFX . '/' . TPL_COLOR . '/photo.jpg';
			$attrs = get_post_meta(get_the_ID(), '_homepage_thumb_attr', true);
		}
		$img_or_vid = get_post_meta(get_the_ID(), '_image_or_video_1', true);
		$double_link = false;
		$media_link = '';
		$title = get_the_title();
		
		//get src for thumb
		if( !empty( $img_or_vid ) ){
			//title
			if( get_post_meta(get_the_ID(), '_lightbox_switch_1', true) == 'lightbox_text_1' )
				$title .= '|' . trim( get_post_meta(get_the_ID(), '_lightbox_text_1', true) );
			elseif( get_post_meta(get_the_ID(), '_caption_switch_1', true) == 'caption_text_1' )
				$title .= '|' . trim( get_post_meta(get_the_ID(), '_caption_text_1', true) );
			
			//other data	
			if( $img_or_vid == 'post_image_1' ){
				$media_link = $this->prepare_path( get_post_meta(get_the_ID(), '_post_image_1', true) );
		 		if( !empty( $media_link ) ){
					$src = TPL_ADV . '/inc/timthumb.php?src=' . $media_link . '&amp;w=' . $width . '&amp;h=' . $height . '&amp;zc=1';
					$attrs = get_post_meta(get_the_ID(), '_post_image_attr_1', true);
					$double_link = true;
					$media_link = '<a title="' . $title . '" href="' . $media_link . '" class="alpha-scope-image"><em></em></a>';
		 		}
			}
			elseif( $img_or_vid == 'post_video_1' ){
				$media_link = get_post_meta(get_the_ID(), '_post_video_1', true);
		 		if( !empty( $media_link ) ){
		 			if( $sidebar )
		 				$src = TPL_GFX . '/' . TPL_COLOR . '/video_small.jpg';
		 			else
		 				$src = TPL_GFX . '/' . TPL_COLOR . '/video.jpg';
					$double_link = true;
					$media_link = '<a title="' . $title . '" href="' . $this->detect_movie( $media_link, true, 560, 340 ) . '" class="alpha-scope-video iframe"><em></em></a>';
		 		}
			}
		}
		//if set thumb then use it instead
		if( !empty( $thumb ) ){
			$src = $thumb;
		}
		$icon_mode = $this->theme_options[ 'portfolio_options' ][ 'portfolio_icon_switch' ];
		if( $icon_mode != 'auto'  ){
			$double_link = false;
		}
		
		//generate HTML
		?>
	 	<div class="item-image">
	 		<?php if( $double_link ): ?>
	 			<span class="alpha-scope alpha-scope-double-icon"><?php echo $media_link; ?><a class="alpha-scope-more" href="<?php echo get_permalink( get_the_ID() ); ?>"></a><img src="<?php echo $src; ?>" <?php echo $attrs; ?> /></span>
	 		<?php else:
	 			$link_to_use = '<a class="alpha-scope-more" href="' . get_permalink( get_the_ID() ) . '"><em></em></a>';
	 			if( $icon_mode == 'view_photo')
	 				$link_to_use = $media_link;
	 			else{}
	 		?>
	 			<span class="alpha-scope alpha-scope-single-icon"><?php echo $link_to_use; ?><img src="<?php echo $src; ?>" <?php echo $attrs; ?> /><span class="alpha-scope-all" style="display: none;"><span class="alpha-scope-bg" style="opacity: 0.5;"></span><a class="alpha-scope-more" href="<?php echo get_permalink( get_the_ID() ); ?>"></a></span></span>
	 			<?php endif; ?>
	 	</div>
		<?php
		
	}
	
	function detect_movie( $src, $get_link = false, $width = 295, $height = 0 ){
		if( $height == 0){
			$height = ceil((9/16) * $width);
		}
//		if( $autoplay == 'on' )
//			$autoplay = '&amp;autoplay=1';
//		else
			$autoplay = '';
		
		if (preg_match("/(youtube\.com\/watch\?)?v=([a-zA-Z0-9\-_]+)&?/s", $src, $matches)){
			$video_id = $matches[2];
			$link = 'http://www.youtube.com/embed/' . $video_id . '?controls=1' . $autoplay . '&amp;fs=1&amp;hd=1&amp;loop=0&amp;rel=0&amp;showinfo=1&amp;showsearch=0&amp;wmode=transparent';
			if( $get_link )
				return $link;
			else
				return '<iframe id="crazytube' . mt_rand() . '" style="height: ' . $height . 'px; width: ' . $width . 'px; border: none;" src="' . $link . '"></iframe>';
		}
		// regexp $src http://vimeo.com/16998178
		elseif (preg_match("/(vimeo\.com\/)([0-9]+)/s", $src, $matches)){
			$video_id = $matches[2];
			$link = 'http://player.vimeo.com/video/' . $video_id . '?title=1' . $autoplay . 'loop=0';
			if( $get_link )
				return $link;
			else
				return '<iframe id="crazyvimeo' . mt_rand() .'" style="height: ' . $height . 'px; width: ' . $width . 'px; border: none;" src="' . $link . '"></iframe>';
		}
		else{
			$link = TPL_ADV . '/inc/videojs/player.php?src=' . $src . '&amp;w=' . $width . '&amp;h=' . $height;
			if( $get_link )
				return $link;
			else
				return '<iframe id="crazyvideojs' . mt_rand() .'" style="height: ' . $height . 'px; width: ' . $width . 'px; border: none;" src="' . $link . '"></iframe>';
		}
	}
	
	function blog_nav() {
		global $wp_query;
	
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<div id="posts-nav">
				<div class="nav-previous"><?php previous_posts_link( '' ); ?></div>
				<div class="nav-next"><?php next_posts_link( '' ); ?></div>
			</div>
		<?php endif;
	}
	
	function blog_post_nav() {
		if( defined( 'PORTFOLIO_PAGE' ) && PORTFOLIO_PAGE ){
			$href = site_url() . '?page_id=' . $this->get_option( 'portfolio_options', 'portfolio_page' );
			$title = __( 'Back to Portfolio', TPL_SLUG );
		}
		else{
			$href = site_url() . '?page_id=' . get_option( 'page_for_posts'); 
			$title = __( 'Back to Blog', TPL_SLUG );
		}
		?>				
			<div id="posts-nav" class="in-post">
				<a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="back-to-blog"></a>
				<div class="nav-previous"><?php next_post_link( '%link', '' ); ?></div>
				<div class="nav-next"><?php previous_post_link( '%link', '' ); ?></div>
			</div>
		<?php
	}
	
	function prepare_path($url) {
		if( USE_RELATIVE_PATHS ){
			return str_replace(get_bloginfo('url'), '', $url);
		}
		else
			return $url;
	}
	
	function make_collection( $collection ){
		$slides = array();
		$media_count = get_post_meta(get_the_ID(), '_image_count', true);
		if( $media_count ){
			for( $i = 1; $i <= $media_count; $i++ ){
				$switch = get_post_meta(get_the_ID(), '_image_or_video_' . $i, true);
				if( $switch ){
					$media = get_post_meta(get_the_ID(), '_' . $switch, true);
					if( $media ){
						$type = substr( $switch, 5, 5 );
						$attrs = trim( get_post_meta(get_the_ID(), '_post_image_attr_' . $i, true) );
						$caption = '';
						if( get_post_meta(get_the_ID(), '_caption_switch_' . $i, true) == 'caption_text_' . $i )
							$caption = trim( get_post_meta(get_the_ID(), '_caption_text_' . $i, true) );
						$slides[] = array( 
							'type'    => $type,
						    'attrs'     => $attrs,
							'src'     => $media,
							'caption' => $caption
						);
					}
				}
			}
			
			switch( $collection ){
				case 'vertical':
					if( sizeof($slides) ){
						echo '<div id="variant-verical">';
							foreach( $slides as $slide ){
								$caption = '';
								if( strlen( $slide['caption'] ) ){
									$caption = '<div class="item-caption">' . $slide['caption'] . '</div>';
								}
								if( $slide['type'] == 'image' ){
									$slide['src'] = $this->prepare_path( $slide['src'] );
									$src = TPL_ADV . '/inc/timthumb.php?src=' . $slide['src'] . '&amp;w=640&amp;zc=1';
									echo '<div class="vert-item"><img src="' . $src . '" ' . $slide['attrs'] . ' />' . $caption . '</div>';
								}
								else{
									echo '<div class="vert-item">' . $this->detect_movie( $slide['src'], false, 640 ) . $caption . '</div>';
								}
							}
						echo '</div>';
					}
					break;
				case 'slider':
					if( sizeof($slides) > 1 ){
						echo '<ul id="image-video-slider">';
							foreach( $slides as $slide ){
								$caption = '';
								if( strlen( $slide['caption'] ) ){
									$caption = '<div class="item-caption">' . $slide['caption'] . '</div>';
								}
								if( $slide['type'] == 'image' ){
									$slide['src'] = $this->prepare_path( $slide['src'] );
									$src = TPL_ADV . '/inc/timthumb.php?src=' . $slide['src'] . '&amp;w=640&amp;zc=1';
									echo '<li><img src="' . $src . '" ' . $slide['attrs'] . ' />' . $caption . '</li>';
								}
								else{
									echo '<li>' . $this->detect_movie( $slide['src'], false, 640 ) . $caption . '</li>';
								}
							}
						echo '</ul>';
					}
					elseif( sizeof($slides) ){
						$slide =  $slides[0];
						$caption = '';
						if( strlen( $slide['caption'] ) ){
							$caption = '<div class="item-caption">' . $slide['caption'] . '</div>';
						}
						if( $slide['type'] == 'image' ){
							$slide['src'] = $this->prepare_path( $slide['src'] );
							$src = TPL_ADV . '/inc/timthumb.php?src=' . $slide['src'] . '&amp;w=640&amp;zc=1';
							echo '<div id="post-image"><img src="' . $src . '" ' . $slide['attrs'] . ' />' . $caption . '</div>';
						}
						else{
							echo '<div id="post-image">' . $this->detect_movie( $slide['src'], false, 640 ) . '</div>' . $caption;
						}
					}
					break;
				case 'liquid':
					if( sizeof($slides) ){
						echo '<div id="variant-liquid">';
							foreach( $slides as $slide ){
								$caption = '';
								if( strlen( $slide['caption'] ) ){
									$caption = '<div class="item-caption">' . $slide['caption'] . '</div>';
								}
								if( $slide['type'] == 'image' ){
									$slide['src'] = $this->prepare_path( $slide['src'] );
									$src = TPL_ADV . '/inc/timthumb.php?src=' . $slide['src'] . '&amp;w=455&amp;zc=1';
									echo '<div class="liquid-item"><img src="' . $src . '" ' . $slide['attrs'] . ' />' . $caption . '</div>';
								}
								else{
									echo '<div class="liquid-item">' . $this->detect_movie( $slide['src'], false, 455 ) . $caption . '</div>';
								}
							}
						echo '</div>';
					}
					break;
			}
			
			
		}
	}
}