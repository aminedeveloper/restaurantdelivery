<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_section_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_section_theme_setup' );
	function pizzahouse_sc_section_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_section_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

pizzahouse_storage_set('sc_section_dedicated', '');

if (!function_exists('pizzahouse_sc_section')) {	
	function pizzahouse_sc_section($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = pizzahouse_get_scheme_color('bg');
			$rgb = pizzahouse_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(pizzahouse_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!pizzahouse_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(pizzahouse_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !pizzahouse_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = pizzahouse_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && pizzahouse_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = pizzahouse_prepare_css_value($width);
		$height = pizzahouse_prepare_css_value($height);
	
		if ((!pizzahouse_param_is_off($scroll) || !pizzahouse_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!pizzahouse_param_is_off($scroll)) pizzahouse_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($bg_image !== '' ? ' bg_image' : '')
					. ($class ? ' ' . esc_attr($class) : '')
					. ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (pizzahouse_param_is_on($scroll) && !pizzahouse_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || pizzahouse_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (pizzahouse_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (pizzahouse_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (pizzahouse_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (pizzahouse_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(pizzahouse_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(pizzahouse_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(pizzahouse_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.pizzahouse_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (pizzahouse_param_is_on($pan) ? '</div>' : '')
					. (pizzahouse_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!pizzahouse_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || pizzahouse_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (pizzahouse_param_is_on($dedicated)) {
			if (pizzahouse_storage_get('sc_section_dedicated')=='') {
				pizzahouse_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	add_shortcode('trx_section', 'pizzahouse_sc_section');
}

if (!function_exists('pizzahouse_sc_block')) {	
	function pizzahouse_sc_block($atts, $content=null) {
		$atts = is_array($atts) ? $atts : array();
		$atts['class'] = (!empty($atts['class']) ? $atts['class'] . ' ' : '') . 'sc_section_block';
		return apply_filters('pizzahouse_shortcode_output', pizzahouse_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	add_shortcode('trx_block', 'pizzahouse_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_section_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_section_reg_shortcodes');
	function pizzahouse_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'pizzahouse'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'pizzahouse') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title for the block", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
							"title" => esc_html__("Subtitle", 'pizzahouse'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'pizzahouse') ),
							"value" => "",
							"type" => "text"
						),
				"description" => array(
					"title" => esc_html__("Description", 'pizzahouse'),
					"desc" => wp_kses_data( __("Short description for the block", 'pizzahouse') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'pizzahouse'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'pizzahouse'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'pizzahouse') ),
					"value" => "no",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select block alignment", 'pizzahouse') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'pizzahouse') ),
					"value" => "none",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'pizzahouse'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'pizzahouse') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'pizzahouse'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'pizzahouse') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'pizzahouse'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'pizzahouse') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'pizzahouse'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'pizzahouse') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'pizzahouse') ),
					"value" => "",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'pizzahouse'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'pizzahouse') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'pizzahouse'),
					"desc" => wp_kses_data( __("Any background color for this section", 'pizzahouse') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'pizzahouse'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'pizzahouse') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'pizzahouse'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'pizzahouse') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'pizzahouse'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'pizzahouse') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'pizzahouse') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'pizzahouse'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'pizzahouse'),
					"desc" => wp_kses_data( __("Font weight of the text", 'pizzahouse') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'pizzahouse'),
						'300' => esc_html__('Light (300)', 'pizzahouse'),
						'400' => esc_html__('Normal (400)', 'pizzahouse'),
						'700' => esc_html__('Bold (700)', 'pizzahouse')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Content for section container", 'pizzahouse') ),
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
		);
		pizzahouse_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'pizzahouse');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'pizzahouse');
		pizzahouse_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_section_reg_shortcodes_vc');
	function pizzahouse_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'pizzahouse'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'pizzahouse'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "pizzahouse"),
					"description" => wp_kses_data( __("Select block alignment", "pizzahouse") ),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", "pizzahouse"),
					"description" => wp_kses_data( __("Select width for columns emulation", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('columns')),
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
				array(
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", "pizzahouse"),
					"description" => wp_kses_data( __("Use pan effect to show section content", "pizzahouse") ),
					"group" => esc_html__('Scroll', 'pizzahouse'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", "pizzahouse"),
					"description" => wp_kses_data( __("Use scroller to show section content", "pizzahouse") ),
					"group" => esc_html__('Scroll', 'pizzahouse'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", "pizzahouse"),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'pizzahouse'),
					"value" => array_flip((array)pizzahouse_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", "pizzahouse"),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", "pizzahouse") ),
					"class" => "",
					"group" => esc_html__('Scroll', 'pizzahouse'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip((array)pizzahouse_get_sc_param('controls')),
					"type" => "dropdown"
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
					"param_name" => "color",
					"heading" => esc_html__("Fore color", "pizzahouse"),
					"description" => wp_kses_data( __("Any color for objects in this section", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "pizzahouse"),
					"description" => wp_kses_data( __("Any background color for this section", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", "pizzahouse"),
					"description" => wp_kses_data( __("Select background image from library for this section", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", "pizzahouse"),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", "pizzahouse"),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", "pizzahouse"),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", "pizzahouse"),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'pizzahouse') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "pizzahouse"),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "pizzahouse"),
					"description" => wp_kses_data( __("Font weight of the text", "pizzahouse") ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'pizzahouse') => 'inherit',
						esc_html__('Thin (100)', 'pizzahouse') => '100',
						esc_html__('Light (300)', 'pizzahouse') => '300',
						esc_html__('Normal (400)', 'pizzahouse') => '400',
						esc_html__('Bold (700)', 'pizzahouse') => '700'
					),
					"type" => "dropdown"
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'pizzahouse');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'pizzahouse') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends PIZZAHOUSE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends PIZZAHOUSE_VC_ShortCodeCollection {}
	}
}
?>