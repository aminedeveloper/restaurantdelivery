<?php
/**
 * Single post
 */
get_header(); 

$single_style = pizzahouse_storage_get('single_style');
if (empty($single_style)) $single_style = pizzahouse_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	pizzahouse_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !pizzahouse_param_is_off(pizzahouse_get_custom_option('show_sidebar_main')),
			'content' => pizzahouse_get_template_property($single_style, 'need_content'),
			'terms_list' => pizzahouse_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>