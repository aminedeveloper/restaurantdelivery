<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_image_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_image_theme_setup' );
	function pizzahouse_sc_image_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_image_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('pizzahouse_sc_image')) {
	function pizzahouse_sc_image($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"url2" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}


		if ($url2 > 0) {
			$attach = wp_get_attachment_image_src( $url2, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$url2 = $attach[0];
		}

		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = pizzahouse_get_resized_image_url($src, $w, $h);
			if ($w || $h) $url2 = pizzahouse_get_resized_image_url($url2, $w, $h);
		}
		if (trim($link)) pizzahouse_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'"'.($url2 ? 'class="linked"' : '').'>'
				.($url2 ? '<img class="img_link" src="'.esc_url($url2).'" alt="" />' : '')
				: '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	add_shortcode('trx_image', 'pizzahouse_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_image_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_image_reg_shortcodes');
	function pizzahouse_sc_image_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'pizzahouse'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"url2" => array(
					"title" => esc_html__("URL for image file(if link)", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'pizzahouse'),
					"desc" => wp_kses_data( __("Image title (if need)", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'pizzahouse'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'pizzahouse') ),
					"value" => "",
					"type" => "icons",
					"options" => pizzahouse_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'pizzahouse'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'pizzahouse') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'pizzahouse'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'pizzahouse') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'pizzahouse'),
						"round" => esc_html__('Round', 'pizzahouse')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'pizzahouse'),
					"desc" => wp_kses_data( __("The link URL from the image", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"width" => pizzahouse_shortcodes_width(),
				"height" => pizzahouse_shortcodes_height(),
				"top" => pizzahouse_get_sc_param('top'),
				"bottom" => pizzahouse_get_sc_param('bottom'),
				"left" => pizzahouse_get_sc_param('left'),
				"right" => pizzahouse_get_sc_param('right'),
				"id" => pizzahouse_get_sc_param('id'),
				"class" => pizzahouse_get_sc_param('class'),
				"animation" => pizzahouse_get_sc_param('animation'),
				"css" => pizzahouse_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_image_reg_shortcodes_vc');
	function pizzahouse_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert image", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'pizzahouse'),
					"description" => wp_kses_data( __("Select image from library", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "url2",
					"heading" => esc_html__("Select image (if link)", 'pizzahouse'),
					"description" => wp_kses_data( __("Select image from library", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'pizzahouse'),
					"description" => wp_kses_data( __("Align image to left or right side", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pizzahouse_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'pizzahouse'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'pizzahouse') => 'square',
						esc_html__('Round', 'pizzahouse') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pizzahouse'),
					"description" => wp_kses_data( __("Image's title", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'pizzahouse'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'pizzahouse') ),
					"class" => "",
					"value" => pizzahouse_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'pizzahouse'),
					"description" => wp_kses_data( __("The link URL from the image", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_vc_width(),
				pizzahouse_vc_height(),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Image extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>