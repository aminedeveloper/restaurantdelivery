<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('pizzahouse_action_theme_styles_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_action_theme_styles_theme_setup', 1 );
	function pizzahouse_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('pizzahouse_filter_used_fonts',			'pizzahouse_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('pizzahouse_filter_list_fonts',			'pizzahouse_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('pizzahouse_action_add_styles',			'pizzahouse_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('pizzahouse_filter_add_styles_inline',		'pizzahouse_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('pizzahouse_action_add_scripts',			'pizzahouse_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('pizzahouse_filter_localize_script',		'pizzahouse_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('pizzahouse_filter_compile_less',			'pizzahouse_filter_theme_styles_compile_less');


		/* Color schemes
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		// Next settings are deprecated
		//bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		//bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Additional accented colors (if need)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		text_link		- links
		text_hover		- hover links
		
		// Inverse blocks
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Input colors - form fields
		input_text		- inactive text
		input_light		- placeholder text
		input_dark		- focused text
		input_bd_color	- inactive border
		input_bd_hover	- focused borde
		input_bg_color	- inactive background
		input_bg_hover	- focused background
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		// Next settings are deprecated
		//alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		pizzahouse_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'pizzahouse'),
			
			// Whole block border and background
			'bd_color'				=> '#e3e3e3', //ok
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#717272', //ok
			'text_light'			=> '#a1a1a1', //ok
			'text_dark'				=> '#2b2b2b', //ok
			'text_link'				=> '#ca0808', //ok
			'text_hover'			=> '#2b2b2b', //ok

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#696767',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#f5f5f5', //ok
			'input_bd_hover'		=> '#e9e9e9', //ok
			'input_bg_color'		=> '#f5f5f5', //ok
			'input_bg_hover'		=> '#f5f5f5', //ok
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#548c1d', //ok green
			'alter_hover'			=> '#fac122', //ok yellow
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f5f5', //ok
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);

		// Add color schemes
		pizzahouse_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'pizzahouse'),
			
			// Whole block border and background
			'bd_color'				=> '#7d7d7d',
			'bg_color'				=> '#262626',

			// Headers, text and links colors
			'text'					=> '#ffffff',
			'text_light'			=> '#ffffff',
			'text_dark'				=> '#e0e0e0',
			'text_link'				=> '#ca0808',
			'text_hover'			=> '#e9e9e9',

			// Inverse colors
			'inverse_text'			=> '#f0f0f0',
			'inverse_light'			=> '#e0e0e0',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#e5e5e5',
		
			// Input fields
			'input_text'			=> '#999999',
			'input_light'			=> '#aaaaaa',
			'input_dark'			=> '#d0d0d0',
			'input_bd_color'		=> '#909090',
			'input_bd_hover'		=> '#888888',
			'input_bg_color'		=> '#666666',
			'input_bg_hover'		=> '#505050',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#999999',
			'alter_light'			=> '#aaaaaa',
			'alter_dark'			=> '#d0d0d0',
			'alter_link'			=> '#ffffff',
			'alter_hover'			=> '#fac122', //ok
			'alter_bd_color'		=> '#909090',
			'alter_bd_hover'		=> '#888888',
			'alter_bg_color'		=> '#666666',
			'alter_bg_hover'		=> '#505050',
			)
		);


		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		pizzahouse_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '3.214em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.25em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.8em'
			)
		);
		pizzahouse_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '2.500em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.25em',
			'margin-top'	=> '0.7em',
			'margin-bottom'	=> '1.15em'
			)
		);
		pizzahouse_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '1.857em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.25em',
			'margin-top'	=> '0.6em',
			'margin-bottom'	=> '0.8em'
			)
		);
		pizzahouse_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '1.429em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.35em',
			'margin-top'	=> '1.1em',
			'margin-bottom'	=> '1.35em'
			)
		);
		pizzahouse_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '1.214em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.4em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '1.3em'
			)
		);
		pizzahouse_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '1.18em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.45em',
			'margin-top'	=> '1.25em',
			'margin-bottom'	=> '1.25em'
			)
		);
		pizzahouse_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Merriweather Sans',
			'font-size' 	=> '14px',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.96em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		pizzahouse_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		pizzahouse_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '0.8em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '3em'
			)
		);
		pizzahouse_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			)
		);
		pizzahouse_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			)
		);
		pizzahouse_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '0.75em'
			)
		);
		pizzahouse_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> 'Graviolasoft',
			'font-size' 	=> '0.786em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)
		);
		pizzahouse_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'pizzahouse'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.45em'
			)
		);
		pizzahouse_add_custom_font('other', array(
				'title'			=> esc_html__('Other elements', 'pizzahouse'),
				'font-family'	=> 'Journal'
			)
		);
	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('pizzahouse_filter_theme_styles_used_fonts')) {
	
	function pizzahouse_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Merriweather Sans'] = 1;
		$theme_fonts['Graviolasoft'] = 1;
		$theme_fonts['Journal'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('pizzahouse_filter_theme_styles_list_fonts')) {
	
	function pizzahouse_filter_theme_styles_list_fonts($list) {
		// Example:
		

		 if (!isset($list['Graviolasoft'])) {
				$list['Graviolasoft'] = array(
					'family' => 'sans-serif',
					'css'    => pizzahouse_get_file_url('/css/font-face/Graviolasoft/stylesheet.css')
					);
		 }

		if (!isset($list['Journal'])) {
			$list['Journal'] = array(
				'family' => 'sans-serif',
				'css'    => pizzahouse_get_file_url('/css/font-face/Journal/stylesheet.css')
			);
		}


		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('pizzahouse_filter_theme_styles_compile_less')) {
	
	function pizzahouse_filter_theme_styles_compile_less($files) {
		if (file_exists(pizzahouse_get_file_dir('css/theme.less'))) {
		 	$files[] = pizzahouse_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('pizzahouse_action_theme_styles_add_styles')) {
	
	function pizzahouse_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( pizzahouse_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'pizzahouse-theme-style', pizzahouse_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'pizzahouse-theme-style', pizzahouse_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('pizzahouse_filter_theme_styles_add_styles_inline')) {
	
	function pizzahouse_filter_theme_styles_add_styles_inline($custom_style) {
		// Todo: add theme specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css


		// Submenu width
		$menu_width = pizzahouse_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = pizzahouse_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = pizzahouse_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = pizzahouse_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= pizzahouse_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('pizzahouse_action_theme_styles_add_scripts')) {
	
	function pizzahouse_action_theme_styles_add_scripts() {
		if (pizzahouse_get_theme_option('show_theme_customizer') == 'yes' && file_exists(pizzahouse_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'pizzahouse-theme-styles-customizer-script', pizzahouse_get_file_url('js/theme.customizer.js'), array(), null, true );
	}
}

// Add theme scripts inline
if (!function_exists('pizzahouse_filter_theme_styles_localize_script')) {
	
	function pizzahouse_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = pizzahouse_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = pizzahouse_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = pizzahouse_get_scheme_color('bg_color');
		return $vars;
	}
}
?>