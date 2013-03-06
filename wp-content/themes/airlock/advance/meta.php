<?php
	function apollo13_metaboxes_post(){
		
		$meta = array(
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Multi upload', TPL_SLUG ),
				"desc" => '',
				"id" => "multi-upload",
				"type" => "multi-upload",
			),
			array(
				"name" => '',
				"type" => "fieldset",
				"additive" => true,
				"default" => "1",
				"title" => "1",
				"id" => 'image_count'
			),
			array(
				"name" => __( 'Choose image or video', TPL_SLUG ),
				"desc" =>__( 'Choose between Image or Video', TPL_SLUG ),
				"id" => "image_or_video",
				"default" => "",
				"type" => "switch",
				"options" => array(
					"post_image" => __( 'Image', TPL_SLUG ),
					"post_video" => __( 'Video', TPL_SLUG )
				),
			),
			array(
				"name" => __( 'Upload image', TPL_SLUG ),
				"desc" => '',
				"id" => "post_image",
				"default" => "",
				"type" => "upload"
			),
			array(
				"name"    => __( 'Image attributes', TPL_SLUG ),
				"desc"    => __( '<strong>alt</strong> is required for <strong>img</strong> elements' ),
				"id"      => 'post_image_attr',
			    'class'   => 'for-image-attributes',
				"default" => 'alt=""',
				"type"    => "input"
			),
			array(
				"name" => __( 'Link to video', TPL_SLUG ),
				"desc" => '',
				"id" => "post_video",
				"default" => "",
				"type" => "input"
			),
			array(
				"type" => "end-switch",
			),
			array(
				"name" => __( 'Move relative to the other', TPL_SLUG ),
				"desc" => __( 'Clicking the button will move up or down relative to other images or videos', TPL_SLUG ),
				"id" => "image_position",
				"default" => '1',
				"type" => "mover",
			),
			array(
				"name" => __( 'Add next image or video', TPL_SLUG ),
				"desc" => '',
				"default" => '1',
				"type" => "adder",
			),
		);
		
		return $meta;
	}
	
	function apollo13_metaboxes_page(){
		
		$meta = array(
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Top image', TPL_SLUG ),
				"desc" => __( '<strong>Remember</strong> image will be 640px wide', TPL_SLUG ),
				"id" => "top_image",
				"default" => "",
				"type" => "upload"
			),
			array(
                "name"    => __( 'Image attributes', TPL_SLUG ),
                "desc"    => __( '<strong>alt</strong> is required for <strong>img</strong> elements' ),
                "id"      => 'top_image_attr',
                'class'   => 'for-image-attributes',
                "default" => 'alt=""',
                "type"    => "input"
            ),
			array(
				"name" => __( 'Page width', TPL_SLUG ),
				"desc" => '',
				"id" => "layout",
				"default" => "full-width",
				"type" => "select",
				"options" => array(
					"inner-640" => __( '640px', TPL_SLUG ),
					"full-width" => __( 'Full width', TPL_SLUG )
				),
			),
		);
		
		return $meta;
	}
	
	function apollo13_metaboxes_portfolio(){
		global $apollo13;
		$meta = array(
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Select portfolio variant', TPL_SLUG ),
				"desc" =>__( 'Choose between: Vertical position / Slider / Liquid', TPL_SLUG ),
				"id" => "variant",
				"default" => "",
				"type" => "select",
				"options" => array(
					"variant_vertical" => __( 'Vertical position', TPL_SLUG ),
					"variant_slider" => __( 'Slider', TPL_SLUG ),
					"variant_liquid" => __( 'Liquid', TPL_SLUG ),
				),
			),
			
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Custom HomePage thumbnail', TPL_SLUG ),
				"desc" => __( '<strong>Remember</strong>. Homepage thumbnail size: 220x' . $apollo13->get_option( 'thumbs_options', 'portfolio_thumb' ) . 'px', TPL_SLUG ),
				"id" => "homepage_thumb",
				"default" => "",
				"type" => "upload"
			),
			array(
                "name"    => __( 'Image attributes', TPL_SLUG ),
                "desc"    => __( '<strong>alt</strong> is required for <strong>img</strong> elements' ),
                "id"      => 'homepage_thumb_attr',
                'class'   => 'for-image-attributes',
                "default" => 'alt=""',
                "type"    => "input"
            ),
            array(
                "name" => '',
                "type" => "fieldset"
            ),
			array(
				"name" => __( 'Custom Sidebar thumbnail', TPL_SLUG ),
				"desc" => __( '<strong>Remember</strong>. Sidebar thumbnail size: 200x' . $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ) . 'px', TPL_SLUG ),
				"id" => "sidebar_thumb",
				"default" => "",
				"type" => "upload"
			),
			array(
                "name"    => __( 'Image attributes', TPL_SLUG ),
                "desc"    => __( '<strong>alt</strong> is required for <strong>img</strong> elements' ),
                "id"      => 'sidebar_thumb_attr',
                'class'   => 'for-image-attributes',
                "default" => 'alt=""',
                "type"    => "input"
            ),
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Short description under title', TPL_SLUG ),
				"desc" => __( 'This text will show on the home page and in the portfolio list', TPL_SLUG ),
				"id" => "project_desc",
				"default" => "",
				"type" => "input"
			),
			array(
				"name" => __( 'Link to the project in details', TPL_SLUG ),
				"desc" => __( 'URL. This address will show in detail', TPL_SLUG ),
				"id" => "project_url",
				"default" => "",
				"type" => "input"
			),
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Project featured?', TPL_SLUG ),
				"desc" => '',
				"id" => "featured",
				"default" => "no",
				"type" => "select",
				"options" => array(
					"no" => __( 'No', TPL_SLUG ),
					"yes" => __( 'Yes', TPL_SLUG )
				),
			),
		);
		
		return $meta;
	}

	function apollo13_metaboxes_portfolio_images(){
		
		$meta = array(
			array(
				"name" => '',
				"type" => "fieldset"
			),
			array(
				"name" => __( 'Multi upload', TPL_SLUG ),
				"desc" => '',
				"id" => "multi-upload",
				"type" => "multi-upload",
			),
			array(
				"name" => '',
				"type" => "fieldset",
				"additive" => true,
				"default" => "1",
				"title" => "1",
				"id" => 'image_count'
			),
			array(
				"name" => __( 'Choose image or video', TPL_SLUG ),
				"desc" =>__( 'Choose between Image or Video', TPL_SLUG ),
				"id" => "image_or_video",
				"default" => "",
				"type" => "switch",
				"options" => array(
					"post_image" => __( 'Image', TPL_SLUG ),
					"post_video" => __( 'Video', TPL_SLUG )
				),
			),
			array(
				"name" => __( 'Upload image', TPL_SLUG ),
				"desc" => '',
				"id" => "post_image",
				"default" => "",
				"type" => "upload"
			),
			array(
                "name"    => __( 'Image attributes', TPL_SLUG ),
                "desc"    => __( '<strong>alt</strong> is required for <strong>img</strong> elements' ),
                "id"      => 'post_image_attr',
                'class'   => 'for-image-attributes',
                "default" => 'alt=""',
                "type"    => "input"
            ),
			array(
				"name" => __( 'Link to video', TPL_SLUG ),
				"desc" => '',
				"id" => "post_video",
				"default" => "",
				"type" => "input"
			),
			array(
				"type" => "end-switch",
			),
			array(
				"name" => __( 'Caption text', TPL_SLUG ),
				"desc" => '',
				"id" => "caption_switch",
				"default" => "",
				"type" => "switch",
				"options" => array(
					"no_input" => __( 'No', TPL_SLUG ),
					"caption_text" => __( 'Yes', TPL_SLUG )
				),
			),
			array(
				"name" => '',
				"desc" =>__( 'This text appears in the details (bottom) of the selected project at the added photo or video', TPL_SLUG ),
				"id" => "caption_text",
				"default" => '',
				"type" => "textarea",
			),
			array(
				"type" => "end-switch",
			),
			array(
				"name" => __( 'Custom lightbox text', TPL_SLUG ),
				"desc" => '',
				"id" => "lightbox_switch",
				"default" => "",
				"type" => "switch",
				"options" => array(
					"no_input" => __( 'No', TPL_SLUG ),
					"lightbox_text" => __( 'Yes', TPL_SLUG )
				),
			),
			array(
				"name" => '',
				"desc" =>__( 'if you leave the description blank, the lightbox will display the caption text', TPL_SLUG ),
				"id" => "lightbox_text",
				"default" => '',
				"type" => "textarea",
			),
			array(
				"type" => "end-switch",
			),
			array(
				"name" => __( 'Move relative to the other', TPL_SLUG ),
				"desc" => __( 'Clicking the button will move up or down relative to other images or videos', TPL_SLUG ),
				"id" => "image_position",
				"default" => '1',
				"type" => "mover",
			),
			array(
				"name" => __( 'Add next image or video', TPL_SLUG ),
				"desc" => '',
				"default" => '1',
				"type" => "adder",
			),
		);
		
		return $meta;
	}
	
	
?>