<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_template_team_2_theme_setup' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_template_team_2_theme_setup', 1 );
	function pizzahouse_template_team_2_theme_setup() {
		pizzahouse_add_template(array(
			'layout' => 'team-2',
			'template' => 'team-2',
			'mode'   => 'team',
			'title'  => esc_html__('Team /Style 2/', 'pizzahouse'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'pizzahouse'),
			'w' => 370,
			'h' => 370
		));
	}
}

// Template output
if ( !function_exists( 'pizzahouse_template_team_2_output' ) ) {
	function pizzahouse_template_team_2_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (pizzahouse_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_team_item sc_team_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : ''). (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?> columns_wrap"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!pizzahouse_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>
			><div class="sc_team_item_avatar column-2_5">
					<?php pizzahouse_show_layout($post_options['photo']); ?>
			</div><div class="sc_team_item_info column-3_5">
				<h5 class="sc_team_item_title"><?php echo (!empty($post_options['link']) ? '<a href="'.esc_url($post_options['link']).'">' : '') . ($post_data['post_title']) . (!empty($post_options['link']) ? '</a>' : ''); ?></h5>
				<div class="sc_team_item_position"><?php pizzahouse_show_layout($post_options['position']);?></div>
				<div class="sc_team_item_description"><?php
					if (!empty($post_data['post_excerpt']))
						pizzahouse_show_layout(pizzahouse_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : pizzahouse_get_custom_option('post_excerpt_maxlength_masonry'))); 
					else {
						$post_meta = get_post_meta($post_data['post_id'], 'pizzahouse_team_data', true);
						if (!empty($post_meta['team_member_brief_info']))
							pizzahouse_show_layout(pizzahouse_strshort($post_meta['team_member_brief_info'], isset($post_options['descr']) ? $post_options['descr'] : pizzahouse_get_custom_option('post_excerpt_maxlength_masonry'))); 
					}
				?></div>
				<?php pizzahouse_show_layout($post_options['socials']); ?>
			</div></div>
		<?php
		if (pizzahouse_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>