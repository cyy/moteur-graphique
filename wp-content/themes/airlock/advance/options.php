<?php
	function apollo13_settings(){
		
		$opt = array(
			array(
				"name" => __( 'Layout width', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Choose:', TPL_SLUG ),
				"desc" => "",
				"id" => "layout_width",
				"default" => 'full',
				"options" => array(
					"full" => __( 'Wide(100%)', TPL_SLUG ),
					"960" => __( 'Narrow(960px)', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Customize Logo', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Custom logo image(light)', TPL_SLUG ),
				"desc" =>__( 'Enter an URL or upload an image for logo. Paste the full URL (include <code>http://</code>). Left empty to use default theme value.', TPL_SLUG ),
				"id" => "logo_image_light",
				"default" => "",
				"type" => "upload"
			),
			array(
				"name" => __( 'Custom logo image(dark)', TPL_SLUG ),
				"desc" =>__( 'Enter an URL or upload an image for logo. Paste the full URL (include <code>http://</code>). Left empty to use default theme value.', TPL_SLUG ),
				"id" => "logo_image_dark",
				"default" => "",
				"type" => "upload"
			),
			array(
				"name" => __( 'Theme styles', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Choose default style file to use:', TPL_SLUG ),
				"desc" => "",
				"id" => "theme_styles",
				"default" => 'style-light',
				"options" => array(
					"style-light" => 'light',
					"style-dark" => 'dark',
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Enable theme switcher:', TPL_SLUG ),
				"desc" => __( 'If disabled: <strong>Auto evening/day detector</strong> will also be disabled', TPL_SLUG ),
				"id" => "theme_switcher",
				"default" => 'on',
				"options" => array(
					"on" => 'Enable',
					"off" => 'Disable',
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Auto evening/day detector', TPL_SLUG ),
				"desc" => __( 'If enabled: depending on user local time theme will load in dark(evening) or light(day) version', TPL_SLUG ),
				"id" => "theme_detect",
				"default" => 'on',
				"options" => array(
					"on" => 'Enable',
					"off" => 'Disable',
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Enable theme footer', TPL_SLUG ),
				"desc" => '',
				"id" => "theme_footer_switcher",
				"default" => 'on',
				"options" => array(
					"on" => 'Enable',
					"off" => 'Disable',
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Blog settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Blog page title', TPL_SLUG ),
				"desc" =>__( 'Enter text you want to use (empty hides this)', TPL_SLUG ),
				"id" => "blog_h1",
				"default" => "Blog",
				"type" => "input"
			),
			array(
				"name" => __( 'Sidebar', TPL_SLUG ),
				"desc" => '',
				"id" => "blog_sidebar_switch",
				"default" => 'left',
				"options" => array(
					"left" => __( 'Left', TPL_SLUG ),
					"right" => __( 'Right', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Other pages', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Sidebar', TPL_SLUG ),
				"desc" => '',
				"id" => "sidebar_switch",
				"default" => 'left',
				"options" => array(
					"left" => __( 'Left', TPL_SLUG ),
					"right" => __( 'Right', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Contact form settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'E-mail address where e-mails will be sent:', TPL_SLUG ),
				"desc" => __( "If empty, will use admin site e-mail", TPL_SLUG ),
				"id" => "contact_email",
				"default" => '',
				"type" => "input",
			),
			array(
				"name" => __( 'Google Analytics', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Enter code here from GA here:', TPL_SLUG ),
				"desc" => "",
				"id" => "ga_code",
				"default" => '',
				"type" => "textarea",
			),
		);
		
		return $opt;
	}
	
	
	function apollo13_design_options(){
		$opt = array(
			array(
				"name" => __( 'Home Page - options and design', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Sidebar', TPL_SLUG ),
				"type" => "block"
			),
			array(
				"name" => __( 'Switch sidebar', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_sidebar_switch",
				"default" => 'left',
				"options" => array(
					"left" => __( 'Left', TPL_SLUG ),
					"right" => __( 'Right', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'HelloText', TPL_SLUG ),
				"type" => "block"
			),
			array(
				"name" => __( 'Switch on/off HelloText section', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_hello_switch",
				"default" => 'on',
				"options" => array(
					"on" => __( 'Enable', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Text', TPL_SLUG ),
				"desc" => __( 'If you click enter the text moves to the next line', TPL_SLUG ),
				"id" => "hp_hello_text",
				"default" => 'Welocome to Airlock theme. Here you can setup welcome message that will be available only on frontpage.<br />You can also disable this in Airlock settings.',
				"type" => "textarea",
			),
			array(
				"name" => __( 'Move relative to the other', TPL_SLUG ),
				"desc" => __( 'Clicking the button will move up or down relative to other modules', TPL_SLUG ),
				"id" => "hp_hello_position",
				"default" => '1',
				"type" => "mover",
			),
			array(
				"name" => __( 'Portfolio', TPL_SLUG ),
				"type" => "block"
			),
			array(
				"name" => __( 'Switch on/off portfolio section', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_portfolio_switch",
				"default" => 'on',
				"options" => array(
					"on" => __( 'Enable', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Show only images(no texts)', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_portfolio_images_switch",
				"default" => 'off',
				"options" => array(
					"on" => __( 'Enable', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Pagination', TPL_SLUG ),
				"desc" => __( 'If you only use portfolio on front page, enabling pagination may come handy.', TPL_SLUG ),
				"id" => "hp_portfolio_pagination",
				"default" => 'off',
				"options" => array(
					"on" => __( 'Enable', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Portfolio items displayed', TPL_SLUG ),
				"desc" => __( "Set number of items displayed in front page. Set 0 to use global WP setting(10 as default). Set to -1 to display all. Keep in mind that items are ordered by date, so some categories may be empty if you set limit here.", TPL_SLUG ),
				"id" => "hp_portfolio_items_per_page",
				"default" => '-1',
				"type" => "input",
			),
			array(
				"name" => __( 'Category', TPL_SLUG ),
				"desc" => __( 'Select from: All, Featured and other categories added', TPL_SLUG ),
				"id" => "hp_portfolio_category",
				"default" => 'all',
				"type" => "dropdown_portfolio_categories",
			),
			array(
				"name" => __( 'Move relative to the other', TPL_SLUG ),
				"desc" => __( 'Clicking the button will move up or down relative to other modules', TPL_SLUG ),
				"id" => "hp_portfolio_position",
				"default" => '2',
				"type" => "mover",
			),
			array(
				"name" => __( 'Blog posts', TPL_SLUG ),
				"type" => "block"
			),
			array(
				"name" => __( 'Switch on/off blog section', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_blog_switch",
				"default" => 'on',
				"options" => array(
					"on" => __( 'Enable', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Title of blog section', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_blog_title",
				"default" => __( 'Latest from Blog', TPL_SLUG ),
				"type" => "input",
			),
			array(
				"name" => __( 'Category', TPL_SLUG ),
				"desc" => __( 'Select category form which posts will be displayed', TPL_SLUG ),
				"id" => "hp_blog_category",
				"default" => '0',
				"type" => "dropdown_blog_categories",
			),
			array(
				"name" => __( 'Number of posts:', TPL_SLUG ),
				"desc" => __( 'Set to -1 to show all posts', TPL_SLUG ),
				"id" => "no_of_latest_posts",
				"default" => '5',
				"type" => "input",
			),
			array(
				"name" => __( 'Move relative to the other', TPL_SLUG ),
				"desc" => __( 'Clicking the button will move up or down relative to other modules', TPL_SLUG ),
				"id" => "hp_blog_position",
				"default" => '3',
				"type" => "mover",
			),
			array(
				"name" => __( 'Footer', TPL_SLUG ),
				"type" => "block"
			),
			array(
				"name" => __( 'Switch on/off footer section', TPL_SLUG ),
				"desc" => '',
				"id" => "hp_footer_switch",
				"default" => 'on',
				"options" => array(
					"on" => __( 'Enable', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
		);
		
		return $opt;
	}
	
	
	function apollo13_thumbs_options(){
		$opt = array(
			array(
				"name" => __( 'Thumbs sizes', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Height of portfolio thumbs', TPL_SLUG ),
				"desc" =>__( 'This will affect front-page, and Portfolio page thumbs', TPL_SLUG ),
				"id" => "portfolio_thumb",
				"default" => "220",
				"type" => "input"
			),
			array(
				"name" => __( 'Height of sidebar thumbs', TPL_SLUG ),
				"desc" =>__( 'This will affect siedbar thumbs for posts and projects', TPL_SLUG ),
				"id" => "sidebar_thumb",
				"default" => "80",
				"type" => "input"
			),
		);
		
		return $opt;
	}
	
	
	function apollo13_fonts_options(){
		$fonts = array();
		//array of cufon fonts
		if( is_dir( TPL_FONTS_DIR ) ) {
			foreach ( glob( TPL_FONTS_DIR . '/' . '*.js' ) as $file ){
				preg_match('/([a-zA-Z-]+[0-9]*[a-zA-Z_-]*)_([0-9]+)-*/', basename($file), $matches);
				$fonts[ $matches[1] ] = basename($file);
			}
		}
		
			
		$opt = array(
			array(
				"name" => __( 'Cufon settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Choose:', TPL_SLUG ),
				"desc" => "",
				"id" => "cufon_switch",
				"default" => 'enable',
				"options" => array(
					"enable" => __( 'Cufon on', TPL_SLUG ),
					"disable" => __( 'Cufon off', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Choose font to use:', TPL_SLUG ),
				"desc" => "",
				"id" => "cufon_fonts",
				"default" => 'TitilliumText22L_Th',
				"options" => $fonts,
				"type" => "cufon",
			)
		);
		
		return $opt;
	}
	
	
	function apollo13_color_options(){
		$opt = array(
			array(
				"name" => __( 'Customize &lt;body&gt; area', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Custom background color', TPL_SLUG ),
				"desc" =>__( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.', TPL_SLUG ),
				"id" => "body_bg_color",
				"default" => "",
				"type" => "color"
			),
			array(
				"name" => __( 'Custom background image', TPL_SLUG ),
				"desc" =>__( 'Enter an URL or upload an image for background. Paste the full URL (include <code>http://</code>). Left empty to use default theme value.', TPL_SLUG ),
				"id" => "body_image",
				"default" => "",
				"type" => "upload"
			),
			array(
				"name" => __( 'Background horizontal position', TPL_SLUG ),
				"desc" => __( 'If you set one position setting, you will have to set other also to make this setting work. ', TPL_SLUG ),
				"id" => "body_position_x",
				"default" => 'default',
				"options" => array(
					"default" => __( 'default theme value', TPL_SLUG ),
					"left" => __( 'left', TPL_SLUG ),
					"center" => __( 'center', TPL_SLUG ),
					"right" => __( 'right', TPL_SLUG ),
				),
				"type" => "select",
			),
			array(
				"name" => __( 'Background vertical position', TPL_SLUG ),
				"desc" => __( 'If you set one position setting, you will have to set other also to make this setting work. ', TPL_SLUG ),
				"id" => "body_position_y",
				"default" => 'default',
				"options" => array(
					"default" => __( 'default theme value', TPL_SLUG ),
					"top" => __( 'left', TPL_SLUG ),
					"center" => __( 'center', TPL_SLUG ),
					"bottom" => __( 'bottom', TPL_SLUG ),
				),
				"type" => "select",
			),
			array(
				"name" => __( 'Background Repeat', TPL_SLUG ),
				"desc" => "",
				"id" => "body_repeat",
				"default" => 'default',
				"options" => array(
					"default" => __( 'default theme value', TPL_SLUG ),
					"no-repeat" => __( 'no repeat', TPL_SLUG ),
					"repeat" => __( 'repeat in both axes', TPL_SLUG ),
					"repeat-x" => __( 'repeat horizontal', TPL_SLUG ),
					"repeat-y" => __('repeat verical', TPL_SLUG ),
				),
				"type" => "select",
			),
		);
		
		return $opt;
	}
	
	function apollo13_footer_options(){
			
		$opt = array(
			array(
				"name" => __( 'Footer texts settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Footer copyright text:', TPL_SLUG ),
				"desc" => "",
				"id" => "footer_copyright",
				"default" => '© 2011 Airlock. All rights reserved. Created by <a href="http://themes.apollo13.eu/">Apollo13</a> on <a href="http://wordpress.org/">WordPress</a>',
				"type" => "textarea",
			),
			array(
				"name" => __( 'Footer most bottom-right text:', TPL_SLUG ),
				"desc" => "",
				"id" => "footer_bottom",
				"default" => 'Airlock Inc.  Made with Passion',
				"type" => "textarea",
			),
		);
		
		return $opt;
	}
	
	function apollo13_portfolio_options(){
			
		$opt = array(
			array(
				"name" => __( 'Portfolio settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Portfolio slug name', TPL_SLUG ),
				"desc" => __( "Remember that if you use nice permalinks(eg. <code>yoursite.com/page-about-me</code>, <code>yoursite.com/portfolio/damn-empty/</code>) then <strong>NONE of your static pages</strong> should have same slug as this, or pagination will break and other problems may appear.", TPL_SLUG ),
				"id" => "portfolio_post_type",
				"default" => 'portfolio',
				"type" => "input",
			),
			array(
				"name" => __( 'Show featured category?', TPL_SLUG ),
				"desc" => "",
				"id" => "show_featured",
				"default" => 'yes',
				"options" => array(
					"yes" => __( 'Yes', TPL_SLUG ),
					"no" => __( 'No', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Select portfolio page', TPL_SLUG ),
				"desc" => __( "Choose the one you use for portfolio display", TPL_SLUG ),
				"id" => "portfolio_page",
				"default" => '0',
				"type" => "wp_dropdown_pages",
			),
			array(
				"name" => __( 'Icons on portfolio item', TPL_SLUG ),
				"desc" => __( "Automatic is default mode. <strong>View mode</strong> will show only view image/play video icon. <strong>Details mode</strong> will show only 'go to details' icon. If you use View mode, you have to be sure you provided good URL for each images/video.", TPL_SLUG ),
				"id" => "portfolio_icon_switch",
				"default" => 'auto',
				"options" => array(
					"auto" => __( 'Automatic', TPL_SLUG ),
					"view_photo" => __( 'View mode', TPL_SLUG ),
					"view_item" => __( 'Details mode', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Portfolio items per page', TPL_SLUG ),
				"desc" => __( "Set number of items per page. This setting will be used on portfolio page. Items are ordered by date. Set to 0 to display all.", TPL_SLUG ),
				"id" => "items_per_page",
				"default" => '-1',
				"type" => "input",
			),
			array(
				"name" => __( 'Sidebar', TPL_SLUG ),
				"desc" => '',
				"id" => "sidebar_switch",
				"default" => 'left',
				"options" => array(
					"left" => __( 'Left', TPL_SLUG ),
					"right" => __( 'Right', TPL_SLUG ),
					"off" => __( 'Disable', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Comments', TPL_SLUG ),
				"desc" => __( "You can change this individually for each portfolio item", TPL_SLUG ),
				"id" => "portfolio_comments",
				"default" => 'off',
				"options" => array(
					"on" => __( 'On', TPL_SLUG ),
					"off" => __( 'Off', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Disabled comments note', TPL_SLUG ),
				"desc" => __( "If comments for portfolio are disabled this note will be displayed. Left empty for no note.", TPL_SLUG ),
				"id" => "portfolio_comments_disabled",
				"default" => 'Comments are disabled.',
				"type" => "input",
			),
		);
		
		return $opt;
	}
	
	function apollo13_social_options(){
		$socials = array(
			'aim' => 'Aim',
			'behance' => 'Behance',
			'blogger' => 'Blogger',
			'delicious' => 'Delicious',
			'deviantart' => 'Deviantart',
			'digg' => 'Digg',
			'dribbble' => 'Dribbble',
			'evernote' => 'Evernote',
			'facebook' => 'Facebook',
			'flickr' => 'Flickr',
			'forrst' => 'Forrst',
			'foursquare' => 'Foursquare',
			'github' => 'Github',
			'googleplus' => 'Google Plus',
			'lastfm' => 'Lastfm',
			'linkedin' => 'Linkedin',
			'paypal' => 'Paypal',
			'quora' => 'Quora',
			'rss' => 'RSS',
			'sharethis' => 'Sharethis',
			'skype' => 'Skype',
			'stumbleupon' => 'Stumbleupon',
			'tumblr' => 'Tumblr',
			'twitter' => 'Twitter',
			'vimeo' => 'Vimeo',
			'wordpress' => 'Wordpress',
			'yahoo' => 'Yahoo',
			'youtube' => 'Youtube',
		);
	
		$opt = array(
			array(
				"name" => __( 'Social settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Number of visible icons:', TPL_SLUG ),
				"desc" => "",
				"id" => "social_number_of_visible",
				"default" => '4',
				"type" => "input",
			),
			array(
				"name" => __( 'Icons set', TPL_SLUG ),
				"desc" => '',
				"id" => "social-icons-set",
				"default" => 'social-1',
				"options" => array(
					"social-1" => __( 'Icon set 1', TPL_SLUG ),
					"social-2" => __( 'Icon set 2', TPL_SLUG ),
					"social-3" => __( 'Icon set 3', TPL_SLUG ),
				),
				"type" => "radio",
			),
			array(
				"name" => __( 'Social services', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Social services', TPL_SLUG ),
				"desc" => __( 'Use <code>http://</code> in your social links', TPL_SLUG ),
				"id" => "social_services",
				"default" => '',
				"type" => "social",
				"options" => $socials
			),
		);
		
		return $opt;
	}
	
	function apollo13_advance_options(){
			
		$opt = array(
			array(
				"name" => __( 'Timthumb settings', TPL_SLUG ),
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Use relative paths', TPL_SLUG ),
				"desc" => __( 'Don\'t change it if images in your blog loads normaly. Only if none image in blog is loading try to change this.', TPL_SLUG ),
				"id" => "timthumb_relative_paths",
				"default" => 'no',
				"options" => array(
					"yes" => __( 'Yes', TPL_SLUG ),
					"no" => __( 'No', TPL_SLUG ),
				),
				"type" => "radio",
			),
		);
		
		return $opt;
	}
?>