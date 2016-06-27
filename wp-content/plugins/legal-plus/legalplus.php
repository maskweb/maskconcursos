<?php
/*
Plugin Name: Legal+
Plugin URI:
Description: Una solución legal completa
Version: 1.0
Author: Dms consulting
Author URI: http://www.consultingdms.com/
License:
*/

//on activate
function legalplus_activate() {
    global $user_ID;

    $page_privacity['post_type']    = 'page';
    $page_privacity['post_content'] = file_get_contents(plugin_dir_path(__FILE__).'assets/content/conditions_default.txt');
    $page_privacity['post_parent']  = 0;
    $page_privacity['post_author']  = $user_ID;
    $page_privacity['post_status']  = 'publish';
    $page_privacity['post_title']   = 'Política de privacidad y Cookies';
    $page_privacity = apply_filters('legalplus_add_new_page', $page_privacity);
    $page_privacityid = wp_insert_post($page_privacity);
    if ($page_privacityid != 0) {
        update_option('legalplus_page_privacity', $page_privacityid);
    }

    $page_notice['post_type']    = 'page';
    $page_notice['post_content'] = file_get_contents(plugin_dir_path(__FILE__).'assets/content/legal_notice.txt');
    $page_notice['post_parent']  = 0;
    $page_notice['post_author']  = $user_ID;
    $page_notice['post_status']  = 'publish';
    $page_notice['post_title']   = 'Aviso legal';
    $page_notice = apply_filters('legalplus_add_new_page', $page_notice);
    $page_noticeid = wp_insert_post($page_notice);
    if ($page_noticeid != 0) {
        update_option('legalplus_page_notice', $page_noticeid);
    }
    update_option('legalplus_bottom_bar', 'on');
}
register_activation_hook( __FILE__, 'legalplus_activate' );

add_action( 'admin_menu', 'legalplus_menu' );
function legalplus_menu() {
    add_options_page( 'Configuración legal +', 'legal+', 'manage_options', 'legal_plus_settings', 'legalplus_menu_page' );
}

function legalplus_menu_page() {
    include("legalplus_admin.php");
}

add_action( 'admin_enqueue_scripts', 'legalplus_admin_scripts' );
function legalplus_admin_scripts($hook) {
    if ('settings_page_legal_plus_settings' != $hook) {
        return;
    }

    wp_register_style('legalplus_admin_css', plugin_dir_url(__FILE__) . 'assets/css/legalplus-admin-style.css');
    wp_enqueue_style('legalplus_admin_css');
}

function legalplus_load_head() {

    wp_register_style( 'legalplus_css_1', plugin_dir_url( __FILE__ ).'assets/css/legalplus.css' );
    wp_enqueue_style( 'legalplus_css_1' );

    wp_register_script( 'legalplus_script_1', plugin_dir_url( __FILE__ ).'assets/js/legalplus.js' );
    wp_enqueue_script( 'legalplus_script_1' );
}
add_action('wp_head', 'legalplus_load_head');

function legalplus_add_header() {
    include('templates/legalplus_header.php');
}
add_action('get_header', 'legalplus_add_header');

//register
add_action( 'register_form', 'legalplus_register_form' );
function legalplus_register_form() {

    ?>
    <p style="font-size: 12px;">
        <input type="checkbox" name="legal_privacity_register" required> He leido y acepto la
        <a href="<?php echo get_page_link(get_option('legalplus_page_privacity')) ?>" target="_blank">
            Política de privacidad</a><br><br>
    </p>
    <?php
}

add_filter( 'registration_errors', 'legalplus_registration_errors', 10, 3 );
function legalplus_registration_errors( $errors, $sanitized_user_login, $user_email ) {

    if ( empty( $_POST['legal_privacity_register'] ) || ! empty( $_POST['legal_privacity_register'] ) &&
            trim( $_POST['legal_privacity_register'] ) == '' ) {
        $errors->add( 'legal_privacity_register_error',
            __( '<strong>ERROR</strong>: Debes aceptar de Política de privacidad', '' ) );
    }

    return $errors;
}

//clear cookies
function legalplus_clear_cookies() {
    $pastdate = mktime(0,0,0,1,1,1970);
    $temp = $_COOKIE;
    foreach( $temp as $name => $value ){
        setcookie( $name , "" , $pastdate );
    }
    echo json_encode( array( 'success' => true ) );
    die;
}

//ajax call to clear cookies if decides out
add_action('wp_ajax_legalplus_clear_cookies', 'legalplus_clear_cookies');
add_action('wp_ajax_nopriv_legalplus_clear_cookies', 'legalplus_clear_cookies');

//bottom bar
function legalplus_bottom_nav_bar() {?>
    <div id="bottom_legal_bar">
        <div>
            Conozca nuestra
            <a href="<?php echo get_page_link(get_option('legalplus_page_privacity')) ?>" target="_blank">Política de privacidad y cookies</a>
            y
            <a href="<?php echo get_page_link(get_option('legalplus_page_notice')) ?>" target="_blank">Aviso legal</a>
        </div>
    </div>
<?php }
if(get_option('legalplus_bottom_bar') == 'on') {
    add_action( 'wp_footer', 'legalplus_bottom_nav_bar');
}

//shortcodes
add_shortcode( 'legalplus_WEB_DOMAIN' , 'legalplus_WEB_DOMAIN_funct' );
function legalplus_WEB_DOMAIN_funct() {
    return get_option('legalplus_WEB_DOMAIN', '');
}

add_shortcode( 'legalplus_TITULAR' , 'legalplus_TITULAR_funct' );
function legalplus_TITULAR_funct() {
    return get_option('legalplus_TITULAR', '');
}

add_shortcode( 'legalplus_COMPLETE_ADDRESS' , 'legalplus_COMPLETE_ADDRESS_funct' );
function legalplus_COMPLETE_ADDRESS_funct() {
    return get_option('legalplus_COMPLETE_ADDRESS', '');
}

add_shortcode( 'legalplus_IDENT_CODE' , 'legalplus_IDENT_CODE_funct' );
function legalplus_IDENT_CODE_funct() {
    return get_option('legalplus_IDENT_CODE', '');
}

add_shortcode( 'legalplus_EMAIL' , 'legalplus_EMAIL_funct' );
function legalplus_EMAIL_funct() {
    return get_option('legalplus_EMAIL', '');
}

add_shortcode( 'legalplus_REGISTER' , 'legalplus_REGISTER_funct' );
function legalplus_REGISTER_funct() {
    return get_option('legalplus_REGISTER', '');
}

add_shortcode( 'legalplus_CONDUCT_CODE' , 'legalplus_CONDUCT_CODE_funct' );
function legalplus_CONDUCT_CODE_funct() {
    return get_option('legalplus_CONDUCT_CODE', '');
}

add_shortcode( 'legalplus_LICENCE' , 'legalplus_LICENCE_funct' );
function legalplus_LICENCE_funct() {
    return get_option('legalplus_LICENCE', '');
}

add_shortcode( 'legalplus_TELEPHONE' , 'legalplus_TELEPHONE_funct' );
function legalplus_TELEPHONE_funct() {
    return get_option('legalplus_TELEPHONE', '');
}

add_shortcode( 'legalplus_COLEGE' , 'legalplus_COLEGE_funct' );
function legalplus_COLEGE_funct() {
    return get_option('legalplus_COLEGE', '');
}

add_shortcode( 'legalplus_COOKIES_TEXT' , 'legalplus_COOKIES_TEXT_funct' );
function legalplus_COOKIES_TEXT_funct() {
    return get_option('legalplus_COOKIES_TEXT', '');
}

add_shortcode( 'legalplus_COOKIE_PAGE' , 'legalplus_COOKIE_PAGE_funct' );
function legalplus_COOKIE_PAGE_funct() {
    return get_option('legalplus_COOKIE_PAGE', '');
}

add_shortcode( 'legalplus_CONDITIONS_LINK' , 'legalplus_CONDITIONS_PAGE_funct' );
function legalplus_CONDITIONS_PAGE_funct() {
    return get_page_link(get_option('legalplus_page_privacity'));
}

