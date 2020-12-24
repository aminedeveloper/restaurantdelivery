<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_price_block_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_price_block_theme_setup' );
	function pizzahouse_sc_price_block_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_price_block_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('pizzahouse_sc_price_block')) {
	function pizzahouse_sc_price_block($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"image" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
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
		$output = '';

		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$css_image = (!empty($image) ? 'background-image:url(' . esc_url($image) . ');' : '');


		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(pizzahouse_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($image) ? '<div class="sc_price_block_image" style="'.esc_attr($css_image).'"></div>' : '')
				. '<div class="sc_price_block_money">'
					. ($money)
				. '</div>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	add_shortcode('trx_price_block', 'pizzahouse_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_price_block_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_price_block_reg_shortcodes');
	function pizzahouse_sc_price_block_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", "pizzahouse"),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", "pizzahouse") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", "pizzahouse"),
					"desc" => wp_kses_data( __("Select style for this price block", "pizzahouse") ),
					"value" => 1,
					"options" => pizzahouse_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", "pizzahouse"),
					"desc" => wp_kses_data( __("Block title", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", "pizzahouse"),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", "pizzahouse"),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"image" => array(
					"title" => esc_html__("Image URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select the image from the library for this section", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"money" => array(
					"title" => esc_html__("Money", 'pizzahouse'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'pizzahouse') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", "pizzahouse"),
					"desc" => wp_kses_data( __("Currency character", "pizzahouse") ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", "pizzahouse"),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "pizzahouse"),
					"desc" => wp_kses_data( __("Select color scheme for this block", "pizzahouse") ),
					"value" => "",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", "pizzahouse"),
					"desc" => wp_kses_data( __("Align price to left or right side", "pizzahouse") ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", "pizzahouse"),
					"desc" => wp_kses_data( __("Description for this price block", "pizzahouse") ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'pizzahouse_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_price_block_reg_shortcodes_vc');
	function pizzahouse_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", "pizzahouse"),
			"description" => wp_kses_data( __("Insert price block with title, price and description", "pizzahouse") ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", "pizzahouse"),
					"desc" => wp_kses_data( __("Select style of this price block", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip((array)pizzahouse_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "pizzahouse"),
					"description" => wp_kses_data( __("Block title", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "pizzahouse"),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", "pizzahouse"),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'pizzahouse'),
					"description" => wp_kses_data( __("Select the image from the library for this section", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'pizzahouse'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'pizzahouse') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'pizzahouse'),
					"description" => wp_kses_data( __("Currency character", 'pizzahouse') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'pizzahouse'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", "pizzahouse"),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", "pizzahouse") ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "pizzahouse"),
					"description" => wp_kses_data( __("Select color scheme for this block", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "pizzahouse"),
					"description" => wp_kses_data( __("Align price to left or right side", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", "pizzahouse"),
					"description" => wp_kses_data( __("Description for this price block", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>