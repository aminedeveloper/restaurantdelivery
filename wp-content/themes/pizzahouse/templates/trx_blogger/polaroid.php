<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_template_polaroid_theme_setup' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_template_polaroid_theme_setup', 1 );
	function pizzahouse_template_polaroid_theme_setup() {
		pizzahouse_add_template(array(
			'layout' => 'polaroid',
			'template' => 'polaroid',
			'mode'   => 'blogger',
			'container2' => '<div class="sc_blogger_elements"><div class="photostack" %css><div>%s</div></div></div>',
			'title'  => esc_html__('Blogger layout: Polaroid', 'pizzahouse'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'pizzahouse'),
			'w'		 => 370,
			'h'		 => 370
		));
		// Add template specific scripts
		add_action('pizzahouse_action_blog_scripts', 'pizzahouse_template_polaroid_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('pizzahouse_template_polaroid_add_scripts')) {
	
	function pizzahouse_template_polaroid_add_scripts($style) {
		if (pizzahouse_substr($style, 0, 8) == 'polaroid')
			pizzahouse_enqueue_polaroid();
	}
}

// Template output
if ( !function_exists( 'pizzahouse_template_polaroid_output' ) ) {
	function pizzahouse_template_polaroid_output($post_options, $post_data) {
		$show_title = true;
		$style = $post_options['layout'];
		?>
		<figure class="post_item sc_blogger_item sc_polaroid_item<?php if ($post_options['number'] == $post_options['posts_on_page'] && !pizzahouse_param_is_on($post_options['loadmore'])) echo ' sc_blogger_item_last'; ?>">
			<a href="<?php echo esc_url($post_data['post_link']); ?>" class="photostack-img"><?php pizzahouse_show_layout($post_data['post_thumb']); ?></a>
			<figcaption class="photostack_info">
				<h6 class="post_title sc_title sc_blogger_title sc_polaroid_title photostack-title"><?php pizzahouse_show_layout($post_data['post_title']); ?></h6>
				<?php
				if ($post_data['post_excerpt']) {
					echo '<div class="photostack-back">' 
						. (in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) 
							? $post_data['post_excerpt']
							: '<p>'.trim(pizzahouse_strshort($post_data['post_excerpt'], isset($post_options['descr']) 
									? $post_options['descr'] 
									: pizzahouse_get_custom_option('post_excerpt_maxlength_masonry')
									)
							).'</p>')
						. '</div>';
				}
				?>
			</figcaption>
		</figure>
		<?php
	}
}
?>