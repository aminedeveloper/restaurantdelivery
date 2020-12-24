<?php
/**
 * Theme custom styles without LESS
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('pizzahouse_action_theme_styles_no_less_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_action_theme_styles_no_less_theme_setup', 1 );
	function pizzahouse_action_theme_styles_no_less_theme_setup() {

		// If no LESS support
		if (pizzahouse_get_theme_setting('less_compiler') == 'no') {
			// Add theme styles
			add_action('pizzahouse_action_add_styles', 				'pizzahouse_action_theme_styles_no_less_add_styles');

			// Add colors and fonts for the WP Customizer
			add_action( 'customize_controls_print_footer_scripts',	'pizzahouse_customizer_wp_css_template' );
			add_action( 'pizzahouse_filter_get_css',					'pizzahouse_customizer_wp_add_scheme_in_css', 100, 4 );
		}
	}
}


// Add theme stylesheets
if (!function_exists('pizzahouse_action_theme_styles_no_less_add_styles')) {
	
	function pizzahouse_action_theme_styles_no_less_add_styles() {
		// Add stylesheet files
		if ( !is_customize_preview() && !isset($_GET['color_scheme']) && pizzahouse_param_is_off(pizzahouse_get_theme_option('debug_mode')) ) {
			wp_enqueue_style( 'pizzahouse-theme-style', pizzahouse_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'pizzahouse-theme-style', pizzahouse_get_inline_css() );
			wp_add_inline_style( 'pizzahouse-main-style', pizzahouse_get_custom_css() . pizzahouse_get_inline_css() );
		} else {
			wp_enqueue_style( 'pizzahouse-theme-style', pizzahouse_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'pizzahouse-main-style', pizzahouse_get_custom_css() . pizzahouse_get_inline_css() );
		}
	}
}


// Add scheme name in each selector in the CSS (priority 100 - after complete css)
if (!function_exists('pizzahouse_customizer_wp_add_scheme_in_css')) {
	
	function pizzahouse_customizer_wp_add_scheme_in_css($css, $colors, $fonts, $scheme) {
		$rez = '';
		$in_comment = $in_rule = false;
		$allow = true;
		$scheme_class = '.scheme_' . trim($scheme) . ' ';
		$self_class = '.scheme_self';
		$self_class_len = pizzahouse_strlen($self_class);
		$css_str = str_replace(array('{{', '}}'), array('[[',']]'), $css['colors']);
		for ($i=0; $i<strlen($css_str); $i++) {
			$ch = $css_str[$i];
			if ($in_comment) {
				$rez .= $ch;
				if ($ch=='/' && $css_str[$i-1]=='*') {
					$in_comment = false;
					$allow = !$in_rule;
				}
			} else if ($in_rule) {
				$rez .= $ch;
				if ($ch=='}') {
					$in_rule = false;
					$allow = !$in_comment;
				}
			} else {
				if ($ch=='/' && $css_str[$i+1]=='*') {
					$rez .= $ch;
					$in_comment = true;
				} else if ($ch=='{') {
					$rez .= $ch;
					$in_rule = true;
				} else if ($ch==',') {
					$rez .= $ch;
					$allow = true;
				} else if (pizzahouse_strpos(" \t\r\n", $ch)===false) {
					if ($allow && pizzahouse_substr($css_str, $i, $self_class_len) == $self_class) {
						$rez .= trim($scheme_class);
						$i += $self_class_len - 1;
					} else
						$rez .= ($allow ? $scheme_class : '') . $ch;
					$allow = false;
				} else {
					$rez .= $ch;
				}
			}
		}
		$rez = str_replace(array('[[',']]'), array('{{', '}}'), $rez);
		$css['colors'] = $rez;
		return $css;
	}
}



// Output an Underscore template for generating CSS for the color scheme.
// The template generates the css dynamically for instant display in the Customizer preview.
if ( !function_exists( 'pizzahouse_customizer_wp_css_template' ) ) {
	
	function pizzahouse_customizer_wp_css_template() {

		// Colors
		$colors = array(
			
			// Whole block border and background
			'bg_color'				=> '{{ data.bg_color }}',
			'bd_color'				=> '{{ data.bd_color }}',
			
			// Text and links colors
			'text'					=> '{{ data.text }}',
			'text_light'			=> '{{ data.text_light }}',
			'text_dark'				=> '{{ data.text_dark }}',
			'text_link'				=> '{{ data.text_link }}',
			'text_hover'			=> '{{ data.text_hover }}',
		
			// Alternative blocks (submenu, buttons, tabs, etc.)
			'alter_bg_color'		=> '{{ data.alter_bg_color }}',
			'alter_bg_hover'		=> '{{ data.alter_bg_hover }}',
			'alter_bd_color'		=> '{{ data.alter_bd_color }}',
			'alter_bd_hover'		=> '{{ data.alter_bd_hover }}',
			'alter_text'			=> '{{ data.alter_text }}',
			'alter_light'			=> '{{ data.alter_light }}',
			'alter_dark'			=> '{{ data.alter_dark }}',
			'alter_link'			=> '{{ data.alter_link }}',
			'alter_hover'			=> '{{ data.alter_hover }}',
		
			// Input fields (form's fields and textarea)
			'input_bg_color'		=> '{{ data.input_bg_color }}',
			'input_bg_hover'		=> '{{ data.input_bg_hover }}',
			'input_bd_color'		=> '{{ data.input_bd_color }}',
			'input_bd_hover'		=> '{{ data.input_bd_hover }}',
			'input_text'			=> '{{ data.input_text }}',
			'input_light'			=> '{{ data.input_light }}',
			'input_dark'			=> '{{ data.input_dark }}',

			// Inverse blocks (with background equal to the links color or one of accented colors)
			'inverse_text'			=> '{{ data.inverse_text }}',
			'inverse_light'			=> '{{ data.inverse_light }}',
			'inverse_dark'			=> '{{ data.inverse_dark }}',
			'inverse_link'			=> '{{ data.inverse_link }}',
			'inverse_hover'			=> '{{ data.inverse_hover }}',

			// Additional accented colors (if used in the current theme)
			
			
			

		);

		$tmpl_holder = 'script';

		$schemes = array_keys(pizzahouse_get_list_color_schemes());
		if (count($schemes) > 0) {
			foreach ($schemes as $scheme) {
				echo '<'.trim($tmpl_holder).' type="text/html" id="tmpl-pizzahouse-color-scheme-'.esc_attr($scheme).'">'
						. trim(pizzahouse_get_custom_css( $colors, false, false, $scheme ))
					. '</'.trim($tmpl_holder).'>';
			}
		}

		// Fonts
		$custom_fonts = pizzahouse_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			$fonts = array();
			foreach ($custom_fonts as $tag => $font) {
				$fonts[$tag.'_font-family']			= '{{ data["'.$tag.'_font-family"] }}';
				$fonts[$tag.'_font-size']			= '{{ data["'.$tag.'_font-size"] }}';
				$fonts[$tag.'_line-height']			= '{{ data["'.$tag.'_line-height"] }}';
				$fonts[$tag.'_line-height_value']	= '{{ data["'.$tag.'_line-height_value"] }}';
				$fonts[$tag.'_font-weight']			= '{{ data["'.$tag.'_font-weight"] }}';
				$fonts[$tag.'_font-style']			= '{{ data["'.$tag.'_font-style"] }}';
				$fonts[$tag.'_text-decoration']		= '{{ data["'.$tag.'_text-decoration"] }}';
				$fonts[$tag.'_margin-top']			= '{{ data["'.$tag.'_margin-top"] }}';
				$fonts[$tag.'_margin-top_value']	= '{{ data["'.$tag.'_margin-top_value"] }}';
				$fonts[$tag.'_margin-bottom']		= '{{ data["'.$tag.'_margin-bottom"] }}';
				$fonts[$tag.'_margin-bottom_value']	= '{{ data["'.$tag.'_margin-bottom_value"] }}';
			}
			echo '<'.trim($tmpl_holder).' type="text/html" id="tmpl-pizzahouse-fonts">'
					. trim(pizzahouse_get_custom_css( false, $fonts, false, false ))
				. '</'.trim($tmpl_holder).'>';
		}

	}
}

// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the core.customizer.wp.color-scheme.js
if (!function_exists('pizzahouse_customizer_wp_add_theme_colors')) {
	function pizzahouse_customizer_wp_add_theme_colors($colors) {
		if (pizzahouse_substr($colors['text'], 0, 2) == '{{') {
			$colors['text_dark_0_05']	= '{{ data.text_dark_0_05 }}';
			$colors['text_dark_0_1']	= '{{ data.text_dark_0_1 }}';
			$colors['text_dark_0_8']	= '{{ data.text_dark_0_8 }}';
			$colors['text_link_0_3']	= '{{ data.text_link_0_3 }}';
			$colors['text_link_0_6']	= '{{ data.text_link_0_6 }}';
			$colors['text_link_0_8']	= '{{ data.text_link_0_8 }}';
			$colors['text_hover_0_2']	= '{{ data.text_hover_0_2 }}';
			$colors['text_hover_0_3']	= '{{ data.text_hover_0_3 }}';
			$colors['text_hover_0_8']	= '{{ data.text_hover_0_8 }}';
			$colors['inverse_text_0_1']	= '{{ data.inverse_text_0_1 }}';
			$colors['bg_color_0_8']		= '{{ data.bg_color_0_8 }}';
			$colors['alter_text_0_1']	= '{{ data.alter_text_0_1 }}';
			$colors['alter_bg_color_0_8'] = '{{ data.alter_bg_color_0_8 }}';
			$colors['alter_bg_hover_0_5'] = '{{ data.alter_bg_hover_0_5 }}';
			$colors['alter_bd_color_0_1'] = '{{ data.alter_bd_color_0_1 }}';
			$colors['alter_hover_0_5'] = '{{ data.alter_hover_0_5 }}';
		} else {
			$colors['text_dark_0_05']	= pizzahouse_hex2rgba( $colors['text_dark'], 0.05 );
			$colors['text_dark_0_1']	= pizzahouse_hex2rgba( $colors['text_dark'], 0.1 );
			$colors['text_dark_0_8']	= pizzahouse_hex2rgba( $colors['text_dark'], 0.8 );
			$colors['text_link_0_3']	= pizzahouse_hex2rgba( $colors['text_link'], 0.3 );
			$colors['text_link_0_6']	= pizzahouse_hex2rgba( $colors['text_link'], 0.6 );
			$colors['text_link_0_8']	= pizzahouse_hex2rgba( $colors['text_link'], 0.8 );
			$colors['text_hover_0_2']	= pizzahouse_hex2rgba( $colors['text_hover'], 0.2 );
			$colors['text_hover_0_3']	= pizzahouse_hex2rgba( $colors['text_hover'], 0.3 );
			$colors['text_hover_0_8']	= pizzahouse_hex2rgba( $colors['text_hover'], 0.8 );
			$colors['inverse_text_0_1']	= pizzahouse_hex2rgba( $colors['inverse_text'], 0.1 );
			$colors['bg_color_0_8']		= pizzahouse_hex2rgba( $colors['bg_color'], 0.8 );
			$colors['alter_text_0_1']	= pizzahouse_hex2rgba( $colors['alter_text'], 0.1 );
			$colors['alter_bg_color_0_8'] = pizzahouse_hex2rgba( $colors['alter_bg_color'], 0.8 );
			$colors['alter_bg_hover_0_5'] = pizzahouse_hex2rgba( $colors['alter_bg_hover'], 0.5 );
			$colors['alter_bd_color_0_1'] = pizzahouse_hex2rgba( $colors['alter_bd_color'], 0.1 );
			$colors['alter_hover_0_5'] = pizzahouse_hex2rgba( $colors['alter_hover'], 0.2 );
		}
		return $colors;
	}
}
			
// Additional (calculated) theme-specific font parameters
// Attention! Don't forget setup custom fonts also in the core.customizer.wp.color-scheme.js
if (!function_exists('pizzahouse_customizer_wp_add_theme_fonts')) {
	function pizzahouse_customizer_wp_add_theme_fonts($fonts) {
		if (pizzahouse_substr($fonts['h1_font-family'], 0, 2) == '{{') {			
			$fonts['menu_height_1']			 = '{{ data["menu_height_1"] }}';
			$fonts['submenu_margin-top_1']	 = '{{ data["submenu_margin-top_1"] }}';
			$fonts['submenu_margin-bottom_1']= '{{ data["submenu_margin-bottom_1"] }}';
		} else {
			$fonts['menu_height_1']			 = pizzahouse_summ_css_value( pizzahouse_summ_css_value( $fonts['menu_margin-top_value'],  $fonts['menu_margin-bottom_value'] ), $fonts['menu_line-height_value'] );
			$fonts['submenu_margin-top_1']	 = $fonts['submenu_margin-top_value'];
			$fonts['submenu_margin-bottom_1']= $fonts['submenu_margin-bottom_value'];
		}
		return $fonts;
	}
}

// Return CSS with custom colors and fonts
if (!function_exists('pizzahouse_get_custom_css')) {

	function pizzahouse_get_custom_css($colors=null, $fonts=null, $minify=true, $only_scheme='') {

		$add_comment = $colors===null && $fonts===null && empty($only_scheme)
						? '/* ' . strip_tags( __("ATTENTION! This file was generated automatically! Don't change it!!!", 'pizzahouse') ) . "*/\n"
						: '';

		$css = $rez = array(
			'fonts' => '',
			'colors' => ''
		);
		
		// Prepare fonts
		if ($fonts===null) {
			$fonts_list = pizzahouse_get_list_fonts(false);
			$custom_fonts = pizzahouse_get_custom_fonts();
			$fonts = array();
			foreach ($custom_fonts as $tag => $font) {
				$fonts[$tag.'_font-family'] = !empty($font['font-family']) && !pizzahouse_is_inherit_option($font['font-family'])
												? "font-family:\"" . str_replace(' ('.esc_html__('uploaded font', 'pizzahouse').')', '', $font['font-family']) . '"' 
													. (isset($fonts_list[$font['font-family']]['family']) ? ',' . $fonts_list[$font['font-family']]['family'] : '' ) 
													. ";"
												: '';
				$fonts[$tag.'_font-size'] = !empty($font['font-size']) && !pizzahouse_is_inherit_option($font['font-size'])
												? "font-size:" . pizzahouse_prepare_css_value($font['font-size']) . ";"
												: '';
				$fonts[$tag.'_line-height'] = !empty($font['line-height']) && !pizzahouse_is_inherit_option($font['line-height'])
												? "line-height: " . pizzahouse_prepare_css_value($font['line-height']) . ";"
												: '';
				$fonts[$tag.'_line-height_value'] = !empty($font['line-height'])	
												? pizzahouse_prepare_css_value($font['line-height'])
												: 'inherit';
				$fonts[$tag.'_font-weight'] = !empty($font['font-weight']) && !pizzahouse_is_inherit_option($font['font-weight'])
												? "font-weight: " . trim($font['font-weight']) . ";"
												: '';
				$fonts[$tag.'_font-style'] = !empty($font['font-style']) && !pizzahouse_is_inherit_option($font['font-style']) && pizzahouse_strpos($font['font-style'], 'i')!==false
												? "font-style: italic;"
												: '';
				$fonts[$tag.'_text-decoration'] = !empty($font['font-style']) && !pizzahouse_is_inherit_option($font['font-style']) && pizzahouse_strpos($font['font-style'], 'u')!==false
												? "text-decoration: underline;"
												: '';
				$fonts[$tag.'_margin-top'] = !empty($font['margin-top']) && !pizzahouse_is_inherit_option($font['margin-top'])
												? "margin-top: " . pizzahouse_prepare_css_value($font['margin-top']) . ";"
												: '';
				$fonts[$tag.'_margin-top_value'] = !empty($font['margin-top'])	
												? pizzahouse_prepare_css_value($font['margin-top'])
												: 'inherit';
				$fonts[$tag.'_margin-bottom'] = !empty($font['margin-bottom']) && !pizzahouse_is_inherit_option($font['margin-bottom'])
												? "margin-bottom: " . pizzahouse_prepare_css_value($font['margin-bottom']) . ";"
												: '';
				$fonts[$tag.'_margin-bottom_value'] = !empty($font['margin-bottom'])	
												? pizzahouse_prepare_css_value($font['margin-bottom'])
												: 'inherit';
			}
		}
		if ($fonts) {
			$fonts = pizzahouse_customizer_wp_add_theme_fonts($fonts);
			// Attention! All font's rules mustn't have ';' in the end of the row
			$rez['fonts'] = <<<FONTS

/* Theme typography */
body {
	{$fonts['p_font-family']}
	{$fonts['p_font-size']}
	{$fonts['p_font-weight']}
	{$fonts['p_font-style']}
	{$fonts['p_line-height']}
	{$fonts['p_text-decoration']}
}

h1 {
	{$fonts['h1_font-family']}
	{$fonts['h1_font-size']}
	{$fonts['h1_font-weight']}
	{$fonts['h1_font-style']}
	{$fonts['h1_line-height']}
	{$fonts['h1_text-decoration']}
	{$fonts['h1_margin-top']}
	{$fonts['h1_margin-bottom']}
}
h2 {
	{$fonts['h2_font-family']}
	{$fonts['h2_font-size']}
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_margin-top']}
	{$fonts['h2_margin-bottom']}
}
h3 {
	{$fonts['h3_font-family']}
	{$fonts['h3_font-size']}
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_margin-top']}
	{$fonts['h3_margin-bottom']}
}
h4 {
	{$fonts['h4_font-family']}
	{$fonts['h4_font-size']}
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_margin-top']}
	{$fonts['h4_margin-bottom']}
}
h5 {
	{$fonts['h5_font-family']}
	{$fonts['h5_font-size']}
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_margin-top']}
	{$fonts['h5_margin-bottom']}
}
h6 {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_margin-top']}
	{$fonts['h6_margin-bottom']}
}

a {
	{$fonts['link_font-family']}
	{$fonts['link_font-size']}
	{$fonts['link_font-weight']}
	{$fonts['link_font-style']}
	{$fonts['link_line-height']}
	{$fonts['link_text-decoration']}
}

/* Form fields settings */

button[disabled],
input[type="submit"][disabled],
input[type="button"][disabled] {
	background-color: {$colors['text_light']} !important;
	color: {$colors['text']} !important;
}


input[type="text"],
input[type="tel"],
input[type="number"],
input[type="email"],
input[type="search"],
input[type="password"],
select,
textarea {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
}

input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
}

/* Top panel - middle area */

.logo .logo_text {
	{$fonts['logo_font-family']}
	{$fonts['logo_font-size']}
	{$fonts['logo_font-weight']}
	{$fonts['logo_font-style']}
	{$fonts['logo_line-height']}
	{$fonts['logo_text-decoration']}
}




/* Main menu */
.menu_main_nav > li > a {
	{$fonts['menu_font-family']}
	{$fonts['menu_font-size']}
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_line-height']}
	{$fonts['menu_text-decoration']}
}
.menu_main_nav > li ul {
	{$fonts['submenu_font-family']}
	{$fonts['submenu_font-size']}
	{$fonts['submenu_font-weight']}
	{$fonts['submenu_font-style']}
	{$fonts['submenu_line-height']}
	{$fonts['submenu_text-decoration']}
}

.top_panel_wrap .contact_field {
	{$fonts['menu_font-family']}
}




/* Post info */
.post_info {
	{$fonts['info_font-family']}
	{$fonts['info_font-size']}
	{$fonts['info_font-weight']}
	{$fonts['info_font-style']}
	{$fonts['info_line-height']}
	{$fonts['info_text-decoration']}
	{$fonts['info_margin-top']}
	{$fonts['info_margin-bottom']}
}

/* Page 404 */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	{$fonts['logo_font-family']}
}



/* Booking Calendar */
.booking_font_custom,
.booking_day_container,
.booking_calendar_container_all {
	{$fonts['p_font-family']}
}
.booking_weekdays_custom {
	{$fonts['h1_font-family']}
}

/* Media Elements */
.mejs-container .mejs-controls .mejs-time {
	{$fonts['p_font-family']}
}

/* Shortcodes */
.sc_recent_news .post_item .post_title {
	{$fonts['h5_font-family']}
	{$fonts['h5_font-size']}
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_margin-top']}
	{$fonts['h5_margin-bottom']}
}
.sc_recent_news .post_item h6.post_title {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_margin-top']}
	{$fonts['h6_margin-bottom']}
}


.rev_slider .trx-subtitle,
.sc_item_subtitle,
.sc_price_block .sc_price_block_money,
.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_count .sc_skills_total {
	{$fonts['other_font-family']}
}

.rev_slider .trx-title,
.sc_menuitems_style_menuitems-2 .sc_menuitem_price,
.sc_menuitems_style_menuitems-2 .sc_menuitem_title,
.sc_menuitems_style_menuitems-1 .sc_menuitem_price,
.esg-grid .eg-shop-content .esg-content,
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale,
.sc_services_item_readmore,
.tribe-events-list-separator-month span,
.tribe-events-calendar td div[id*="tribe-events-daynum-"],
.tribe-events-calendar thead th,


.tribe-events-calendar-month__header-column-title.tribe-common-b3, 
.tribe-common .tribe-common-b3, .tribe-events .tribe-events-c-ical__link, 
.tribe-common .tribe-common-c-btn-border, 
.tribe-common a.tribe-common-c-btn-border, 
body .tribe-events .tribe-events-c-top-bar__datepicker-button, 
.tribe-common .tribe-common-c-btn, 
.tribe-events .tribe-events-c-view-selector__list-item-text,

#tribe-bar-views .tribe-bar-views-list,
.sc_events_item .sc_events_item_readmore,
#tribe-bar-form .tribe-bar-submit input[type="submit"],
.tribe-events-button, #tribe-events .tribe-events-button,
#tribe-bar-form .tribe-bar-submit input[type="submit"],
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a,
.sc_tabs .sc_tabs_titles,
.sc_team_item .sc_team_item_info .sc_team_item_position,
.comments_list_wrap .comment_reply a,
.comments_list_wrap .comment_info,
.post_item_related .link,
.sc_price_block_title {
	{$fonts['h1_font-family']}
}

.esg-grid .eg-shop-content .esg-content.eg-shop-element-5,
#tribe-events-content .tribe-events-calendar div[id*="tribe-events-event-"] h3.tribe-events-month-event-title {
	{$fonts['p_font-family']}
}



FONTS;
		}

		if ($colors!==false) {
			$schemes = empty($only_scheme) ? array_keys(pizzahouse_get_list_color_schemes()) : array($only_scheme);
			if (count($schemes) > 0) {
				$step = 1;
				foreach ($schemes as $scheme) {
					// Prepare colors
					if (empty($only_scheme)) $colors = pizzahouse_get_scheme_colors($scheme);

					// Make theme-specific colors and tints
					$colors = pizzahouse_customizer_wp_add_theme_colors($colors);

					// Make styles
					$rez['colors'] = <<<CSS

/* 2. Theme Colors
------------------------------------------------------------------------- */

h1, h2, h3, h4, h5, h6,
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
	color: {$colors['text_dark']};
}
.widget_rss h5.widget_title a{
    color: {$colors['text_dark']};
}
h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {
	color: {$colors['text_link']};
}
.widget_rss h5.widget_title a:hover{
    color: {$colors['text_link']};
}
a {
	color: {$colors['text_link']};
}
a:hover {
	color: {$colors['text_hover']};
}
blockquote {
	background-color: {$colors['text_link']};
}
blockquote:before {
	color: {$colors['alter_hover']};
}
blockquote, blockquote p {
	color: {$colors['inverse_text']};
}
blockquote a {
	color: {$colors['inverse_text']};
}
blockquote a:hover {
	color: {$colors['alter_hover']};
}
.sc_quote_title {
	{$fonts['h1_font-family']}
}

.accent1 {			color: {$colors['text_link']}; }
.accent1_bgc {		background-color: {$colors['text_link']}; }
.accent1_bg {		background: {$colors['text_link']}; }
.accent1_border {	border-color: {$colors['text_link']}; }
a.accent1:hover {	color: {$colors['text_hover']}; }

.alter_link_subtitle .sc_item_subtitle {	color: {$colors['alter_link']}; }
.text_link_subtitle .sc_item_subtitle {	color: {$colors['text_link']}; }
.text_link_subtitle .sc_title_icon {	background-color: {$colors['text_link']}; }

/* Portfolio hovers */
.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect3.colored .info,
.post_content.ih-item.circle.effect4.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect6.colored .info,
.post_content.ih-item.circle.effect7.colored .info,
.post_content.ih-item.circle.effect8.colored .info,
.post_content.ih-item.circle.effect9.colored .info,
.post_content.ih-item.circle.effect10.colored .info,
.post_content.ih-item.circle.effect11.colored .info,
.post_content.ih-item.circle.effect12.colored .info,
.post_content.ih-item.circle.effect13.colored .info,
.post_content.ih-item.circle.effect14.colored .info,
.post_content.ih-item.circle.effect15.colored .info,
.post_content.ih-item.circle.effect16.colored .info,
.post_content.ih-item.circle.effect18.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect1.colored .info,
.post_content.ih-item.square.effect2.colored .info,
.post_content.ih-item.square.effect3.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect5.colored .info,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect8.colored .info,
.post_content.ih-item.square.effect9.colored .info .info-back,
.post_content.ih-item.square.effect10.colored .info,
.post_content.ih-item.square.effect11.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect14.colored .info,
.post_content.ih-item.square.effect15.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect_book.colored .info,
.post_content.ih-item.square.effect_pull.colored .post_descr {
	background: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect_more.colored .info,
.post_content.ih-item.square.effect_dir.colored .info,
.post_content.ih-item.square.effect_shift.colored .info {
	background: {$colors['text_link_0_6']};
	color: {$colors['inverse_text']};
}
.post_content.ih-item.square.effect_border.colored .img,
.post_content.ih-item.square.effect_fade.colored .img,
.post_content.ih-item.square.effect_slide.colored .img {
	background: {$colors['text_link']};
}
.post_content.ih-item.square.effect_border.colored .info,
.post_content.ih-item.square.effect_fade.colored .info,
.post_content.ih-item.square.effect_slide.colored .info {
	color: {$colors['inverse_text']};
}
.post_content.ih-item.square.effect_border.colored .info:before,
.post_content.ih-item.square.effect_border.colored .info:after {
	border-color: {$colors['inverse_text']};
}

.post_content.ih-item.circle.effect1 .spinner {
	border-right-color: {$colors['text_link']};
	border-bottom-color: {$colors['text_link']};
}
.post_content.ih-item .post_readmore .post_readmore_label,
.post_content.ih-item .info a,
.post_content.ih-item .info a > span {
	color: {$colors['inverse_link']};
}
.post_content.ih-item .post_readmore:hover .post_readmore_label,
.post_content.ih-item .info a:hover,
.post_content.ih-item .info a:hover > span {
	color: {$colors['inverse_hover']};
}

/* Tables */

.sc_table table {
	color: {$colors['text_dark']};
}
.sc_table table tr {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['inverse_dark']};
}
.sc_table table tr:first-child {
	background-color: {$colors['alter_link']};
	color: {$colors['inverse_dark']};
}



/* Table of contents */
pre.code,
#toc .toc_item.current,
#toc .toc_item:hover {
	border-color: {$colors['text_link']};
}


::selection,
::-moz-selection { 
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

/* 3. Form fields settings
-------------------------------------------------------------- */

input[type="tel"],
input[type="text"],
input[type="number"],
input[type="email"],
input[type="search"],
input[type="password"],
select,
textarea {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
input[type="tel"]:focus,
input[type="text"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="search"]:focus,
input[type="password"]:focus,
select:focus,
textarea:focus {
	color: {$colors['input_dark']};
	border-color: {$colors['input_bd_hover']};
	background-color: {$colors['input_bg_hover']};
}
input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
	color: {$colors['input_light']};
}
fieldset {
	border-color: {$colors['bd_color']};
}
fieldset legend {
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}

/* ======================== INPUT'S STYLES ================== */

/* Accent */
.sc_input_hover_accent input[type="text"]:focus,
.sc_input_hover_accent input[type="number"]:focus,
.sc_input_hover_accent input[type="email"]:focus,
.sc_input_hover_accent input[type="password"]:focus,
.sc_input_hover_accent input[type="search"]:focus,
.sc_input_hover_accent select:focus,
.sc_input_hover_accent textarea:focus {
	box-shadow: 0px 0px 0px 2px {$colors['text_link']};
}
.sc_input_hover_accent input[type="text"] + label:before,
.sc_input_hover_accent input[type="number"] + label:before,
.sc_input_hover_accent input[type="email"] + label:before,
.sc_input_hover_accent input[type="password"] + label:before,
.sc_input_hover_accent input[type="search"] + label:before,
.sc_input_hover_accent select + label:before,
.sc_input_hover_accent textarea + label:before {
	color: {$colors['text_link_0_6']};
}

/* Path */
.sc_input_hover_path input[type="text"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="number"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="email"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="password"] + label > .sc_form_graphic,
.sc_input_hover_path input[type="search"] + label > .sc_form_graphic,
.sc_input_hover_path textarea + label > .sc_form_graphic {
	stroke: {$colors['input_bd_color']};
}

/* Jump */
.sc_input_hover_jump .sc_form_label_content:before {
	color: {$colors['inverse_text']};
}
.sc_input_hover_jump input[type="text"],
.sc_input_hover_jump input[type="number"],
.sc_input_hover_jump input[type="email"],
.sc_input_hover_jump input[type="password"],
.sc_input_hover_jump input[type="search"],
.sc_input_hover_jump textarea {
	border-color: {$colors['input_bd_color']};
}
.sc_input_hover_jump input[type="text"]:focus,
.sc_input_hover_jump input[type="number"]:focus,
.sc_input_hover_jump input[type="email"]:focus,
.sc_input_hover_jump input[type="password"]:focus,
.sc_input_hover_jump input[type="search"]:focus,
.sc_input_hover_jump textarea:focus,
.sc_input_hover_jump input[type="text"].filled,
.sc_input_hover_jump input[type="number"].filled,
.sc_input_hover_jump input[type="email"].filled,
.sc_input_hover_jump input[type="password"].filled,
.sc_input_hover_jump input[type="search"].filled,
.sc_input_hover_jump textarea.filled {
	border-color: {$colors['text_link']};
}

/* Underline */
.sc_input_hover_underline input[type="text"] + label:before,
.sc_input_hover_underline input[type="number"] + label:before,
.sc_input_hover_underline input[type="email"] + label:before,
.sc_input_hover_underline input[type="password"] + label:before,
.sc_input_hover_underline input[type="search"] + label:before,
.sc_input_hover_underline textarea + label:before {
	background-color: {$colors['input_bd_color']};
}
.sc_input_hover_jump input[type="text"]:focus + label:before,
.sc_input_hover_jump input[type="number"]:focus + label:before,
.sc_input_hover_jump input[type="email"]:focus + label:before,
.sc_input_hover_jump input[type="password"]:focus + label:before,
.sc_input_hover_jump input[type="search"]:focus + label:before,
.sc_input_hover_jump textarea:focus + label:before,
.sc_input_hover_jump input[type="text"].filled + label:before,
.sc_input_hover_jump input[type="number"].filled + label:before,
.sc_input_hover_jump input[type="email"].filled + label:before,
.sc_input_hover_jump input[type="password"].filled + label:before,
.sc_input_hover_jump input[type="search"].filled + label:before,
.sc_input_hover_jump textarea.filled + label:before {
	background-color: {$colors['input_bd_hover']};
}
.sc_input_hover_underline input[type="text"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="number"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="email"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="password"] + label > .sc_form_label_content,
.sc_input_hover_underline input[type="search"] + label > .sc_form_label_content,
.sc_input_hover_underline textarea + label > .sc_form_label_content {
	color: {$colors['input_text']};
}
.sc_input_hover_underline input[type="text"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="number"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="email"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="password"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="search"]:focus + label > .sc_form_label_content,
.sc_input_hover_underline textarea:focus + label > .sc_form_label_content,
.sc_input_hover_underline input[type="text"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="number"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="email"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="password"].filled + label > .sc_form_label_content,
.sc_input_hover_underline input[type="search"].filled + label > .sc_form_label_content,
.sc_input_hover_underline textarea.filled + label > .sc_form_label_content {
	color: {$colors['input_dark']};
}

/* Iconed */
.sc_input_hover_iconed input[type="text"] + label,
.sc_input_hover_iconed input[type="number"] + label,
.sc_input_hover_iconed input[type="email"] + label,
.sc_input_hover_iconed input[type="password"] + label,
.sc_input_hover_iconed input[type="search"] + label,
.sc_input_hover_iconed textarea + label {
	color: {$colors['input_text']};
}
.sc_input_hover_iconed input[type="text"]:focus + label,
.sc_input_hover_iconed input[type="number"]:focus + label,
.sc_input_hover_iconed input[type="email"]:focus + label,
.sc_input_hover_iconed input[type="password"]:focus + label,
.sc_input_hover_iconed input[type="search"]:focus + label,
.sc_input_hover_iconed textarea:focus + label,
.sc_input_hover_iconed input[type="text"].filled + label,
.sc_input_hover_iconed input[type="number"].filled + label,
.sc_input_hover_iconed input[type="email"].filled + label,
.sc_input_hover_iconed input[type="password"].filled + label,
.sc_input_hover_iconed input[type="search"].filled + label,
.sc_input_hover_iconed textarea.filled + label {
	color: {$colors['input_dark']};
}

/* ======================== END INPUT'S STYLES ================== */



/* 6. Page layouts
-------------------------------------------------------------- */
.body_wrap {
	color: {$colors['text']};
}
.body_style_boxed .body_wrap {
	background-color: {$colors['bg_color']};
}


/* 7. Section's decorations
-------------------------------------------------------------- */

/* If in the Theme options set "Body filled", else - leave this sections transparent */
body:not(.video_bg_show),
body:not(.video_bg_show) .page_wrap,
.copy_wrap,
#page_preloader {
	background-color: {$colors['bg_color']};
}


.article_style_boxed .content > article > .post_content,
.article_style_boxed[class*="single-"] .content > .comments_wrap,
.article_style_boxed[class*="single-"] .content > article > .post_info_share,
.article_style_boxed:not(.layout_excerpt):not(.single) .content .post_item {
	background-color: {$colors['alter_bg_color']};
}


/* 7.1 Top panel
-------------------------------------------------------------- */

.top_panel_wrap_inner {
	background-color: {$colors['bg_color']};
}
.top_panel_fixed .top_panel_position_over.top_panel_wrap_inner {}
.top_panel_middle .sidebar_cart:after,
.top_panel_middle .sidebar_cart {
	background-color: {$colors['bg_color']};
}
.top_panel_fixed .top_panel_middle .sidebar_cart {
	background-color: {$colors['alter_bg_color']};
}
.top_panel_top a {
	color: {$colors['text']};
}
.top_panel_top a:hover {
	color: {$colors['text_hover']};
}


/* User menu */
.menu_user_nav > li > a {
	color: {$colors['text_hover']};
}

.menu_user_nav > li > a:hover {
	color: {$colors['text_link']};
}
.top_panel_over .menu_user_nav > li > a {
	color: {$colors['inverse_text']};
}
.top_panel_over .menu_user_nav > li > a:hover {
	color: {$colors['alter_hover']};
}
.menu_user_nav > li ul {
	background-color: {$colors['alter_hover']};
}

.menu_user_nav > li > ul:after,
.menu_user_nav > li ul {
	color: {$colors['inverse_text']};
	background-color: {$colors['alter_hover']};
}
.menu_user_nav > li ul li a {
	color: {$colors['inverse_text']};
}
.menu_user_nav > li ul li a:hover,
.menu_user_nav > li ul li.current-menu-item > a,
.menu_user_nav > li ul li.current-menu-ancestor > a {
	color: {$colors['alter_dark']};
}

.menu_user_nav > li.menu_user_controls .user_avatar {
	border-color: {$colors['bd_color']};
}


/* Bookmarks */
.menu_user_nav > li.menu_user_bookmarks .bookmarks_add {
	border-bottom-color: {$colors['alter_bd_color']};
}


/* Top panel - middle area */
.top_panel_position_over.top_panel_middle {
	background-color: {$colors['alter_bg_color_0_8']};
}
.logo .logo_text {
	color: {$colors['text_dark']};
}
.logo .logo_slogan {
	color: {$colors['text']};
}


/* Top panel (bottom area) */
.top_panel_bottom {
	background-color: {$colors['text_link']};
}



/* Top panel image in the header 7  */
.top_panel_image_header,
.top_panel_over:not(.top_panel_fixed) .top_panel_middle .contact_field,
.top_panel_over:not(.top_panel_fixed) .contact_field .top_panel_cart_button,
.top_panel_over:not(.top_panel_fixed) .logo_text,
.top_panel_over:not(.top_panel_fixed) .logo_slogan {
	color: {$colors['inverse_text']};
}
.header_mobile .contact_link a,
.top_panel_over:not(.top_panel_fixed) .contact_link a:hover {
	color: {$colors['inverse_text']};
	border-color: {$colors['inverse_text']};
}

.top_panel_image .breadcrumbs a:hover,
.top_panel_image_title {
	color: {$colors['alter_hover']};
}

.top_panel_image_header a,
.top_panel_image_title > a,
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > li > a {
	color: {$colors['inverse_link']};
}
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > a:hover,
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > li > a:hover,
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > li.sfHover > a,
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > li.current-menu-item > a,
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > li.current-menu-parent > a,
.top_panel_over:not(.top_panel_fixed) .menu_main_nav > li.current-menu-ancestor > a {
	color: {$colors['alter_hover']};
}


/* Main menu */
.menu_main_nav > li > a {
	color: {$colors['alter_dark']};
}
.menu_main_nav > li ul {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_hover']};
}
.menu_main_nav > a:hover,
.menu_main_nav > li > a:hover,
.menu_main_nav > li.sfHover > a,
.menu_main_nav > li.current-menu-item > a,
.menu_main_nav > li.current-menu-parent > a,
.menu_main_nav > li.current-menu-ancestor > a {
	color: {$colors['alter_hover']};
}
.menu_main_nav > li ul li a {
	color: {$colors['inverse_link']};
}
.menu_main_nav > li ul li a:hover,
.menu_main_nav > li ul li.current-menu-item > a,
.menu_main_nav > li ul li.current-menu-ancestor > a {
	color: {$colors['text_hover']};
}


/* ---------------------- MENU HOVERS ----------------------- */



/* slide_box */
.menu_hover_slide_box .menu_main_nav > li#blob {
	background-color: {$colors['alter_bg_hover']};
}


/* slide_line */
.menu_hover_slide_line .menu_main_nav > li#blob {
	background-color: {$colors['alter_hover']};
}


/* zoom_line */
.menu_hover_zoom_line .menu_main_nav > li > a:before {
	background-color: {$colors['alter_hover']};
}


/* path_line */
.menu_hover_path_line .menu_main_nav > li:before,
.menu_hover_path_line .menu_main_nav > li:after,
.menu_hover_path_line .menu_main_nav > li > a:before,
.menu_hover_path_line .menu_main_nav > li > a:after {
	background-color: {$colors['alter_hover']};
}


/* roll_down */
.menu_hover_roll_down .menu_main_nav > li > a:before {
	background-color: {$colors['alter_hover']};
}


/* color_line */
.menu_hover_color_line .menu_main_nav > li > a:hover,
.menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['alter_dark']};
}

.menu_hover_color_line .menu_main_nav > li > a:before {
	background-color: {$colors['alter_dark']};
}
.menu_hover_color_line .menu_main_nav > li > a:after {
	background-color: {$colors['alter_hover']};
}
.menu_hover_color_line .menu_main_nav > li.sfHover > a,
.menu_hover_color_line .menu_main_nav > li > a:hover,
.menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['alter_hover']};
}


/* ---------------------- END MENU HOVERS ----------------------- */

/* Contact fields */
.top_panel_middle .contact_field,
.top_panel_middle .contact_field .top_panel_cart_button {
	color: {$colors['text_dark']}; 
}
.top_panel_middle .contact_field .top_panel_cart_button:hover {
	color: {$colors['alter_hover']};
}
.top_panel_middle .contact_icon {
	color: {$colors['alter_hover']};
}

/* Search field */
.content .search_field {
	background-color: {$colors['alter_bg_color']}; 
}
.content .search_field,
.content .search_submit {
	color: {$colors['alter_text']};
}
.content .search_field:focus,
.content .search_submit:hover {
	color: {$colors['alter_dark']};
}

.top_panel_icon.search_wrap {
	background-color: {$colors['bg_color']};
	color: {$colors['text_link']};
}
.top_panel_icon .contact_icon,
.top_panel_icon .search_submit {
	color: {$colors['text_link']};
}



.search_style_fullscreen.search_state_closed:not(.top_panel_icon) .search_submit,
.search_style_slide.search_state_closed:not(.top_panel_icon) .search_submit {
	color: {$colors['inverse_text']};
}
.search_style_expand.search_state_opened:not(.top_panel_icon) .search_submit:hover,
.search_style_slide.search_state_opened:not(.top_panel_icon) .search_submit:hover {
	color: {$colors['input_dark']};
}




/* Search results */
.search_results .post_more,
.search_results .search_results_close {
	color: {$colors['text_link']};
}
.search_results .post_more:hover,
.search_results .search_results_close:hover {
	color: {$colors['text_hover']};
}


.pushy_inner {
	color: {$colors['text']}; 
	background-color: {$colors['bg_color']}; 
}
.pushy_inner a {
	color: {$colors['text_link']}; 
}
.pushy_inner a:hover {
	color: {$colors['text_hover']}; 
}
.pushy_inner ul ul {
	background-color: {$colors['alter_bg_color_0_8']}; 
}


/* Header mobile */
.header_mobile .menu_button,
.header_mobile .menu_main_cart .top_panel_cart_button .contact_icon {
	color: {$colors['text_dark']};
}

.top_panel_over .header_mobile .menu_button,
.top_panel_over .header_mobile .menu_main_cart .top_panel_cart_button .contact_icon {
	color: {$colors['inverse_text']};
}

.header_mobile .side_wrap {
	color: {$colors['inverse_text']};
}
.header_mobile .panel_top,
.header_mobile .side_wrap {
	background-color: {$colors['text_link']};
}
.header_mobile .panel_middle {
	background-color: {$colors['text_link']};
}
.header_mobile .panel_bottom {
	background-color: {$colors['text_hover']};
}

.header_mobile .menu_button:hover,
.header_mobile .menu_main_cart .top_panel_cart_button .contact_icon:hover,
.header_mobile .menu_main_cart.top_panel_icon:hover .top_panel_cart_button .contact_icon,
.header_mobile .side_wrap .close:hover{
	color: {$colors['alter_hover']};
}

.header_mobile .menu_main_nav > li a,
.header_mobile .menu_main_nav > li > a:hover {
	color: {$colors['inverse_link']};
}
.header_mobile .menu_main_nav > a:hover, 
.header_mobile .menu_main_nav > li.sfHover > a, 
.header_mobile .menu_main_nav > li.current-menu-item > a, 
.header_mobile .menu_main_nav > li.current-menu-parent > a, 
.header_mobile .menu_main_nav > li.current-menu-ancestor > a,
.header_mobile .menu_main_nav > li > a:hover,
.header_mobile .menu_main_nav > li ul li a:hover, 
.header_mobile .menu_main_nav > li ul li.current-menu-item > a, 
.header_mobile .menu_main_nav > li ul li.current-menu-ancestor > a,
.header_mobile .login a:hover {
	color: {$colors['inverse_hover']};
}
.header_mobile .popup_wrap .popup_close:hover {
	color: {$colors['text_dark']};
}

.header_mobile .search_wrap,
.header_mobile .login {
	border-color: {$colors['text_link']};
}
.header_mobile .login .popup_link,
.header_mobile .sc_socials.sc_socials_type_icons a {
	color: {$colors['inverse_link']};
}

.header_mobile .search_wrap .search_field,
.header_mobile .search_wrap .search_field:focus {  
	color: {$colors['inverse_text']};
}

.header_mobile .widget_shopping_cart ul.cart_list > li > a:hover {
	color: {$colors['text_link']};
}

.header_mobile .popup_wrap .sc_socials.sc_socials_type_icons a {
	color: {$colors['text_light']};
}




/* 7.2 Main Slider
-------------------------------------------------------------- */
.tparrows.default {
	color: {$colors['bg_color']};
}
.tp-bullets.simplebullets.round .bullet {
	background-color: {$colors['bg_color']};
}
.tp-bullets.simplebullets.round .bullet.selected {
	border-color: {$colors['bg_color']};
}
.slider_over_content_inner {
	background-color: {$colors['bg_color_0_8']};
}
.slider_over_button {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color_0_8']};
}
.slider_over_close {
	color: {$colors['text_dark']};
}



/* 7.3 Top panel: Page title and breadcrumbs
-------------------------------------------------------------- */

.top_panel_title_inner {
	background-color: {$colors['alter_bg_color']};
}

.top_panel_title_inner .page_title {
	color: {$colors['alter_dark']};
}

.top_panel_title_inner .post_navi .post_navi_item a,
.top_panel_title_inner .breadcrumbs a.breadcrumbs_item {
	color: {$colors['alter_dark']};
}
.top_panel_title_inner .post_navi .post_navi_item a:hover,
.top_panel_title_inner .breadcrumbs a.breadcrumbs_item:hover {
	color: {$colors['alter_light']};
}
.top_panel_title_inner .post_navi span,
.top_panel_title_inner .breadcrumbs span {
	color: {$colors['alter_light']};
}
.post_navi .post_navi_item + .post_navi_item:before,
.top_panel_title_inner .breadcrumbs .breadcrumbs_delimiter {
	color: {$colors['alter_light']};
}





/* 7.4 Main content wrapper
-------------------------------------------------------------- */

/* Blog pagination */
.pagination > a {
	border-color: {$colors['text_link']};
}



/* 7.5 Post formats
-------------------------------------------------------------- */

/* Aside */
.post_format_aside.post_item_single .post_content p,
.post_format_aside .post_descr {
	border-color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}




/* 7.6 Posts layouts
-------------------------------------------------------------- */

/* Hover icon */
.hover_icon:before {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}


/* Post info */
.post_info a,
.post_info a > span {
	color: {$colors['text']};
}


.post_info a.post_info_date {
	color: {$colors['alter_link']};
}


.post_info a[class*="icon-"] {
	color: {$colors['text_link']};
}
.post_info a:hover,
.post_info a:hover > span {
	color: {$colors['text_link']};
}

.post_item .post_readmore_label {
	color: {$colors['text_dark']};
}
.post_item .post_readmore:hover .post_readmore_label {
	color: {$colors['text_hover']};
}

/* Related posts */
.post_item_related .post_info a {
	color: {$colors['text']};
}
.related_wrap .post_item_related,
.article_style_stretch .post_item_related {
	background-color: {$colors['alter_bg_color']};
}
.article_style_boxed.sidebar_show[class*="single-"] .related_wrap .post_item_related {
	background-color: {$colors['alter_bg_color']};
}



.post_item_related .post_info_date {
	background-color: {$colors['alter_link']};
	color: {$colors['inverse_text']};
}
.post_item_related .link {
	color: {$colors['alter_link']};
}
.post_item_related .link:hover {
	color: {$colors['text_link']};
}



/* Masonry and Portfolio */
.isotope_wrap .isotope_item_colored_1 .post_featured {
	border-color: {$colors['text_link']};
}

/* Isotope filters */
.isotope_filters a {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.isotope_filters a.active,
.isotope_filters a:hover {
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}




/* 7.7 Paginations
-------------------------------------------------------------- */

/* Style 'Pages' and 'Slider' */
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_pages > a,
.pagination_pages > span {
	background-color: {$colors['input_bd_hover']};
	color: {$colors['text']};
}
.pagination_single > .pager_numbers,
.pagination_single a:hover,
.pagination_slider .pager_cur:hover,
.pagination_slider .pager_cur:focus,
.pagination_pages > .active,
.pagination_pages > a:hover {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.pagination_wrap .pager_next,
.pagination_wrap .pager_prev,
.pagination_wrap .pager_last,
.pagination_wrap .pager_first {
	background-color: {$colors['input_bd_hover']};
	color: {$colors['text']};
}

.pagination_wrap .pager_next:hover,
.pagination_wrap .pager_prev:hover,
.pagination_wrap .pager_last:hover,
.pagination_wrap .pager_first:hover {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.pagination_slider .pager_slider {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
}




/* Style 'Load more' */
.pagination_viewmore > a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.pagination_viewmore > a:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}

/* Loader picture */
.viewmore_loader,
.mfp-preloader span,
.sc_video_frame.sc_video_active:before {
	background-color: {$colors['text_hover']};
}



/* 8 Single page parts
-------------------------------------------------------------- */


/* 8.1 Attachment and Portfolio post navigation
------------------------------------------------------------- */
.post_featured .post_nav_item {
	color: {$colors['inverse_text']};
}
.post_featured .post_nav_item:before {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.post_featured .post_nav_item .post_nav_info {
	background-color: {$colors['text_link']};
}


/* 8.2 Reviews block
-------------------------------------------------------------- */
.reviews_block .reviews_summary .reviews_item {
	background-color: {$colors['text_link']};
}
.reviews_block .reviews_summary,
.reviews_block .reviews_max_level_100 .reviews_stars_bg {
	background-color: {$colors['alter_bg_hover']};
}
.reviews_block .reviews_max_level_100 .reviews_stars_hover,
.reviews_block .reviews_item .reviews_slider {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.reviews_block .reviews_item .reviews_stars_hover {
	color: {$colors['text_link']};
}
.reviews_block .reviews_value {
	color: {$colors['text_dark']};
}
.reviews_block .reviews_summary .reviews_criteria {
	color: {$colors['text']};
}
.reviews_block .reviews_summary .reviews_value {
	color: {$colors['inverse_text']};
}

/* Summary stars in the post item (under the title) */
.post_item .post_rating .reviews_stars_bg,
.post_item .post_rating .reviews_stars_hover,
.post_item .post_rating .reviews_value {
	color: {$colors['text_link']};
}


/* 8.3 Post author
-------------------------------------------------------------- */
.post_author {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['alter_dark']};
}
.post_author .post_author_title {
	color: {$colors['alter_dark']};
}
.post_author .post_author_title a {
	color: {$colors['text_hover']}; 
}
.post_author .post_author_title a:hover {
	color: {$colors['text_link']};
}
.post_author .post_author_info .sc_socials_shape_square a {
	color: {$colors['alter_dark']};
}
.post_author .post_author_info .sc_socials_shape_square a:hover {
	color: {$colors['text_hover']};
}

.post_author .post_author_info .link {
	{$fonts['h1_font-family']}
}


/* 8.4 Comments
-------------------------------------------------------- */
.comments_list_wrap ul.children,
.comments_list_wrap ul > li + li {
	border-top-color: {$colors['bd_color']};
}
.comments_list_wrap .comment-respond {
	border-bottom-color: {$colors['bd_color']};
}
.comments_list_wrap > ul {
	border-bottom-color: {$colors['bd_color']};
}
.comments_list_wrap .comment_info > span.comment_author {
	color: {$colors['text_dark']};
}



/* 8.5 Page 404
-------------------------------------------------------------- */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	color: {$colors['text_link']};
}




/* 9. Sidebars
-------------------------------------------------------------- */

.widget_area .post_item .post_title {
	{$fonts['p_font-family']}
}

.sidebar_cart,
.widget_area_inner {
	background-color: {$colors['alter_bg_color']};
}
.footer_wrap_inner.widget_area_inner {
	background-color: {$colors['bg_color']};
}

/* Common rules */
.sidebar_inner aside:nth-child(3n+4),
.sidebar_inner aside:nth-child(3n+5),
.sidebar_inner aside:nth-child(3n+6),
.sidebar_outer_inner aside:nth-child(3n+4),
.sidebar_outer_inner aside:nth-child(3n+5),
.sidebar_outer_inner aside:nth-child(3n+6),
.widget_area_inner aside:nth-child(2n+3),
.widget_area_inner aside:nth-child(2n+4),
.widget_area_inner aside+aside {
	border-color: {$colors['bd_color']};
}
.widget_area_inner {
	color: {$colors['text_dark']};
}
.ui-datepicker .ui-datepicker-title,
.widget_area_inner .post_info,
.widget_area_inner ul,
.widget_area_inner table {
	color: {$colors['text']};
}

.widget_area_inner a,
.widget_area_inner ul li:before,
.widget_area_inner ul li a:hover {
	color: {$colors['text_link']};
}
.wp-block-search .wp-block-search__button:before,
.widget_area_inner button:before {
	color: {$colors['text_light']};
}
.wp-block-search:hover .wp-block-search__button:before,
.widget_area_inner button:hover:before {
	color: {$colors['text_link']};
}
.widget_area_inner a:hover,
.widget_area_inner ul li a {
	color: {$colors['text_hover']};
}
.widget_area_inner .post_title a {
	color: {$colors['text_dark']};
}
.widget_area_inner .post_title a:hover {
	color: {$colors['text_link']};
}
.widget_area_inner .widget_text a:not(.sc_button) {
	color: {$colors['text_link']};
}
.widget_area_inner .post_info a {
	color: {$colors['alter_link']};
}
.widget_area_inner .widget_text a:not(.sc_button):hover,
.widget_area_inner .post_info a:hover {
	color: {$colors['text_hover']};
}
.widget_area_inner .comment-author-link {
	color: {$colors['text_hover']};
}

/* Widget: Search */
.widget_area_inner .widget_product_search .search_form,
.widget_area_inner .widget_search .search_form {
	background-color: {$colors['bg_color']};
}
.widget_area_inner .widget_product_search .search_field,
.widget_area_inner .widget_search .search_field {
	color: {$colors['alter_text']};
}
.widget_area_inner .widget_product_search .search_button,
.widget_area_inner .widget_search .search_button {
	color: {$colors['alter_text']};
}
.widget_area_inner .widget_product_search .search_button:hover,
.widget_area_inner .widget_search .search_button:hover {
	color: {$colors['alter_dark']};
}

.widget_area_inner .widget_product_search input:focus,
.widget_area_inner .widget_search input:focus {
	color: {$colors['text_dark']};
}




/* Widget: Calendar */
.wp-block-calendar .weekday,
.widget_area_inner .widget_calendar .weekday {
	color: {$colors['text_dark']};
}
.wp-block-calendar td a:hover,
.widget_area_inner .widget_calendar td a:hover {
	color: {$colors['text_link']};
}
.wp-block-calendar .today .day_wrap,
.widget_area_inner .widget_calendar .today .day_wrap {
	color: {$colors['inverse_text']};
}

.wp-block-calendar .today .day_wrap:before,
.widget_area_inner .widget_calendar .today .day_wrap:before {
	background-color: {$colors['text_link']};
}


/* Product Delivery Date for WooCommerce - Lite */

#ui-datepicker-div .ui-datepicker-today a {
	background: {$colors['text_link']}!important;
	color: {$colors['inverse_text']};
}
#ui-datepicker-div .ui-datepicker-today a:hover{
 color: {$colors['text_hover']};
}

#ui-datepicker-div .ui-widget-header a{
    color: {$colors['text_link']};
}

#ui-datepicker-div .ui-widget-header a:hover{
    color: {$colors['text_hover']};
}
#ui-datepicker-div  thead th{
	color: {$colors['text_dark']};
}
#ui-datepicker-div .ui-state-default{
    color: {$colors['text']};
}
#ui-datepicker-div .ui-state-default:hover{
    color: {$colors['text_link']};
}

#ui-datepicker-div {
	background: {$colors['bg_color']}!important;
}

/* Widget: Tag Cloud */
.wp-block-tag-cloud a,
.widget_area_inner .widget_product_tag_cloud a,
.widget_area_inner .widget_tag_cloud a {
	background-color: {$colors['input_bd_hover']};
	color: {$colors['text_dark']};
}
.wp-block-tag-cloud a:not([class*="sc_button_hover_"]):hover,
.widget_area_inner .widget_product_tag_cloud a:not([class*="sc_button_hover_"]):hover,
.widget_area_inner .widget_tag_cloud a:not([class*="sc_button_hover_"]):hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}


/* Left or Right sidebar */
.sidebar_outer_inner aside,
.sidebar_inner aside {
	border-top-color: {$colors['bd_color']};
}


/* 10. Footer areas
-------------------------------------------------------------- */

/* Contacts */
.contacts_copyright_wrap {
	color: {$colors['inverse_text']};
	background-color: {$colors['bg_color']};
}

/* Testimonials and Twitter */
.testimonials_wrap_inner,
.twitter_wrap_inner {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}

/* Copyright */
.copyright_wrap_inner .copyright_text {
	color: {$colors['inverse_text']};
}
.copyright_wrap_inner .copyright_text a,
.copyright_wrap_inner .menu_footer_nav li a {
	color: {$colors['alter_hover']};
}
.copyright_wrap_inner .copyright_text a:hover,
.copyright_wrap_inner .menu_footer_nav li a:hover {
	color: {$colors['inverse_text']};
}




/* 11. Utils
-------------------------------------------------------------- */

/* Scroll to top */
.scroll_to_top {
	color: {$colors['alter_hover']};
	{$fonts['h1_font-family']}
}
.scroll_to_top:hover {
	color: {$colors['alter_hover']};
}

/* Preloader */
#page_preloader {
	background-color: {$colors['bg_color']};
}
.preloader_wrap > div {
	background-color: {$colors['text_link']};
}

/* Gallery preview */
.scheme_self.gallery_preview:before {
	background-color: {$colors['bg_color']};
}

/* 12. Registration and Login popups
-------------------------------------------------------------- */
.popup_wrap {
	background-color: {$colors['bg_color']};
}




/* 13. Third party plugins
------------------------------------------------------- */

/* 13.2 WooCommerce
------------------------------------------------------ */

.top_panel_wrap .widget_shopping_cart ul.cart_list > li {
	border-color: {$colors['bd_color']};
}

.woocommerce ul.cart_list li a, .woocommerce ul.product_list_widget li a, .woocommerce-page ul.cart_list li a, .woocommerce-page ul.product_list_widget li a {
	{$fonts['h1_font-family']}
	color: {$colors['text_hover']};
}
.woocommerce ul.cart_list li a:hover, .woocommerce ul.product_list_widget li a:hover, .woocommerce-page ul.cart_list li a:hover, .woocommerce-page ul.product_list_widget li a:hover {
	color: {$colors['text_link']};
}

/* Theme colors */

.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,
.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price,
.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,
.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,
.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,
.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,
.woocommerce a:hover h3, .woocommerce-page a:hover h3,
.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,
.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,
.widget_area_inner .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a
{
	color: {$colors['text_link']};
}
.woocommerce div.quantity span, .woocommerce-page div.quantity span {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.woocommerce div.quantity span:hover, .woocommerce-page div.quantity span:hover {
	background-color: {$colors['text_hover']};
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
	background-color: {$colors['text_link']};
}


.woocommerce.widget_shopping_cart .quantity, .woocommerce .widget_shopping_cart .quantity, .woocommerce-page.widget_shopping_cart .quantity, .woocommerce-page .widget_shopping_cart .quantity {
	color: {$colors['text_link']};
}

.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,
.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,
.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active
{ 
	border-top-color: {$colors['text_link']};
}

/* Buttons */
#btn-buy,
.woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
.woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, 
.woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, 
.woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, 
.woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, 
.woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce-account .addresses .title .edit,
.woocommerce ul.products li.product .add_to_cart_button, .woocommerce-page ul.products li.product .add_to_cart_button {
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
	{$fonts['button_font-family']}
}
#btn-buy:hover,
.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,
.woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, 
.woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover,
.woocommerce-page #content input.button:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,
.woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover,
.woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce-account .addresses .title .edit:hover,
.woocommerce ul.products li.product .add_to_cart_button:hover, .woocommerce-page ul.products li.product .add_to_cart_button:hover {
	border-color: {$colors['text_link']};
	color: {$colors['text_link']};
}

.post-type-archive-product.woocommerce ul.products li.product .button {
	background-color: {$colors['alter_link']};
	border-color: {$colors['alter_link']};
	color: {$colors['inverse_text']};
}
.post-type-archive-product.woocommerce ul.products li.product .button:hover {
	border-color: {$colors['alter_link']};
	background-color: {$colors['inverse_text']};
	color: {$colors['alter_link']};
}


.top_panel_wrap .widget_shopping_cart .buttons a:first-child {
	background-color: {$colors['alter_hover']};
	border-color: {$colors['alter_hover']};;
}
.top_panel_wrap .widget_shopping_cart .buttons a:first-child:hover {
	color: {$colors['alter_hover']};
	border-color: {$colors['alter_hover']};;
}


/* Messages */
.article_style_boxed.woocommerce .woocommerce-error, .article_style_boxed.woocommerce .woocommerce-info, .article_style_boxed.woocommerce .woocommerce-message,
.article_style_boxed.woocommerce-page .woocommerce-error, .article_style_boxed.woocommerce-page .woocommerce-info, .article_style_boxed.woocommerce-page .woocommerce-message {
	background-color: {$colors['alter_bg_color']};
}
.article_style_boxed.woocommerce.archive .woocommerce-error, .article_style_boxed.woocommerce.archive .woocommerce-info, .article_style_boxed.woocommerce.archive .woocommerce-message,
.article_style_boxed.woocommerce-page.archive .woocommerce-error, .article_style_boxed.woocommerce-page.archive .woocommerce-info, .article_style_boxed.woocommerce-page.archive .woocommerce-message {
	background-color: {$colors['alter_bg_color']};
}

/* Products stream */
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale {
	background-color: {$colors['alter_hover']};
	color: {$colors['inverse_text']};
}
.article_style_boxed.woocommerce ul.products li.product .post_item_wrap, .article_style_boxed.woocommerce-page ul.products li.product .post_item_wrap {
	background-color: {$colors['alter_bg_color']};
}

.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .star-rating:before, .woocommerce ul.products li.product .star-rating span {
	color: {$colors['text_link']};
}
.woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del {
	color: {$colors['text_light']};
}

.scheme_self.article_style_boxed.woocommerce ul.products li.product .post_item_wrap {
	background-color: {$colors['alter_bg_hover']};
}
.scheme_self.article_style_boxed.woocommerce-page ul.products li.product .post_item_wrap {
	background-color: {$colors['alter_bg_hover']};
}
.scheme_self.article_style_boxed.woocommerce ul.products li.product .post_content {
	background-color: {$colors['alter_bg_color']};
}
.scheme_self.article_style_boxed.woocommerce-page ul.products li.product .post_content {
	background-color: {$colors['alter_bg_color']};
}

/* Pagination */
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}

/* Tabs */
.woocommerce div.product .woocommerce-tabs .panel, .woocommerce #content div.product .woocommerce-tabs .panel, .woocommerce-page div.product .woocommerce-tabs .panel, .woocommerce-page #content div.product .woocommerce-tabs .panel {
	border-color: {$colors['bd_color']};
}

/* Tabs on single product page */
.scheme_self.woocommerce-tabs.trx-stretch-width {
	background-color: {$colors['bg_color']};
}
.single-product div.product .woocommerce-tabs.trx-stretch-width .wc-tabs li a {
	color: {$colors['text']};
}
.single-product div.product .woocommerce-tabs.trx-stretch-width .wc-tabs li a:hover,
.single-product div.product .woocommerce-tabs.trx-stretch-width .wc-tabs li.active a {
	color: {$colors['text_dark']};
}
.single-product div.product .woocommerce-tabs.trx-stretch-width .wc-tabs li.active a:after {
	background-color: {$colors['text_link']};
}

/* Cart */
.woocommerce table.cart thead th, .woocommerce #content table.cart thead th, .woocommerce-page table.cart thead th, .woocommerce-page #content table.cart thead th {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

/* My Account */
.woocommerce-account .woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-navigation li+li {
	border-color: {$colors['bd_color']};
}
.woocommerce-MyAccount-navigation li.is-active a {
	color: {$colors['text_dark']};
}

/* Widgets */
.top_panel_wrap .widget_shopping_cart ul.cart_list > li > a:hover {
	color: {$colors['text_link']}; 
}

/* Widget Product Categories */
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li+li {
	border-color: {$colors['alter_bd_color']};
}
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li,
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li > a {
	color: {$colors['text']};
}
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li:hover,
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li:hover > a,
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories li > a:hover {
	color: {$colors['text_dark']};
}
body:not(.woocommerce) .widget_area:not(.footer_wrap) .widget_product_categories ul.product-categories ul {
	background-color: {$colors['alter_bg_color']};
}


/* 13.3 Tribe Events
------------------------------------------------------- */

.tribe-event-schedule-details {
	color: {$colors['alter_link']};	
}
.tribe-events-calendar thead th {
	background-color: {$colors['text_link']};
}
.tribe-events-day .tribe-events-day-time-slot h5 {
	color: {$colors['alter_link']};	
}
.tribe-events-day .tribe-events-day-time-slot h5,
.tribe-events-list-separator-month {
	background-color: {$colors['alter_bg_color']};	
}
.tribe-events-list-separator-month span {
	color: {$colors['alter_link']};	
}

.tribe-events .datepicker .year.active,
.tribe-events .tribe-events-calendar-month__mobile-events-icon--event,
.tribe-common .tribe-common-c-loader__dot,
.tribe-events .datepicker .day.active,
.tribe-events .datepicker .month.active,
.tribe-events .tribe-events-calendar-month__multiday-event-bar-inner,
.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"],
.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"] > a {
	background-color: {$colors['text_link']};
}

.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link,
.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date,
.tribe-events-calendar div[id*="tribe-events-daynum-"], 
.tribe-events-calendar div[id*="tribe-events-daynum-"] a {
	color: {$colors['text_link']};
}
.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link:hover{
	color: {$colors['text_dark']};
}

#tribe-bar-views-toggle,
#tribe-bar-views .tribe-bar-views-list {
	background-color: {$colors['alter_hover']};
	border-color: {$colors['alter_hover']};
	color: {$colors['inverse_text']};
}
#tribe-bar-views .tribe-bar-views-list li{
background-color: {$colors['alter_hover']};
}
#tribe-bar-views .tribe-bar-views-list li:hover{
    color: {$colors['inverse_text']};
}

/* Buttons */
.tribe-common .tribe-common-c-btn,
.tribe-common .tribe-events-c-ical__link,
#tribe-bar-form .tribe-bar-submit input[type="submit"] {
	background-color: {$colors['alter_hover']};
	border-color: {$colors['alter_hover']};
	color: {$colors['inverse_text']};
}
.tribe-common .tribe-common-c-btn:hover,
.tribe-common .tribe-events-c-ical__link:hover,
#tribe-bar-form .tribe-bar-submit input[type="submit"]:hover {
	border-color: {$colors['alter_hover']};
	color: {$colors['alter_hover']};
}

.tribe-events .tribe-events-calendar-month__multiday-event-bar-title{
    color: {$colors['inverse_text']};
}
.tribe-events-button, 
#tribe-events .tribe-events-button,
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a {
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.tribe-events-button:hover, 
#tribe-events .tribe-events-button:hover,
a.tribe-events-read-more:hover,
.tribe-events-button:hover,
.tribe-events-nav-previous a:hover,
.tribe-events-nav-next a:hover,
.tribe-events-widget-link a:hover,
.tribe-events-viewmore a:hover {
	border-color: {$colors['text_link']};
	color: {$colors['text_link']};
}




/* 13.4 BB Press and Buddy Press
------------------------------------------------------- */

/* Buttons */
#bbpress-forums div.bbp-topic-content a,
#buddypress button, #buddypress a.button, #buddypress input[type="submit"], #buddypress input[type="button"], #buddypress input[type="reset"], #buddypress ul.button-nav li a, #buddypress div.generic-button a, #buddypress .comment-reply-link, a.bp-title-button, #buddypress div.item-list-tabs ul li.selected a,
#buddypress .acomment-options a {
	background: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
#bbpress-forums div.bbp-topic-content a:hover,
#buddypress button:hover, #buddypress a.button:hover, #buddypress input[type="submit"]:hover, #buddypress input[type="button"]:hover, #buddypress input[type="reset"]:hover, #buddypress ul.button-nav li a:hover, #buddypress div.generic-button a:hover, #buddypress .comment-reply-link:hover, a.bp-title-button:hover, #buddypress div.item-list-tabs ul li.selected a:hover,
#buddypress .acomment-options a:hover {
	background: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}
#buddypress #item-nav,
#buddypress div#subnav.item-list-tabs,
#buddypress div.item-list-tabs {
	background-color: {$colors['alter_bg_color']};
}
#buddypress #item-nav li:not(.selected) a,
#buddypress div#subnav.item-list-tabs li:not(.selected) a,
#buddypress div.item-list-tabs li:not(.selected) a {
	color: {$colors['alter_text']};
}
#buddypress #item-nav li:not(.selected) a:hover,
#buddypress div#subnav.item-list-tabs li:not(.selected) a:hover,
#buddypress div.item-list-tabs li:not(.selected) a:hover {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}
#buddypress .dir-search input[type="search"], #buddypress .dir-search input[type="text"], #buddypress .groups-members-search input[type="search"], #buddypress .groups-members-search input[type="text"], #buddypress .standard-form input[type="color"], #buddypress .standard-form input[type="date"], #buddypress .standard-form input[type="datetime-local"], #buddypress .standard-form input[type="datetime"], #buddypress .standard-form input[type="email"], #buddypress .standard-form input[type="month"], #buddypress .standard-form input[type="number"], #buddypress .standard-form input[type="password"], #buddypress .standard-form input[type="range"], #buddypress .standard-form input[type="search"], #buddypress .standard-form input[type="tel"], #buddypress .standard-form input[type="text"], #buddypress .standard-form input[type="time"], #buddypress .standard-form input[type="url"], #buddypress .standard-form input[type="week"], #buddypress .standard-form select, #buddypress .standard-form textarea,
#buddypress form#whats-new-form textarea {
	color: {$colors['input_text']};
	background-color: {$colors['input_bg_color']};
	border-color: {$colors['input_bd_color']};
}
#buddypress .dir-search input[type="search"]:focus, #buddypress .dir-search input[type="text"]:focus, #buddypress .groups-members-search input[type="search"]:focus, #buddypress .groups-members-search input[type="text"]:focus, #buddypress .standard-form input[type="color"]:focus, #buddypress .standard-form input[type="date"]:focus, #buddypress .standard-form input[type="datetime-local"]:focus, #buddypress .standard-form input[type="datetime"]:focus, #buddypress .standard-form input[type="email"]:focus, #buddypress .standard-form input[type="month"]:focus, #buddypress .standard-form input[type="number"]:focus, #buddypress .standard-form input[type="password"]:focus, #buddypress .standard-form input[type="range"]:focus, #buddypress .standard-form input[type="search"]:focus, #buddypress .standard-form input[type="tel"]:focus, #buddypress .standard-form input[type="text"]:focus, #buddypress .standard-form input[type="time"]:focus, #buddypress .standard-form input[type="url"]:focus, #buddypress .standard-form input[type="week"]:focus, #buddypress .standard-form select:focus, #buddypress .standard-form textarea:focus,
#buddypress form#whats-new-form textarea:focus {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']};
	border-color: {$colors['input_bd_hover']};
}

#buddypress #reply-title small a span, #buddypress a.bp-primary-action span {
	color: {$colors['text_link']};
	background-color: {$colors['inverse_text']};
}

#buddypress .activity .activity-item:nth-child(2n+1) {
	background-color: {$colors['alter_bg_color']};
}


/* 13.5 WPBakery PageBuilder
------------------------------------------------------ */
.scheme_self.vc_row {
	background-color: {$colors['bg_color']};
}


/* 13.6 Booking Calendar
------------------------------------------------------ */
.booking_month_container_custom,
.booking_month_navigation_button_custom {
	background-color: {$colors['alter_bg_color']} !important;
}
.booking_month_name_custom,
.booking_month_navigation_button_custom {
	color: {$colors['alter_dark']} !important;
}
.booking_month_navigation_button_custom:hover {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_hover']} !important;
}


/* 13.6 LearnDash LMS
------------------------------------------------------ */
#learndash_next_prev_link > a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
#learndash_next_prev_link > a:hover {
	background-color: {$colors['text_hover']};
}
.widget_area dd.course_progress div.course_progress_blue {
	background-color: {$colors['text_hover']};
}


/* 13.7 HTML5 Audio Player
------------------------------------------------------- */
#myplayer .ttw-music-player .progress-wrapper {
	background-color: {$colors['alter_bg_hover']};
}
#myplayer .ttw-music-player .tracklist li.track {
	border-color: {$colors['bd_color']};
}
#myplayer .ttw-music-player .tracklist,
#myplayer .ttw-music-player .buy,
#myplayer .ttw-music-player .description,
#myplayer .ttw-music-player .artist,
#myplayer .ttw-music-player .artist-outer {
	color: {$colors['text']};
}
#myplayer .ttw-music-player .player .title,
#myplayer .ttw-music-player .tracklist li:hover {
	color: {$colors['text_dark']};
}




/* 15. Shortcodes
-------------------------------------------------------------- */

/* Accordion */
.sc_accordion .sc_accordion_item .sc_accordion_title {
	border-color: {$colors['bd_color']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title .sc_accordion_icon {
	color: {$colors['alter_light']};
	background-color: {$colors['alter_bg_color']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon_opened {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title:hover {
	color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_accordion .sc_accordion_item .sc_accordion_title:hover .sc_accordion_icon_opened {
	background-color: {$colors['text_hover']};
}
.sc_accordion .sc_accordion_item .sc_accordion_content {
	border-color: {$colors['bd_color']};
}


/* Audio */

/* Standard style */

.sc_audio .sc_audio_title {
	color: {$colors['inverse_text']};
}



/* Modern style */
.sc_audio.sc_audio_info {
	border-color: {$colors['alter_bg_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_audio .sc_audio_author_name {
	color: {$colors['alter_hover']};
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
.mejs-controls .mejs-volume-button .mejs-volume-slider,
.mejs-controls .mejs-time-rail .mejs-time-current {
	background: {$colors['alter_hover']} !important;
}
.mejs-container, .mejs-embed, .mejs-embed body, .mejs-container .mejs-controls {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.mejs-container .mejs-controls .mejs-time {
	color: {$colors['inverse_text']};
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total:before,
.mejs-controls .mejs-time-rail .mejs-time-total:before {
	background-color: {$colors['alter_hover_0_5']};
}
.mejs-controls .mejs-time-rail .mejs-time-loaded {
	background: {$colors['alter_hover_0_5']} !important;
}
.mejs-container .mejs-controls .mejs-fullscreen-button,
.mejs-container .mejs-controls .mejs-volume-button,
.mejs-container .mejs-controls .mejs-playpause-button {
	background: {$colors['alter_hover']} !important;
}



/* Button */
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.wp-block-button:not(.is-style-outline) .wp-block-button__link,
.sc_button.sc_button_style_filled {
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.wp-block-button:not(.is-style-outline) .wp-block-button__link:hover,
input[type="submit"]:not([class*="sc_button_hover_"]):hover,
input[type="reset"]:not([class*="sc_button_hover_"]):hover,
input[type="button"]:not([class*="sc_button_hover_"]):hover,
button:not([class*="sc_button_hover_"]):hover,
.sc_button.sc_button_style_filled:not([class*="sc_button_hover_"]):hover {
	border-color: {$colors['text_link']};
	color: {$colors['text_link']};
}
.sc_button.sc_button_style_filled_2 {
	background-color: {$colors['alter_link']};
	border-color: {$colors['alter_link']};
	color: {$colors['inverse_text']};
}
.sc_button.sc_button_style_filled_2:hover {
	border-color: {$colors['alter_link']};
	color: {$colors['alter_link']};
}
.sc_button.sc_button_style_filled_3 {
	background-color: {$colors['alter_hover']};
	border-color: {$colors['alter_hover']};
	color: {$colors['inverse_text']};
}
.sc_button.sc_button_style_filled_3:hover {
	border-color: {$colors['alter_hover']};
	color: {$colors['alter_hover']};
}

/* ================= BUTTON'S HOVERS ==================== */

/* Fade */
[class*="sc_button_hover_fade"]:hover {
	background-color: {$colors['text_hover']} !important;
	color: {$colors['inverse_text']} !important;
}

/* Slide */

/* This way via gradient */
[class*="sc_button_hover_slide"] {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_link']};
}
[class*="sc_button_hover_slide"]:hover {
	background-color: {$colors['text_hover']} !important;
}
.sc_button_hover_slide_left {
	background: linear-gradient(to right, {$colors['text_hover']} 50%, {$colors['text_link']} 50%) repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0) !important;
}
.sc_button_hover_slide_top {
	background: linear-gradient(to bottom, {$colors['text_hover']} 50%, {$colors['text_link']} 50%) repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0) !important;
}

/* ================= END BUTTON'S HOVERS ==================== */



/* Blogger */
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date { 
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date .year:before {
	border-color: {$colors['inverse_text']};
}
.sc_blogger.layout_date .sc_blogger_item::before {
	background-color: {$colors['alter_bg_color']};
}
.sc_blogger_item.sc_plain_item {
	background-color: {$colors['alter_bg_color']};
}
.sc_blogger.layout_polaroid .photostack nav span.current {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_blogger.layout_polaroid .photostack nav span.current.flip {
	background-color: {$colors['text_hover']};
}

/* Call to Action */
.sc_call_to_action .sc_call_to_action_descr {
	color: {$colors['text_dark']};
}
.sc_call_to_action_accented {
	color: {$colors['inverse_text']};
}
.sc_call_to_action_accented .sc_item_title,
.sc_call_to_action_accented .sc_item_descr {
	color: {$colors['inverse_text']};
}
.sc_call_to_action .sc_item_subtitle {
	color: {$colors['text_link']};
}
.sc_call_to_action_accented .sc_item_subtitle {
	color: {$colors['alter_hover']};
}

.sc_call_to_action_accented .sc_item_button > a:hover {
	border-color: {$colors['inverse_text']} !important;
	color: {$colors['inverse_text']} !important;
}

/* Chat */
.sc_chat:after {
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
}
.sc_chat_inner {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bd_color']};
}
.sc_chat_inner a {
	color: {$colors['alter_link']};
}
.sc_chat_inner a:hover {
	color: {$colors['alter_hover']};
}

/* Contact form */
.sc_form .sc_form_item.sc_form_button button { 
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']};
}
.sc_form .sc_form_button button:hover { 
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_form .sc_form_address_label,
.sc_form .sc_form_item > label {
	color: {$colors['text_dark']};
}
.sc_form .sc_form_item .sc_form_element input[type="radio"] + label:before,
.sc_form .sc_form_item .sc_form_element input[type="checkbox"] + label:before {
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
.sc_form_select_container {
	background-color: {$colors['input_bg_color']};
}

/* picker */
.sc_form .picker {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
.picker__month,
.picker__year {
	color: {$colors['input_dark']};
}
.sc_form .picker__nav--prev:before,
.sc_form .picker__nav--next:before {
	color: {$colors['input_text']};
}
.sc_form .picker__nav--prev:hover:before,
.sc_form .picker__nav--next:hover:before {
	color: {$colors['input_dark']};
}
.sc_form .picker__nav--disabled,
.sc_form .picker__nav--disabled:hover,
.sc_form .picker__nav--disabled:before,
.sc_form .picker__nav--disabled:before:hover {
	color: {$colors['input_light']};
}
.sc_form table.picker__table th {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_form .picker__day--infocus {
	color: {$colors['input_dark']};
}
.sc_form .picker__day--today,
.sc_form .picker__day--infocus:hover,
.sc_form .picker__day--outfocus:hover,
.sc_form .picker__day--highlighted:hover,
.sc_form .picker--focused .picker__day--highlighted {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']};
}
.sc_form .picker__day--disabled,
.sc_form .picker__day--disabled:hover {
	color: {$colors['input_light']};
}
.sc_form .picker__day--highlighted.picker__day--disabled,
.sc_form .picker__day--highlighted.picker__day--disabled:hover {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_hover']} !important;
}
.sc_form .picker__day--today:before,
.sc_form .picker__button--today:before,
.sc_form .picker__button--clear:before,
.sc_form button:focus {
	border-color: {$colors['text_link']};
}
.sc_form .picker__button--close:before {
	color: {$colors['text_link']};
}
.sc_form .picker--time .picker__button--clear:hover,
.sc_form .picker--time .picker__button--clear:focus {
	background-color: {$colors['text_hover']};
}
.sc_form .picker__footer {
	border-color: {$colors['input_bd_color']};
}
.sc_form .picker__button--today,
.sc_form .picker__button--clear,
.sc_form .picker__button--close {
	color: {$colors['input_text']};
}
.sc_form .picker__button--today:hover,
.sc_form .picker__button--clear:hover,
.sc_form .picker__button--close:hover {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']} !important;
}
.sc_form .picker__button--today[disabled],
.sc_form .picker__button--today[disabled]:hover {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_hover']};
	border-color: {$colors['input_bg_hover']};
}
.sc_form .picker__button--today[disabled]:before {
	border-top-color: {$colors['input_light']};
}

/* Time picker */
.sc_form .picker__list-item {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
}
.sc_form .picker__list-item:hover,
.sc_form .picker__list-item--highlighted,
.sc_form .picker__list-item--highlighted:hover,
.sc_form .picker--focused .picker__list-item--highlighted,
.sc_form .picker__list-item--selected,
.sc_form .picker__list-item--selected:hover,
.sc_form .picker--focused .picker__list-item--selected {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']};
	border-color: {$colors['input_bd_hover']};
}
.sc_form .picker__list-item--disabled,
.sc_form .picker__list-item--disabled:hover,
.sc_form .picker--focused .picker__list-item--disabled {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_color']};
	border-color: {$colors['input_bd_color']};
}


/* Countdown Style 1 */
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.sc_countdown.sc_countdown_style_1 .sc_countdown_separator {
	color: {$colors['text_link']};
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_label {
	color: {$colors['text_link']};
}

/* Countdown Style 2 */
.sc_countdown.sc_countdown_style_2 .sc_countdown_separator {
	color: {$colors['text_link']};
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span {
	background-color: {$colors['text_link']};
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_label {
	color: {$colors['text_link']};
}

/* Dropcaps */
.sc_dropcaps .sc_dropcaps_item {
	color: {$colors['inverse_text']};
	{$fonts['h1_font-family']}
}
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item {
	background-color: {$colors['text_link']};
}
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item {
	background-color: {$colors['alter_link']};
} 
.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item {
	background-color: {$colors['alter_hover']};
} 
.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item {
	background-color: {$colors['text_hover']};
} 


/* Events */
.sc_events_item .sc_events_item_readmore {
	color: {$colors['alter_link']};
}
.sc_events_item .sc_events_item_readmore:hover {
	color: {$colors['text_link']};
}
.sc_events_style_events-1 .sc_events_item {
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}
.sc_events_style_events-1 .sc_events_item_date {
	background-color: {$colors['alter_link']};
	color: {$colors['inverse_text']};
}
.sc_events_style_events-2 .sc_events_item {
	border-color: {$colors['bd_color']};
}
.sc_events_style_events-2 .sc_events_item_date {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_events_style_events-2 .sc_events_item_time:before,
.sc_events_style_events-2 .sc_events_item_details:before {
	background-color: {$colors['bd_color']};
}

/* Google map */
.sc_googlemap_content {
	background-color: {$colors['bg_color']};
}

/* Highlight */
.sc_highlight_style_1 {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_highlight_style_2 {
	background-color: {$colors['text_hover']};
	color: {$colors['inverse_text']};
}
.sc_highlight_style_3 {
	background-color: {$colors['alter_link']};
	color: {$colors['inverse_text']};
}
.sc_tooltip_parent {
	color: {$colors['text_dark']};
}

/* Icon */
.sc_icon_hover:hover,
a:hover .sc_icon_hover {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_link']} !important; 
}

.sc_icon_shape_round.sc_icon,
.sc_icon_shape_square.sc_icon {	
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}

.sc_icon_shape_round.sc_icon:hover,
.sc_icon_shape_square.sc_icon:hover,
a:hover .sc_icon_shape_round.sc_icon,
a:hover .sc_icon_shape_square.sc_icon {	
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}


/* Image */
figure figcaption,
.sc_image figcaption {
	background-color: {$colors['alter_bg_color']};
}


/* Infobox */
.sc_infobox.sc_infobox_style_regular {
	background-color: {$colors['text_link']};
}

/* Intro */
.sc_intro_inner .sc_intro_subtitle {
	color: {$colors['inverse_link']};
} 
.sc_intro_inner .sc_intro_title {
	color: {$colors['inverse_dark']};
}
.sc_intro_inner .sc_intro_descr,
.sc_intro_inner .sc_intro_icon {
	color: {$colors['inverse_dark']};
}

/* List */
.sc_list_style_iconed li:before,
.sc_list_style_iconed .sc_list_icon {
	color: {$colors['text_link']};
}
.sc_list_style_iconed li .sc_list_title {
	color: {$colors['text_dark']};
}
.sc_list_style_iconed li a:hover .sc_list_title {
	color: {$colors['text_hover']};
}


/* Line */
.sc_line {
	border-color: {$colors['bd_color']};
}
.sc_line .sc_line_title {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
}



/* Menu items */
.popup_menuitem a.prevnext_menuitem:hover,
.popup_menuitem a.close_menuitem:hover {
	color: {$colors['text_link']};
}


.sc_menuitems_style_menuitems-1 .sc_menuitem_price {
	color: {$colors['text_link']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_spicy {
	color: {$colors['text_dark']};
	background-color: {$colors['bg_color']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_box_title {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_content,
.sc_menuitems_style_menuitems-2 .sc_menuitem_ingredients,
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions {
	color: {$colors['text']};
	border-color: {$colors['bd_color']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_content_title,
.sc_menuitems_style_menuitems-2 .sc_menuitem_ingredients_title, 
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_title {
	color: {$colors['text_dark']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_content_title span,
.sc_menuitems_style_menuitems-2 .sc_menuitem_ingredients_title span,
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_title span {
	color: {$colors['text_link']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_list li {
	color: {$colors['text_dark']};
}
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_list li:before,
.sc_menuitems_style_menuitems-2 .sc_menuitem_nutritions_list li span {
	color: {$colors['text_link']};
}
.popup_menuitem > .sc_menuitems_wrap {
	background-color: {$colors['bg_color']};
}


/* Popup */
.sc_popup:before {
	background-color: {$colors['text_link']};
}


/* Price */
.sc_price .sc_price_currency, .sc_price .sc_price_money, .sc_price .sc_price_penny {
	color: {$colors['text_dark']};
}
.sc_price .sc_price_info {
	color: {$colors['text_light']};
}

/* Price block */
.sc_price_block,
.sc_price_block .sc_price_block_money * {
	color: {$colors['inverse_text']};	
}
.sc_price_block.sc_price_block_style_1 {
	background-color: {$colors['alter_hover']};
}
.sc_price_block.sc_price_block_style_2 {
	background-color: {$colors['alter_link']};
}
.sc_price_block.sc_price_block_style_3 {
	background-color: {$colors['text_link']}; 
}


/* Promo */
.sc_promo_image,
.sc_promo_block {
	background-color: {$colors['alter_bg_hover']};
}
.sc_promo_title {
	color: {$colors['inverse_link']};
}
.sc_promo_descr {
	color: {$colors['inverse_text']};
}
.sc_promo .sc_promo_button a:hover {
	color: {$colors['inverse_link']} !important;
	border-color: {$colors['inverse_link']} !important;
}

/* pizzahouse - Recent News */
.sc_recent_news_header {
	border-color: {$colors['text_dark']};
}
.sc_recent_news_header_category_item_more {
	color: {$colors['text_link']};
}
.sc_recent_news_header_more_categories {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.sc_recent_news_header_more_categories > a {
	color: {$colors['alter_link']};
}
.sc_recent_news_header_more_categories > a:hover {
	color: {$colors['alter_hover']};
	background-color: {$colors['alter_bg_hover']};
}
.sc_recent_news .post_counters_item,
.sc_recent_news .post_counters .post_edit a {
	background-color: {$colors['alter_bg_color']};
}
.sidebar .sc_recent_news .post_counters_item,
.sidebar .sc_recent_news .post_counters .post_edit a {
	background-color: {$colors['bg_color']};
}
.sc_recent_news .post_counters .post_edit a {
	color: {$colors['alter_dark']};
}
.sc_recent_news_style_news-magazine .post_accented_border {
	border-color: {$colors['bd_color']};
}
.sc_recent_news_style_news-excerpt .post_item {
	border-color: {$colors['bd_color']};
}


/* Section */
.sc_section_inner {
	color: {$colors['text']};
}


/* Services */
.sc_services_item .sc_services_item_readmore {
	color: {$colors['text_link']};
}
.sc_services_item .sc_services_item_readmore span {
	color: {$colors['text_link']};
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
	color: {$colors['text_hover']};
}
.sc_services_style_services-1 .sc_services_item {
	background-color: {$colors['alter_bg_color']};
	color: {$colors['text']};
}
.sc_services_style_services-1 .sc_icon,
.sc_services_style_services-2 .sc_icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_services_style_services-1 .sc_icon:hover,
.sc_services_style_services-1 a:hover .sc_icon,
.sc_services_style_services-2 .sc_icon:hover,
.sc_services_style_services-2 a:hover .sc_icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_services_style_services-3 a:hover .sc_icon,
.sc_services_style_services-3 .sc_icon:hover {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color']};
}
.sc_services_style_services-3 a:hover .sc_services_item_title {
	color: {$colors['text_link']};
}
.sc_services_style_services-4 .sc_icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_services_style_services-4 .sc_services_item_title {
	color: {$colors['text_dark']};
}
.sc_services_style_services-4 a:hover .sc_icon,
.sc_services_style_services-4 .sc_icon:hover {
	background-color: {$colors['text_hover']};
}
.sc_services_style_services-4 a:hover .sc_services_item_title {
	color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon {
	border-color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon {
	color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon:hover,
.sc_services_style_services-5 a:hover .sc_icon {
	background-color: {$colors['text_link']};
}
.sc_services_style_services-5 .sc_icon:hover,
.sc_services_style_services-5 a:hover .sc_icon {
	color: {$colors['inverse_text']};
}


/* Scroll controls */
.sc_scroll_controls_wrap a {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.sc_scroll_controls_type_side .sc_scroll_controls_wrap a {
	background-color: {$colors['text_link_0_8']};
}
.sc_scroll_controls_wrap a:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}
.sc_scroll_bar .swiper-scrollbar-drag:before {
	background-color: {$colors['text_link']};
}
.sc_scroll .sc_scroll_bar {
	border-color: {$colors['alter_bg_color']};
}

/* Skills */
.sc_skills_bar .sc_skills_item {
	background-color: {$colors['alter_bg_color']};
}
.sc_skills_counter .sc_skills_item .sc_skills_icon {
	color: {$colors['text_link']};
}
.sc_skills_counter .sc_skills_item:hover .sc_skills_icon {
	color: {$colors['text_hover']};
}
.sc_skills_counter .sc_skills_item .sc_skills_info {
	color: {$colors['text_dark']};
}
.sc_skills_bar .sc_skills_item .sc_skills_count {
	border-color: {$colors['text_link']};
}

.sc_skills_legend_title, .sc_skills_legend_value {
	color: {$colors['text_dark']};
}

.sc_skills_counter .sc_skills_item.sc_skills_style_1 {
	background-color: {$colors['text_link']};
}
.sc_skills_counter .sc_skills_item.sc_skills_style_1:hover {
	background-color: {$colors['text_link']};
}
.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_count {
	color: {$colors['alter_hover']};
}
.sc_skills_counter .sc_skills_item.sc_skills_style_1 .sc_skills_info {
	color: {$colors['inverse_text']};
	{$fonts['h1_font-family']}
}
.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_skills_pie .sc_skills_label,
.sc_skills_pie .sc_skills_item .sc_skills_total,
.sc_skills_bar .sc_skills_info .sc_skills_label {
	color: {$colors['text_dark']};
	{$fonts['h1_font-family']}
}


/* Slider */
.sc_slider_controls_wrap a {
	color: {$colors['text_link']};
	border-color: {$colors['bg_color']};
}
.sc_slider_controls_wrap a:hover {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};	
}
.sc_slider_swiper .sc_slider_pagination_wrap .swiper-pagination-bullet-active,
.sc_slider_swiper .sc_slider_pagination_wrap span:hover {
	background-color: {$colors['text_link']};
}
.sc_slider_swiper .sc_slider_info {
	background-color: {$colors['text_link_0_8']} !important;
}
.sc_slider_pagination.widget_area .post_item + .post_item {
	border-color: {$colors['bd_color']};
}
.sc_slider_pagination_over .sc_slider_pagination {
	background-color: {$colors['alter_bg_color_0_8']};
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span {
	border-color: {$colors['bd_color']};
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span:hover,
.sc_slider_pagination_over .sc_slider_pagination_wrap .swiper-pagination-bullet-active {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
}
.sc_slider_pagination_over .sc_slider_pagination .post_title {
	color: {$colors['alter_dark']};
}
.sc_slider_pagination_over .sc_slider_pagination .post_info {
	color: {$colors['alter_text']};
}
.sc_slider_pagination_area .sc_slider_pagination .post_item.active {
	background-color: {$colors['alter_bg_color']} !important;
}

/* Socials */
.sc_socials_item > a,
.sc_socials.sc_socials_type_icons a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_socials_item a span {
	color: {$colors['inverse_text']};
}
.sc_socials_item > a:hover span {
	color: {$colors['text_link']};
}
.sc_socials_item > a:hover,
.sc_socials.sc_socials_type_icons a:hover {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_socials.sc_socials_share.sc_socials_dir_vertical .sc_socials_item a {
	background-color: {$colors['alter_bg_color']};
}

/* Tabs */
.sc_tabs .sc_tabs_titles {
	background-color: {$colors['alter_bg_color']};
}
.sc_tabs .sc_tabs_titles li a:hover,
.sc_tabs .sc_tabs_titles li.ui-state-active a,
.sc_tabs .sc_tabs_titles li.sc_tabs_active a {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.sc_tabs_active a {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}
.sc_tabs .sc_tabs_content .sc_button:hover,
.sc_tabs .sc_tabs_content .sc_button {
	color: {$colors['text_link']} !important;
}

/* Team */
.sc_team_item .sc_team_item_info .sc_team_item_title a {
	color: {$colors['text_dark']};
}
.sc_team_item .sc_team_item_info .sc_team_item_title a:hover {
	color: {$colors['text_link']};
}
.sc_team_item .sc_team_item_info .sc_team_item_position {
	color: {$colors['text_link']};
}
.sc_team_style_team-1 .sc_team_item_info{
	border-color: {$colors['text_link']};
	color: {$colors['text']};
}
.sc_team.sc_team_style_team-3 .sc_team_item .sc_team_item_info .sc_team_item_title a:hover {
	color: {$colors['alter_link']};
}
.sc_team.sc_team_style_team-3 .sc_socials_item a {
	color: {$colors['inverse_link']};
	border-color: {$colors['alter_link']};
	background-color: {$colors['alter_link']};
}

.sc_team.sc_team_style_team-3 .sc_socials_item a:hover{
	color: {$colors['inverse_link']};
	border-color: {$colors['inverse_link']};
}
.sc_team.sc_team_style_team-3 .sc_socials_item a:hover span{
	color: {$colors['alter_link']};
	border-color: {$colors['inverse_link']};
}

.sc_team_style_team-3 .sc_team_item {
	background-color: {$colors['inverse_text']};
}
.sc_team_style_team-3 .sc_team_item .sc_team_item_info .sc_team_item_position {
	color: {$colors['alter_link']};
}


.sc_team.sc_team_style_team-4 .sc_socials_item a {
	color: {$colors['inverse_link']};
	border-color: {$colors['inverse_link']};
}
.sc_team.sc_team_style_team-4 .sc_socials_item a:hover {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_team.sc_team_style_team-4 .sc_team_item_avatar .sc_team_item_hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark_0_8']};
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a {
	color: {$colors['inverse_text']};
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a:hover {
	color: {$colors['text_link']};
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_position {
	color: {$colors['inverse_text']};
}







/* Testimonials */
.sc_testimonial_item:before,
.sc_testimonials .sc_slider_controls_wrap a {
	color: {$colors['alter_hover']};
}
.sc_testimonials .sc_slider_controls_wrap a:hover {
	color: {$colors['text_link']};
}
.sc_testimonials {
	color: {$colors['text']};
}
.sc_testimonials .sc_testimonial_author_name,
.sc_testimonials .sc_testimonial_author_position {
	color: {$colors['alter_hover']};
}


/* Title */
.sc_title_icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['alter_hover']};
}
.sc_title_underline:before {
	color: {$colors['text_link']};
}
.sc_title_divider .sc_title_divider_before,
.sc_title_divider .sc_title_divider_after {
	background-color: {$colors['text_dark']};
}

/* Toggles */
.sc_toggles .sc_toggles_item .sc_toggles_title {
	border-color: {$colors['bd_color']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title .sc_toggles_icon {
	color: {$colors['alter_light']};
	background-color: {$colors['alter_bg_color']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover {
	color: {$colors['text_hover']};
	border-color: {$colors['text_hover']};
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened {
	background-color: {$colors['text_hover']};
}
.sc_toggles .sc_toggles_item .sc_toggles_content {
	border-color: {$colors['bd_color']};
}


/* Tooltip */
.sc_tooltip_parent .sc_tooltip,
.sc_tooltip_parent .sc_tooltip:before {
	background-color: {$colors['alter_hover']};
}


/* Twitter */
.sc_twitter {
	color: {$colors['text']};
}
.sc_twitter .sc_slider_controls_wrap a {
	color: {$colors['text_link']};
}

/* Common styles (title, subtitle and description for some shortcodes) */
.sc_item_subtitle {
	color: {$colors['alter_hover']};
}
.sc_item_button > a:before {
	color: {$colors['text_link']};
	background-color: {$colors['inverse_text']};
}
.sc_item_button > a:hover:before {
	color: {$colors['text_hover']};
}




.esg-grid .eg-shop-content .esg-content .add_to_cart_button {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_link']} !important;
	border-color: {$colors['text_link']} !important;
}
.esg-grid .eg-shop-content .esg-content .add_to_cart_button:hover {
	color: {$colors['inverse_text']} !important;
	border-color: {$colors['inverse_text']} !important;
}
.minimal-light .esg-grid .esg-left,
.minimal-light .esg-grid .esg-right {	
	color: {$colors['alter_hover']} !important;
}
	
.minimal-light .esg-grid .esg-left:hover,
.minimal-light .esg-grid .esg-right:hover {	
	color: {$colors['text_link']} !important;
}
	
	
.sidebar select {
	border-color: {$colors['inverse_text']};
}
	
	
CSS;
					
					$rez = apply_filters('pizzahouse_filter_get_css', $rez, $colors, $fonts, $scheme);
					
					$css['colors'] .= $rez['colors'];
					if ($step == 1) $css['fonts'] = $rez['fonts'];
					$step++;
				}
			}
		} else
			$css['fonts'] = $rez['fonts'];
		
		$css_str = (!empty($css['fonts']) ? $css['fonts'] : '')
					. (!empty($css['colors']) ? $css['colors'] : '');
		
		if (!empty($css_str))
			$css_str = $add_comment . ($minify ? pizzahouse_minify_css($css_str) : $css_str);

		return $css_str;
	}
}
?>