<?php
/**
 * Theme Widget: Flickr photos
 */

// Theme init
if (!function_exists('pizzahouse_widget_flickr_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_widget_flickr_theme_setup', 1 );
	function pizzahouse_widget_flickr_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_widget_flickr_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('pizzahouse_widget_flickr_load')) {
	add_action( 'widgets_init', 'pizzahouse_widget_flickr_load' );
	function pizzahouse_widget_flickr_load() {
		register_widget( 'pizzahouse_widget_flickr' );
	}
}

// Widget Class
class pizzahouse_widget_flickr extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_flickr', 'description' => esc_html__('Last flickr photos.', 'pizzahouse') );
		parent::__construct( 'pizzahouse_widget_flickr', esc_html__('PizzaHouse - Flickr photos', 'pizzahouse'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$flickr_username = isset($instance['flickr_username']) ? $instance['flickr_username'] : '';
		$flickr_count = isset($instance['flickr_count']) ? $instance['flickr_count'] : '';
		
		
		// Before widget (defined by themes)
		pizzahouse_show_layout($before_widget);

		// Display the widget title if one was input (before and after defined by themes)
		if ($title) pizzahouse_show_layout($title, $before_title, $after_title);
		
		// Here will be displayed widget content
		?>
		<div class="flickr_images">
			<?php
			$holder = 'script';
			if ($flickr_count <= 10) {
				// Old method - up to 10 images
				$size = 's';
				?><<?php pizzahouse_show_layout($holder); ?> type="text/javascript" src="<?php echo esc_attr(pizzahouse_get_protocol()); ?>://www.flickr.com/badge_code_v2.gne?count=<?php echo (int) $flickr_count; ?>&amp;display=random&amp;flickr_display=random&amp;size=<?php echo urlencode($size); ?>&amp;layout=x&amp;source=user&amp;user=<?php echo urlencode($flickr_username); ?>"></<?php pizzahouse_show_layout($holder); ?>><?php
			} else {
				// New method > 10 images
				$size = 'square';
				?><<?php pizzahouse_show_layout($holder); ?> type="text/javascript" src="<?php echo esc_attr(pizzahouse_get_protocol()); ?>://www.flickr.com/badge_code.gne?count=<?php echo (int) $flickr_count; ?>&amp;display=random&amp;flickr_display=random&amp;size=<?php echo urlencode($size); ?>&amp;layout=x&amp;source=user&amp;nsid=<?php echo urlencode($flickr_username); ?>&amp;raw=1"></<?php pizzahouse_show_layout($holder); ?>><?php
			}
			?>
		</div>

		<?php
		// After widget (defined by themes)
		pizzahouse_show_layout($after_widget);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['flickr_username'] = strip_tags( $new_instance['flickr_username'] );
		$instance['flickr_count'] = (int) $new_instance['flickr_count'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {
		
		// Set up some default widget settings
		$defaults = array( 
			'title' => '', 
			'flickr_username' => '', 
			'flickr_count' => '' 
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$flickr_username = isset($instance['flickr_username']) ? $instance['flickr_username'] : '';
		$flickr_count = isset($instance['flickr_count']) ? $instance['flickr_count'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'pizzahouse'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'flickr_username' )); ?>"><?php esc_html_e('Flickr ID:', 'pizzahouse'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'flickr_username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'flickr_username' )); ?>" value="<?php echo esc_attr($flickr_username); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'flickr_count' )); ?>"><?php esc_html_e('Number of photos:', 'pizzahouse'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'flickr_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'flickr_count' )); ?>" value="<?php echo esc_attr($flickr_count); ?>" class="widgets_param_fullwidth" />
		</p>

	<?php
	}
}



// trx_widget_flickr
//-------------------------------------------------------------
/*
[trx_widget_flickr id="unique_id" title="Widget title" flickr_count="6" flickr_username="Flickr@23"]
*/
if ( !function_exists( 'pizzahouse_sc_widget_flickr' ) ) {
	function pizzahouse_sc_widget_flickr($atts, $content=null){	
		$atts = pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"title"			=> "",
			'flickr_count'	=> 6,
			'flickr_username' => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		extract($atts);
		$type = 'pizzahouse_widget_flickr';
		$output = '';
		if ( (int) $atts['flickr_count'] > 0 && !empty($atts['flickr_username']) ) {
			global $wp_widget_factory;
			if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
				$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
								. ' class="widget_area sc_widget_flickr' 
									. (pizzahouse_exists_visual_composer() ? ' vc_widget_flickr wpb_content_element' : '') 
									. (!empty($class) ? ' ' . esc_attr($class) : '') 
							. '">';
				ob_start();
				the_widget( $type, $atts, pizzahouse_prepare_widgets_args(pizzahouse_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_flickr', 'widget_flickr') );
				$output .= ob_get_contents();
				ob_end_clean();
				$output .= '</div>';
			}
		}
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_widget_flickr', $atts, $content);
	}
	add_shortcode("trx_widget_flickr", "pizzahouse_sc_widget_flickr");
}


// Add [trx_widget_flickr] in the VC shortcodes list
if (!function_exists('pizzahouse_widget_flickr_reg_shortcodes_vc')) {
	//add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_widget_flickr_reg_shortcodes_vc');
	function pizzahouse_widget_flickr_reg_shortcodes_vc() {
		
		vc_map( array(
				"base" => "trx_widget_flickr",
				"name" => esc_html__("Widget Flickr photos", 'pizzahouse'),
				"description" => wp_kses_data( __("Display the latest photos from Flickr account", 'pizzahouse') ),
				"category" => esc_html__('Content', 'pizzahouse'),
				"icon" => 'icon_trx_widget_flickr',
				"class" => "trx_widget_flickr",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", 'pizzahouse'),
						"description" => wp_kses_data( __("Title of the widget", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "flickr_username",
						"heading" => esc_html__("Flickr username", 'pizzahouse'),
						"description" => wp_kses_data( __("Your Flickr username", 'pizzahouse') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "flickr_count",
						"heading" => esc_html__("Number of photos", 'pizzahouse'),
						"description" => wp_kses_data( __("How many photos to be displayed?", 'pizzahouse') ),
						"class" => "",
						"value" => "6",
						"type" => "textfield"
					),
					pizzahouse_get_vc_param('id'),
					pizzahouse_get_vc_param('class'),
					pizzahouse_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Flickr extends WPBakeryShortCode {}

	}
}
?>