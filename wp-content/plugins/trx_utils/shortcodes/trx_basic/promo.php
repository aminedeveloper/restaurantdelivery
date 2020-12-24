<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_promo_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_promo_theme_setup' );
	function pizzahouse_sc_promo_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_promo_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_promo_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('pizzahouse_sc_promo')) {	
	function pizzahouse_sc_promo($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "large",
			"align" => "none",
			"image" => "",
			"image_position" => "left",
			"image_width" => "50%",
			"text_margins" => '',
			"text_align" => "left",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'pizzahouse'),
			"link" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if ($image == '') {
			$image_width = '0%';
			$text_margins = '';
		}
		
		$width  = pizzahouse_prepare_css_value($width);
		$height = pizzahouse_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width, $height);
		
		$css_image = (!empty($image) ? 'background-image:url(' . esc_url($image) . ');' : '')
				     . (!empty($image_width) ? 'width:'.trim($image_width).';' : '')
				     . (!empty($image_position) ? $image_position.': 0;' : '');
	
		$text_width = pizzahouse_strpos($image_width, '%')!==false
						? (100 - (int) str_replace('%', '', $image_width)).'%'
						: 'calc(100%-'.trim($image_width).')';
		$css_text = 'width: '.esc_attr($text_width).'; float: '.($image_position=='left' ? 'right' : 'left').';'.(!empty($text_margins) ? ' margin:'.esc_attr($text_margins).';' : '');
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_promo' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($size ? ' sc_promo_size_'.esc_attr($size) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. (empty($image) ? ' no_image' : '')
						. '"'
					. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
					. ($css ? 'style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_promo_inner">'
						. (!empty($image) ? '<div class="sc_promo_image" style="'.esc_attr($css_image).'"></div>' : '')
						. '<div class="sc_promo_block sc_align_'.esc_attr($text_align).'" style="'.esc_attr($css_text).'">'
							. '<div class="sc_promo_block_inner">'
									. (!empty($subtitle) ? '<h6 class="sc_promo_subtitle sc_item_subtitle">' . trim(pizzahouse_strmacros($subtitle)) . '</h6>' : '')
									. (!empty($title) ? '<h2 class="sc_promo_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(pizzahouse_strmacros($title)) . '</h2>' : '')
									. (!empty($description) ? '<div class="sc_promo_descr sc_item_descr">' . trim(pizzahouse_strmacros($description)) . '</div>' : '')
									. (!empty($content) ? '<div class="sc_promo_content">'.do_shortcode($content).'</div>' : '')
									. (!empty($link) ? '<div class="sc_promo_button sc_item_button">'.pizzahouse_do_shortcode('[trx_button link="'.esc_url($link).'"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
							. '</div>'
						. '</div>'
					. '</div>'
				. '</div>';
	
	
	
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_promo', $atts, $content);
	}
	add_shortcode('trx_promo', 'pizzahouse_sc_promo');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_promo_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_promo_reg_shortcodes');
	function pizzahouse_sc_promo_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_promo", array(
			"title" => esc_html__("Promo", 'pizzahouse'),
			"desc" => wp_kses_data( __("Insert promo diagramm in your page (post)", 'pizzahouse') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment of the promo block", "pizzahouse"),
					"desc" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", "pizzahouse") ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('float')
				), 
				"size" => array(
					"title" => esc_html__("Size of the promo block", "pizzahouse"),
					"desc" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", "pizzahouse") ),
					"value" => "large",
					"type" => "switch",
					"options" => array(
						'small' => esc_html__('Small', 'pizzahouse'),
						'large' => esc_html__('Large', 'pizzahouse')
					)
				), 
				"image" => array(
					"title" => esc_html__("Image URL", "pizzahouse"),
					"desc" => wp_kses_data( __("Select the promo image from the library for this section", "pizzahouse") ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_position" => array(
					"title" => esc_html__("Image position", "pizzahouse"),
					"desc" => wp_kses_data( __("Place the image to the left or to the right from the text block", "pizzahouse") ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('hpos')
				),
				"image_width" => array(
					"title" => esc_html__("Image width", "pizzahouse"),
					"desc" => wp_kses_data( __("Width (in pixels or percents) of the block with image", "pizzahouse") ),
					"value" => "50%",
					"type" => "text"
				),
				"text_margins" => array(
					"title" => esc_html__("Text margins", "pizzahouse"),
					"desc" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"text_align" => array(
					"title" => esc_html__("Text alignment", "pizzahouse"),
					"desc" => wp_kses_data( __("Align the text inside the block", "pizzahouse") ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('align')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "pizzahouse"),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", "pizzahouse") ),
					"value" => "",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('schemes')
				),
				"title" => array(
					"title" => esc_html__("Title", "pizzahouse"),
					"desc" => wp_kses_data( __("Title for the block", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", "pizzahouse"),
					"desc" => wp_kses_data( __("Subtitle for the block", "pizzahouse") ),
							"value" => "",
							"type" => "text"
						),
				"description" => array(
					"title" => esc_html__("Description", "pizzahouse"),
					"desc" => wp_kses_data( __("Short description for the block", "pizzahouse") ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", "pizzahouse"),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", "pizzahouse"),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", "pizzahouse") ),
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
if ( !function_exists( 'pizzahouse_sc_promo_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_promo_reg_shortcodes_vc');
	function pizzahouse_sc_promo_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_promo",
			"name" => esc_html__("Promo", "pizzahouse"),
			"description" => wp_kses_data( __("Insert promo block", "pizzahouse") ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_promo',
			"class" => "trx_sc_collection trx_sc_promo",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the promo block", "pizzahouse"),
					"description" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", "pizzahouse") ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip((array)pizzahouse_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Size of the promo block", "pizzahouse"),
					"description" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", "pizzahouse") ),
					"class" => "",
					"value" => array(esc_html__('Use small block', 'pizzahouse') => 'small'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", "pizzahouse"),
					"description" => wp_kses_data( __("Select the promo image from the library for this section", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_position",
					"heading" => esc_html__("Image position", "pizzahouse"),
					"description" => wp_kses_data( __("Place the image to the left or to the right from the text block", "pizzahouse") ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip((array)pizzahouse_get_sc_param('hpos')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image_width",
					"heading" => esc_html__("Image width", "pizzahouse"),
					"description" => wp_kses_data( __("Width (in pixels or percents) of the block with image", "pizzahouse") ),
					"value" => '',
					"std" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "text_margins",
					"heading" => esc_html__("Text margins", "pizzahouse"),
					"description" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", "pizzahouse") ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "text_align",
					"heading" => esc_html__("Text alignment", "pizzahouse"),
					"description" => wp_kses_data( __("Align text to the left or to the right side inside the block", "pizzahouse") ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip((array)pizzahouse_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "pizzahouse"),
					"description" => wp_kses_data( __("Select color scheme for the section with text", "pizzahouse") ),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "pizzahouse"),
					"description" => wp_kses_data( __("Title for the block", "pizzahouse") ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
						"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", "pizzahouse"),
					"description" => wp_kses_data( __("Subtitle for the block", "pizzahouse") ),
						"group" => esc_html__('Captions', 'pizzahouse'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", "pizzahouse"),
					"description" => wp_kses_data( __("Description for the block", "pizzahouse") ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", "pizzahouse"),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", "pizzahouse") ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", "pizzahouse"),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", "pizzahouse") ),
					"group" => esc_html__('Captions', 'pizzahouse'),
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
		
		class WPBakeryShortCode_Trx_Promo extends PIZZAHOUSE_VC_ShortCodeCollection {}
	}
}
?>