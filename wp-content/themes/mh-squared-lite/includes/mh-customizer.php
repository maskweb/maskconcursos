<?php

function mh_squared_lite_customize_register($wp_customize) {

	/***** Register Custom Controls *****/

    class MH_Squared_Lite_Upgrade extends WP_Customize_Control {
        public function render_content() {  ?>
        	<p class="mh-upgrade-thumb">
        		<img src="<?php echo get_template_directory_uri(); ?>/images/mh_squared.png" />
        	</p>
        	<p class="customize-control-title mh-upgrade-title">
        		<?php esc_html_e('MH Squared Pro', 'mh-squared-lite'); ?>
        	</p>
        	<p class="textfield mh-upgrade-text">
        		<?php esc_html_e('If you like the free version of this theme, you will LOVE the full version of MH Squared which includes unique custom widgets, additional features and more useful options to customize your website.', 'mh-squared-lite'); ?>
			</p>
			<p class="customize-control-title mh-upgrade-title">
        		<?php esc_html_e('Additional Features:', 'mh-squared-lite'); ?>
        	</p>
        	<ul class="mh-upgrade-features">
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('Options to modify color scheme', 'mh-squared-lite'); ?>
	        	</li>
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('Typography options', 'mh-squared-lite'); ?>
	        	</li>
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('Several additional widget areas', 'mh-squared-lite'); ?>
	        	</li>
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('Additional custom widgets', 'mh-squared-lite'); ?>
	        	</li>
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('Additional custom menu slots', 'mh-squared-lite'); ?>
	        	</li>
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('Social buttons, related articles, ...', 'mh-squared-lite'); ?>
	        	</li>
	        	<li class="mh-upgrade-feature-item">
	        		<?php esc_html_e('News ticker and many more...', 'mh-squared-lite'); ?>
	        	</li>
        	</ul>
			<p class="mh-upgrade-button">
				<a href="http://www.mhthemes.com/themes/mh/squared/" target="_blank" class="button button-secondary">
					<?php esc_html_e('Learn more about MH Squared', 'mh-squared-lite'); ?>
				</a>
			</p><?php
        }
    }

	/***** Add Panels *****/

	$wp_customize->add_panel('mh_squared_lite_theme_options', array('title' => esc_html__('Theme Options', 'mh-squared-lite'), 'description' => '', 'capability' => 'edit_theme_options', 'theme_supports' => '', 'priority' => 1,));

	/***** Add Sections *****/

	$wp_customize->add_section('mh_squared_lite_general', array('title' => esc_html__('General', 'mh-squared-lite'), 'priority' => 1, 'panel' => 'mh_squared_lite_theme_options'));
	$wp_customize->add_section('mh_squared_lite_layout', array('title' => esc_html__('Layout', 'mh-squared-lite'), 'priority' => 2, 'panel' => 'mh_squared_lite_theme_options'));
	$wp_customize->add_section('mh_squared_lite_upgrade', array('title' => esc_html__('More Features', 'mh-squared-lite'), 'priority' => 3, 'panel' => 'mh_squared_lite_theme_options'));

	/***** Add Settings *****/

	$wp_customize->add_setting('mh_squared_lite_options[excerpt_length]', array('default' => 25, 'type' => 'option', 'sanitize_callback' => 'mh_squared_lite_sanitize_integer'));
	$wp_customize->add_setting('mh_squared_lite_options[read_more]', array('default' => esc_html__('Read More', 'mh-squared-lite'), 'type' => 'option', 'sanitize_callback' => 'mh_squared_lite_sanitize_text'));
	$wp_customize->add_setting('mh_squared_lite_options[sidebar]', array('default' => 'right', 'type' => 'option', 'sanitize_callback' => 'mh_squared_lite_sanitize_select'));
	$wp_customize->add_setting('mh_squared_lite_options[premium_version_upgrade]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'esc_attr'));

	/***** Add Controls *****/

	$wp_customize->add_control('excerpt_length', array('label' => esc_html__('Custom Excerpt Length in Words', 'mh-squared-lite'), 'section' => 'mh_squared_lite_general', 'settings' => 'mh_squared_lite_options[excerpt_length]', 'priority' => 1, 'type' => 'text'));
	$wp_customize->add_control('read_more', array('label' => esc_html__('Custom Excerpt More-Text', 'mh-squared-lite'), 'section' => 'mh_squared_lite_general', 'settings' => 'mh_squared_lite_options[read_more]', 'priority' => 2, 'type' => 'text'));
	$wp_customize->add_control('sidebar', array('label' => esc_html__('Sidebar Alignment', 'mh-squared-lite'), 'section' => 'mh_squared_lite_layout', 'settings' => 'mh_squared_lite_options[sidebar]', 'priority' => 1, 'type' => 'select', 'choices' => array('right' => esc_html__('Right Sidebar', 'mh-squared-lite'), 'left' => esc_html__('Left Sidebar', 'mh-squared-lite'))));
	$wp_customize->add_control(new MH_Squared_Lite_Upgrade($wp_customize, 'premium_version_upgrade', array('section' => 'mh_squared_lite_upgrade', 'settings' => 'mh_squared_lite_options[premium_version_upgrade]', 'priority' => 1)));
}
add_action('customize_register', 'mh_squared_lite_customize_register');

/***** Data Sanitization *****/

function mh_squared_lite_sanitize_text($input) {
    return wp_kses_post(force_balance_tags($input));
}
function mh_squared_lite_sanitize_integer($input) {
    return strip_tags(intval($input));
}
function mh_squared_lite_sanitize_checkbox($input) {
    if ($input == 1) {
        return 1;
    } else {
        return '';
    }
}
function mh_squared_lite_sanitize_select($input) {
    $valid = array(
        'enable' => esc_html__('Enable', 'mh-squared-lite'),
        'disable' => esc_html__('Disable', 'mh-squared-lite'),
		'right' => esc_html__('Right Sidebar', 'mh-squared-lite'),
        'left' => esc_html__('Left Sidebar', 'mh-squared-lite'),
    );
    if (array_key_exists($input, $valid)) {
        return $input;
    } else {
        return '';
    }
}

/***** Return Theme Options / Set Default Options *****/

if (!function_exists('mh_squared_lite_theme_options')) {
	function mh_squared_lite_theme_options() {
		$theme_options = wp_parse_args(
			get_option('mh_squared_lite_options', array()),
			mh_squared_lite_default_options()
		);
		return $theme_options;
	}
}
if (!function_exists('mh_squared_lite_default_options')) {
	function mh_squared_lite_default_options() {
		$default_options = array(
			'excerpt_length' => 25,
			'read_more' => esc_html__('Read More', 'mh-squared-lite'),
			'sidebar' => 'right',
		);
		return $default_options;
	}
}

/***** Enqueue Customizer CSS *****/

function mh_squared_lite_customizer_css() {
	wp_enqueue_style('mh-customizer', get_template_directory_uri() . '/admin/customizer.css', array());
}
add_action('customize_controls_print_styles', 'mh_squared_lite_customizer_css');

/***** Enqueue Customizer JS *****/

function mh_squared_lite_customizer_js() {
	wp_enqueue_script('mh-customizer', get_template_directory_uri() . '/js/mh-customizer.js', array(), '1.0.0', true);
	wp_localize_script('mh-customizer', 'mh_squared_lite_links', array(
		'upgradeURL' => esc_url('http://www.mhthemes.com/themes/mh/squared/'),
		'upgradeLabel' => esc_html__('Upgrade to MH Squared Pro', 'mh-squared-lite'),
		'title'	=> esc_html__('Theme Related Links:', 'mh-squared-lite'),
		'themeURL' => esc_url('http://www.mhthemes.com/themes/mh/squared-lite/'),
		'themeLabel' => esc_html__('Theme Info Page', 'mh-squared-lite'),
		'docsURL' => esc_url('http://www.mhthemes.com/support/documentation-mh-squared/'),
		'docsLabel'	=> esc_html__('Theme Documentation', 'mh-squared-lite'),
		'supportURL' => esc_url('https://wordpress.org/support/theme/mh-squared-lite'),
		'supportLabel' => esc_html__('Support Forum', 'mh-squared-lite'),
		'rateURL' => esc_url('https://wordpress.org/support/view/theme-reviews/mh-squared-lite?filter=5'),
		'rateLabel'	=> esc_html__('Rate this theme', 'mh-squared-lite'),
	));
}
add_action('customize_controls_enqueue_scripts', 'mh_squared_lite_customizer_js');

?>