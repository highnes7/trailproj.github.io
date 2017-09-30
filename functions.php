<?php

add_action( 'after_setup_theme', 'ascendant_child_theme_setup' );

function ascendant_child_theme_setup() {

	$theme = wp_get_theme();
	if ( $theme->Template == 'allegiant_pro' ) {
		add_filter('body_class', 'ascendant_body_class');
	}

    // Remove parent font
	remove_action('wp_head', 'cpotheme_styling_fonts', 20 );
	remove_action('wp_head', 'cpotheme_styling_custom', 20);

	remove_filter('cpotheme_background_args', 'cpotheme_background_args');
	add_filter('cpotheme_background_args', 'cpotheme_child_background_args');

}

function ascendant_body_class( $classes ){
	$classes[] = 'allegian_pro_template';
	return $classes;
}

//Add public stylesheets
if(!function_exists('ascendant_child_add_styles')){
	add_action('wp_enqueue_scripts', 'ascendant_child_add_styles', 9);
	function ascendant_child_add_styles(){

		wp_enqueue_style( 'ascendant-google-font', 'https://fonts.googleapis.com/css?family=Lato:400,700|Raleway:300,400,500,700,800,900' );	
		wp_enqueue_style('ascendant-main', get_template_directory_uri().'/style.css');

	}
}

if(!function_exists('ascendant_child_add_fontawesome')){
	add_action('wp_enqueue_scripts', 'ascendant_child_add_fontawesome',11);
	function ascendant_child_add_fontawesome(){

		wp_enqueue_style('cpotheme-fontawesome');

	}
}

if(!function_exists('cpotheme_child_background_args')){
	function cpotheme_child_background_args($data){ 
		$data = array(
		'default-color' => 'eeeeee',
		'default-image' => get_stylesheet_directory_uri().'/images/background.jpg',
		'default-repeat' => 'no-repeat',
		'default-position-x' => 'center',
		'default-attachment' => 'fixed',
		);
		return $data;
	}
}

add_filter( 'cpotheme_customizer_controls', 'ascendant_add_customizer_fields', 11 );
function ascendant_add_customizer_fields( $data ){

	$data['transparent_header'] = array(
		'label' => __('Transparent Header', 'allegiant'),
		'description' => __('Your header will be transparent.', 'allegiant'),
		'section' => 'cpotheme_management',
		'type' => 'checkbox',
		'sanitize' => 'cpotheme_sanitize_bool',
		'default' => '1');

	//Typography
	if ( isset($data['type_headings']) ) {
		$data['type_headings']['default'] = 'Raleway';
	}
	if ( isset($data['type_nav']) ) {
		$data['type_nav']['default'] = 'Raleway';
	}
	if ( isset($data['type_body']) ) {
		$data['type_body']['default'] = 'Lato';
	}
	if ( isset($data['primary_color']) ) {
		$data['primary_color']['default'] = '#70b85d';
	}
	if ( isset($data['type_headings_color']) ) {
		$data['type_headings_color']['default'] = '#18253c';
	}
	if ( isset($data['type_widgets_color']) ) {
		$data['type_widgets_color']['default'] = '#18253c';
	}
	if ( isset($data['type_nav_color']) ) {
		$data['type_nav_color']['default'] = '#18253c';
	}
	if ( isset($data['type_link_color']) ) {
		$data['type_link_color']['default'] = '#70b85d';
	}
	if ( isset($data['type_body_color']) ) {
		$data['type_body_color']['default'] = '#8c9597';
	}

	if ( isset($data['postpage_dates']) ) {
		$data['postpage_dates']['default'] = false;
	}
	if ( isset($data['postpage_authors']) ) {
		$data['postpage_authors']['default'] = false;
	}
	if ( isset($data['postpage_comments']) ) {
		$data['postpage_comments']['default'] = false;
	}
	if ( isset($data['postpage_categories']) ) {
		$data['postpage_categories']['default'] = false;
	}
	if ( isset($data['postpage_tags']) ) {
		$data['postpage_tags']['default'] = false;
	}
	if ( isset($data['home_tagline_content']) ) {
		$data['home_tagline_content']['sanitize'] = 'wp_kses_post';
	}

	return $data;

}

// Pro Typographi
add_filter('cpotheme_font_headings', 'ascendant_cpotheme_font_headings');
add_filter('cpotheme_font_menu', 'ascendant_cpotheme_font_menu');
add_filter('cpotheme_font_body', 'ascendant_cpotheme_font_body');

function ascendant_cpotheme_font_headings() {
	$option_list = get_option('cpotheme_settings', false);
	if ( isset($option_list['type_headings']) ) {
		return $option_list['type_headings'];
	}else{
		return "Raleway";
	}
}

function ascendant_cpotheme_font_menu() {
	$option_list = get_option('cpotheme_settings', false);
	if ( isset($option_list['type_nav']) ) {
		return $option_list['type_nav'];
	}else{
		return "Raleway";
	}
}

function ascendant_cpotheme_font_body() {
	$option_list = get_option('cpotheme_settings', false);
	if ( isset($option_list['type_body']) ) {
		return $option_list['type_body'];
	}else{
		return "Lato";
	}
}

add_action('wp_head', 'ascendant_cpotheme_styling_custom', 20);
function ascendant_cpotheme_styling_custom(){
	$primary_color = cpotheme_get_option('primary_color'); ?>
	<style type="text/css">
		<?php if($primary_color != ''): ?>
		html body .button, 
		html body .button:link, 
		html body .button:visited,
		.menu-portfolio .current-cat a,
		.pagination .current,
		html body input[type=submit] { background: <?php echo $primary_color; ?>; }
		html body .button:hover,
		html body input[type=submit]:hover { color:#fff; background:<?php echo $primary_color; ?>; }
		.menu-main .current_page_ancestor > a,
		.menu-main .current-menu-item > a,
		.features a.feature-image, .team .team-member-description { color:<?php echo $primary_color; ?>; }
		<?php endif; ?>
    </style>
	<?php
}

if(!function_exists('cpotheme_logo')){
	function cpotheme_logo($width = 0, $height = 0){
		$output = '<div id="logo" class="logo">';
		if(cpotheme_get_option('general_texttitle') == 0){
			if(cpotheme_get_option('general_logo') == ''){
				if(defined('CPOTHEME_LOGO_WIDTH')) $width = intval(CPOTHEME_LOGO_WIDTH);
				$output .= '<a class="site-logo" href="'.home_url().'"><img src="'.get_stylesheet_directory_uri().'/images/logo.png" alt="'.get_bloginfo('name').'" width="'.esc_attr($width).'" height="'.esc_attr($height).'"/></a>';
			}else{
				$logo_width = cpotheme_get_option('general_logo_width');
				$logo_url = esc_url(cpotheme_get_option('general_logo'));
				if($logo_width != '') $logo_width = ' style="width:'.esc_attr($logo_width).'px;"';
				$output .= '<a class="site-logo" href="'.home_url().'"><img src="'.$logo_url.'" alt="'.get_bloginfo('name').'"'.$logo_width.'/></a>';
			}
		}
		
		$classes = '';
		if(cpotheme_get_option('general_texttitle') == 0) $classes = ' hidden';
		if(!is_front_page()){
			$output .= '<span class="title site-title'.esc_attr($classes).'"><a href="'.home_url().'">'.get_bloginfo('name').'</a></span>';
		}else{
			$output .= '<h1 class="title site-title '.esc_attr($classes).'"><a href="'.home_url().'">'.get_bloginfo('name').'</a></h1>';
		}
		
		$output .= '</div>';
		echo $output;
	}
}