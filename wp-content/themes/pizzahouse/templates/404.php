<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_template_404_theme_setup' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_template_404_theme_setup', 1 );
	function pizzahouse_template_404_theme_setup() {
		pizzahouse_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'pizzahouse_template_404_output' ) ) {
	function pizzahouse_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php esc_html_e( '404', 'pizzahouse' ); ?></h1>
				<h2 class="page_subtitle"><?php esc_html_e('The requested page cannot be found', 'pizzahouse'); ?></h2>
				<p class="page_description"><?php echo wp_kses_data( sprintf( __('Can\'t find what you need? Take a moment and do a search below or start from <a href="%s">our homepage</a>.', 'pizzahouse'), esc_url(home_url('/')) ) ); ?></p>
				<div class="page_search"><?php pizzahouse_show_layout(pizzahouse_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'pizzahouse')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>