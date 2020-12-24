<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_title_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_title_theme_setup' );
	function pizzahouse_sc_title_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_title_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('pizzahouse_sc_title')) {
	function pizzahouse_sc_title($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !pizzahouse_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !pizzahouse_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(pizzahouse_strpos($image, 'http')===0 ? $image : pizzahouse_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !pizzahouse_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	add_shortcode('trx_title', 'pizzahouse_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_title_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_title_reg_shortcodes');
	function pizzahouse_sc_title_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_title", array(
			"title" => esc_html__("Title", "pizzahouse"),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", "pizzahouse") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title content", 'pizzahouse') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title type (header level)", 'pizzahouse') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'pizzahouse'),
						'2' => esc_html__('Header 2', 'pizzahouse'),
						'3' => esc_html__('Header 3', 'pizzahouse'),
						'4' => esc_html__('Header 4', 'pizzahouse'),
						'5' => esc_html__('Header 5', 'pizzahouse'),
						'6' => esc_html__('Header 6', 'pizzahouse'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title style", 'pizzahouse') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'pizzahouse'),
						'underline' => esc_html__('Underline', 'pizzahouse'),
						'divider' => esc_html__('Divider', 'pizzahouse'),
						'iconed' => esc_html__('With icon (image)', 'pizzahouse')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title text alignment", 'pizzahouse') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'pizzahouse'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'pizzahouse'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'pizzahouse') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'pizzahouse'),
						'100' => esc_html__('Thin (100)', 'pizzahouse'),
						'300' => esc_html__('Light (300)', 'pizzahouse'),
						'400' => esc_html__('Normal (400)', 'pizzahouse'),
						'600' => esc_html__('Semibold (600)', 'pizzahouse'),
						'700' => esc_html__('Bold (700)', 'pizzahouse'),
						'900' => esc_html__('Black (900)', 'pizzahouse')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select color for the title", 'pizzahouse') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'pizzahouse'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'pizzahouse') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => pizzahouse_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'pizzahouse'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'pizzahouse') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => pizzahouse_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'pizzahouse'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'pizzahouse') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'pizzahouse'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'pizzahouse') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'pizzahouse'),
						'medium' => esc_html__('Medium', 'pizzahouse'),
						'large' => esc_html__('Large', 'pizzahouse')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'pizzahouse'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'pizzahouse') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'pizzahouse'),
						'left' => esc_html__('Left', 'pizzahouse')
					)
				),
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
if ( !function_exists( 'pizzahouse_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_title_reg_shortcodes_vc');
	function pizzahouse_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'pizzahouse'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'pizzahouse'),
					"description" => wp_kses_data( __("Title content", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'pizzahouse'),
					"description" => wp_kses_data( __("Title type (header level)", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'pizzahouse') => '1',
						esc_html__('Header 2', 'pizzahouse') => '2',
						esc_html__('Header 3', 'pizzahouse') => '3',
						esc_html__('Header 4', 'pizzahouse') => '4',
						esc_html__('Header 5', 'pizzahouse') => '5',
						esc_html__('Header 6', 'pizzahouse') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'pizzahouse'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'pizzahouse') => 'regular',
						esc_html__('Underline', 'pizzahouse') => 'underline',
						esc_html__('Divider', 'pizzahouse') => 'divider',
						esc_html__('With icon (image)', 'pizzahouse') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'pizzahouse'),
					"description" => wp_kses_data( __("Title text alignment", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'pizzahouse'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'pizzahouse'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'pizzahouse') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'pizzahouse') => 'inherit',
						esc_html__('Thin (100)', 'pizzahouse') => '100',
						esc_html__('Light (300)', 'pizzahouse') => '300',
						esc_html__('Normal (400)', 'pizzahouse') => '400',
						esc_html__('Semibold (600)', 'pizzahouse') => '600',
						esc_html__('Bold (700)', 'pizzahouse') => '700',
						esc_html__('Black (900)', 'pizzahouse') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'pizzahouse'),
					"description" => wp_kses_data( __("Select color for the title", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'pizzahouse'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'pizzahouse') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => pizzahouse_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'pizzahouse'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'pizzahouse') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => pizzahouse_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'pizzahouse'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'pizzahouse') ),
					"group" => esc_html__('Icon &amp; Image', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'pizzahouse'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'pizzahouse') ),
					"group" => esc_html__('Icon &amp; Image', 'pizzahouse'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'pizzahouse') => 'small',
						esc_html__('Medium', 'pizzahouse') => 'medium',
						esc_html__('Large', 'pizzahouse') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'pizzahouse'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'pizzahouse') ),
					"group" => esc_html__('Icon &amp; Image', 'pizzahouse'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'pizzahouse') => 'top',
						esc_html__('Left', 'pizzahouse') => 'left'
					),
					"type" => "dropdown"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>