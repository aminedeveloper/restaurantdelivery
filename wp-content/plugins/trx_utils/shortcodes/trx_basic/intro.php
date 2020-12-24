<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_intro_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_intro_theme_setup' );
	function pizzahouse_sc_intro_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_intro_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_intro_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('pizzahouse_sc_intro')) {
	function pizzahouse_sc_intro($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"align" => "none",
			"image" => "",
			"bg_color" => "",
			"icon" => "",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Read more', 'pizzahouse'),
			"link2" => '',
			"link2_caption" => '',
			"url" => "",
			"content_position" => "",
			"content_width" => "",
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
		
		$width  = pizzahouse_prepare_css_value($width);
		$height = pizzahouse_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);

		$css .= pizzahouse_get_css_dimensions_from_values($width,$height);
		$css .= ($image ? 'background: url('.$image.');' : '');
		$css .= ($bg_color ? 'background-color: '.$bg_color.';' : '');
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_intro_buttons sc_item_buttons">'
							. (!empty($link) 
								? '<div class="sc_intro_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" size="medium"]'.esc_html($link_caption).'[/trx_button]').'</div>' 
								: '')
							. (!empty($link2) && $style==2 
								? '<div class="sc_intro_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" size="medium"]'.esc_html($link2_caption).'[/trx_button]').'</div>' 
								: '')
							. '</div>'
						: '');
						
		$output = '<div '.(!empty($url) ? 'data-href="'.esc_url($url).'"' : '') 
					. ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_intro' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($content_position && $style==1 ? ' sc_intro_position_' . esc_attr($content_position) : '') 
						. ($style==5 ? ' small_padding' : '') 
						. ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
					. ($css ? ' style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_intro_inner '.($style ? ' sc_intro_style_' . esc_attr($style) : '').'"'.(!empty($content_width) ? ' style="width:'.esc_attr($content_width).';"' : '').'>'
						. (!empty($icon) && $style==5 ? '<div class="sc_intro_icon '.esc_attr($icon).'"></div>' : '')
						. '<div class="sc_intro_content">'
							. (!empty($subtitle) && $style!=4 && $style!=5 ? '<h6 class="sc_intro_subtitle">' . trim(pizzahouse_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_intro_title">' . trim(pizzahouse_strmacros($title)) . '</h2>' : '')
							. (!empty($description) && $style!=1 ? '<div class="sc_intro_descr">' . trim(pizzahouse_strmacros($description)) . '</div>' : '')
							. ($style==2 || $style==3 ? $buttons : '')
						. '</div>'
					. '</div>'
				.'</div>';
	
	
	
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_intro', $atts, $content);
	}
	add_shortcode('trx_intro', 'pizzahouse_sc_intro');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_intro_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_intro_reg_shortcodes');
	function pizzahouse_sc_intro_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_intro", array(
			"title" => esc_html__("Intro", 'pizzahouse'),
			"desc" => wp_kses_data( __("Insert Intro block in your page (post)", 'pizzahouse') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select style to display block", 'pizzahouse') ),
					"value" => "1",
					"type" => "checklist",
					"options" => pizzahouse_get_list_styles(1, 5)
				),
				"align" => array(
					"title" => esc_html__("Alignment of the intro block", 'pizzahouse'),
					"desc" => wp_kses_data( __("Align whole intro block to left or right side of the page or parent container", 'pizzahouse') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('float')
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select the intro image from the library for this section", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select background color for the intro", 'pizzahouse') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Icon',  'pizzahouse'),
					"desc" => wp_kses_data( __("Select icon from Fontello icons set",  'pizzahouse') ),
					"dependency" => array(
						'style' => array(5)
					),
					"value" => "",
					"type" => "icons",
					"options" => pizzahouse_get_sc_param('icons')
				),
				"content_position" => array(
					"title" => esc_html__('Content position', 'pizzahouse'),
					"desc" => wp_kses_data( __("Select content position", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "top_left",
					"type" => "checklist",
					"options" => array(
						'top_left' => esc_html__('Top Left', 'pizzahouse'),
						'top_right' => esc_html__('Top Right', 'pizzahouse'),
						'bottom_right' => esc_html__('Bottom Right', 'pizzahouse'),
						'bottom_left' => esc_html__('Bottom Left', 'pizzahouse')
					)
				),
				"content_width" => array(
					"title" => esc_html__('Content width', 'pizzahouse'),
					"desc" => wp_kses_data( __("Select content width", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "100%",
					"type" => "checklist",
					"options" => array(
						'100%' => esc_html__('100%', 'pizzahouse'),
						'90%' => esc_html__('90%', 'pizzahouse'),
						'80%' => esc_html__('80%', 'pizzahouse'),
						'70%' => esc_html__('70%', 'pizzahouse'),
						'60%' => esc_html__('60%', 'pizzahouse'),
						'50%' => esc_html__('50%', 'pizzahouse'),
						'40%' => esc_html__('40%', 'pizzahouse'),
						'30%' => esc_html__('30%', 'pizzahouse')
					)
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'pizzahouse'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'pizzahouse') ),
					"divider" => true,
					"dependency" => array(
						'style' => array(1,2,3)
					),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title for the block", 'pizzahouse') ),
					"value" => "",
					"type" => "textarea"
				),
				"description" => array(
					"title" => esc_html__("Description", 'pizzahouse'),
					"desc" => wp_kses_data( __("Short description for the block", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(2,3,4,5),
					),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(2,3),
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'pizzahouse'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(2,3),
					),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(2)
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'pizzahouse'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'pizzahouse') ),
					"dependency" => array(
						'style' => array(2)
					),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("Link", 'pizzahouse'),
					"desc" => wp_kses_data( __("Link of the intro block", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'pizzahouse') ),
					"value" => "",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('schemes')
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
if ( !function_exists( 'pizzahouse_sc_intro_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_intro_reg_shortcodes_vc');
	function pizzahouse_sc_intro_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_intro",
			"name" => esc_html__("Intro", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert Intro block", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_intro',
			"class" => "trx_sc_single trx_sc_intro",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style of the block", 'pizzahouse'),
					"description" => wp_kses_data( __("Select style to display this block", 'pizzahouse') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip((array)pizzahouse_get_list_styles(1, 5)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the block", 'pizzahouse'),
					"description" => wp_kses_data( __("Align whole intro block to left or right side of the page or parent container", 'pizzahouse') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip((array)pizzahouse_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'pizzahouse'),
					"description" => wp_kses_data( __("Select the intro image from the library for this section", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'pizzahouse'),
					"description" => wp_kses_data( __("Select background color for the intro", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'pizzahouse'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set", 'pizzahouse') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('5')
					),
					"value" => pizzahouse_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content_position",
					"heading" => esc_html__("Content position", 'pizzahouse'),
					"description" => wp_kses_data( __("Select content position", 'pizzahouse') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('Top Left', 'pizzahouse') => 'top_left',
						esc_html__('Top Right', 'pizzahouse') => 'top_right',
						esc_html__('Bottom Right', 'pizzahouse') => 'bottom_right',
						esc_html__('Bottom Left', 'pizzahouse') => 'bottom_left'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content_width",
					"heading" => esc_html__("Content width", 'pizzahouse'),
					"description" => wp_kses_data( __("Select content width", 'pizzahouse') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('100%', 'pizzahouse') => '100%',
						esc_html__('90%', 'pizzahouse') => '90%',
						esc_html__('80%', 'pizzahouse') => '80%',
						esc_html__('70%', 'pizzahouse') => '70%',
						esc_html__('60%', 'pizzahouse') => '60%',
						esc_html__('50%', 'pizzahouse') => '50%',
						esc_html__('40%', 'pizzahouse') => '40%',
						esc_html__('30%', 'pizzahouse') => '30%'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'pizzahouse'),
					"description" => wp_kses_data( __("Subtitle for the block", 'pizzahouse') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1','2','3')
					),
					"group" => esc_html__('Captions', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pizzahouse'),
					"description" => wp_kses_data( __("Title for the block", 'pizzahouse') ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'pizzahouse'),
					"description" => wp_kses_data( __("Description for the block", 'pizzahouse') ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3','4','5')
					),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'pizzahouse'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'pizzahouse') ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'pizzahouse'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'pizzahouse') ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'pizzahouse'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'pizzahouse') ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'pizzahouse'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'pizzahouse') ),
					"group" => esc_html__('Captions', 'pizzahouse'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Link", 'pizzahouse'),
					"description" => wp_kses_data( __("Link of the intro block", 'pizzahouse') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'pizzahouse'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'pizzahouse') ),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('schemes')),
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
		) );
		
		class WPBakeryShortCode_Trx_Intro extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>