<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_template_menuitems_2_theme_setup' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_template_menuitems_2_theme_setup', 1 );
	function pizzahouse_template_menuitems_2_theme_setup() {
		pizzahouse_add_template(array(
			'layout' => 'menuitems-2',
			'template' => 'menuitems-2',
			'mode'   => 'menuitems_single',
			'title'  => esc_html__('MenuItems /Style 2/', 'pizzahouse'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'pizzahouse'),
			'w'		 => 1170,
			'h'		 => 659
		));
	}
}

// Template output
if ( !function_exists( 'pizzahouse_template_menuitems_2_output' ) ) {
	function pizzahouse_template_menuitems_2_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_menuitems_item sc_menuitems_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
				. (!pizzahouse_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>

				<?php
				if ($post_options['menuitem_image']) {
					?><div class="sc_menuitem_image"><?php
					pizzahouse_show_layout($post_options['menuitem_image']);
					if ( $post_options['menuitem_spicylevel'] != 'inherit' && $post_options['menuitem_spicylevel'] != '0' && $post_options['menuitem_spicylevel'] != 'NaN' ) {
						?>
						<div class="sc_menuitem_spicy menuitem_spicylevel_<?php pizzahouse_show_layout($post_options['menuitem_spicylevel']); ?> ">
							<span class="icon-fire"></span> Spicy Level <?php pizzahouse_show_layout($post_options['menuitem_spicylevel']); ?>/5
						</div>
						<?php
					}
					?></div><?php
				}
				
				if ( $post_data['post_title'] || ( $post_options['menuitem_price'] != 'inherit' && strlen($post_options['menuitem_price']) != 0 ) ) {
					?>
					<div class="sc_menuitem_box_title">
						<?php
						if ($post_data['post_title']) {
							?><div class="sc_menuitem_title"><strong><?php pizzahouse_show_layout($post_data['post_title']);?></strong></div><?php
						}
						if ( $post_options['menuitem_price'] != 'inherit' && strlen($post_options['menuitem_price']) != 0 ) {
							?><div class="sc_menuitem_price"><?php pizzahouse_show_layout($post_options['menuitem_price']); ?></div><?php
						}
						?>
						<div class="cL"></div>
					</div>
					<?php
				}
				
				if ($post_data['post_content']) {
					?>
					<div class="sc_menuitem_content">
						<div class="sc_menuitem_content_title"><span class="icon-chef"></span>Description</div>
						<?php pizzahouse_show_layout($post_data['post_content']); ?>
					</div>
					<?php
				}
				
				if ( ($post_options['menuitem_ingredients'] != 'inherit') and ( strlen($post_options['menuitem_ingredients']) != 0 ) ) {
					?>
					<div class="sc_menuitem_ingredients">
						<div class="sc_menuitem_ingredients_title"><span class="icon-sweet"></span>Ingredients</div>
						<?php echo wpautop($post_options['menuitem_ingredients']); ?>
					</div>
					<?php
				}

				if (   ( $post_options['menuitem_calories'] != 'inherit'		&& strlen($post_options['menuitem_calories']) != 0 )
					|| ( $post_options['menuitem_cholesterol'] != 'inherit'		&& strlen($post_options['menuitem_cholesterol']) != 0 )
					|| ( $post_options['menuitem_fiber'] != 'inherit'			&& strlen($post_options['menuitem_fiber']) != 0 )
					|| ( $post_options['menuitem_sodium'] != 'inherit'			&& strlen($post_options['menuitem_sodium']) != 0 )
					|| ( $post_options['menuitem_sodium'] != 'inherit'			&& strlen($post_options['menuitem_sodium']) != 0 )
					|| ( $post_options['menuitem_carbohydrates'] != 'inherit'	&& strlen($post_options['menuitem_carbohydrates']) != 0 )
					|| ( $post_options['menuitem_fat'] != 'inherit'				&& strlen($post_options['menuitem_fat']) != 0 )
					|| ( $post_options['menuitem_protein'] != 'inherit'			&& strlen($post_options['menuitem_protein']) != 0 )
						) {
					?>
					<div class="sc_menuitem_nutritions">
						<div class="sc_menuitem_nutritions_title"><span class="icon-food"></span>Nutritions</div>
						<ul class="sc_menuitem_nutritions_list">
							<?php
							if ( $post_options['menuitem_calories'] != 'inherit' && strlen($post_options['menuitem_calories']) != 0 )
								echo '<li>' . esc_html__('Calories: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_calories']) . esc_html__('Kcal', 'pizzahouse') . '</span></li>';
							if ( $post_options['menuitem_cholesterol'] != 'inherit' && strlen($post_options['menuitem_cholesterol']) != 0 ) {
								echo '<li>' . esc_html__('Cholesterol: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_cholesterol']) . esc_html__('mg', 'pizzahouse') . '</span></li>';
							}
							if ( $post_options['menuitem_fiber'] != 'inherit' && strlen($post_options['menuitem_fiber']) != 0 ) {
								echo '<li>' . esc_html__('Fiber: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_fiber']) . esc_html__('g', 'pizzahouse') . '</span></li>';
							}
							if ( $post_options['menuitem_sodium'] != 'inherit' && strlen($post_options['menuitem_sodium']) != 0 ) {
								echo '<li>' . esc_html__('Sodium: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_sodium']) . esc_html__('mg', 'pizzahouse') . '</span></li>';
							}
							if ( $post_options['menuitem_carbohydrates'] != 'inherit' && strlen($post_options['menuitem_carbohydrates']) != 0 ) {
								echo '<li>' . esc_html__('Carbohydrates: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_carbohydrates']) . esc_html__('g', 'pizzahouse') . '</span></li>';
							} 
							if ( $post_options['menuitem_fat'] != 'inherit' && strlen($post_options['menuitem_fat']) != 0 ) { 
								echo '<li>' . esc_html__('Fat: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_fat']) . esc_html__('g', 'pizzahouse') . '</span></li>';
							}
							if ( $post_options['menuitem_protein'] != 'inherit' && strlen($post_options['menuitem_protein']) != 0 ) {
								echo '<li>' . esc_html__('Protein: ', 'pizzahouse') . '<span>' . trim($post_options['menuitem_protein']) . esc_html__('g', 'pizzahouse') . '</span></li>';
							}
							?>
						</ul>
						<div class="cL"></div>
					</div>
					<?php
				}
				?>
				
				<div class="sc_menuitem_more">
					<?php if ( $post_options['menuitem_product'] != 'inherit' ) { ?>
						<a class="sc_button sc_button_square sc_button_style_filled sc_button_size_small margin_right_tiny" href="<?php echo esc_url(get_permalink($post_options['menuitem_product'])); ?>"><?php esc_html_e('ORDER', 'pizzahouse'); ?></a>
					<?php } ?>
					<a class="sc_button sc_button_square sc_button_style_filled sc_button_size_small" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><?php esc_html_e('POST COMMENT', 'pizzahouse'); ?></a>
					<div class="cL"></div>
				</div>

				<div class="clearfix"></div>
			</div>
		<?php
	}
}
?>