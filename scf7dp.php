<?php
/*
Plugin Name: Smart CF7 DatePicker
Description: An alternative/fix to Contact Form 7 date selector.
Version: 1.0
Author: Michael Gillihan
Author URI: http://mikegillihan.com/
License: GPL2
*/

/* Based on Mark O'Brien's CF7 DatePicker - http://wordpress.org/plugins/cf7-datepicker-alternative/ */

function scf7dp_activation() {
    // @todo Require Contact form 7 to activate
}

register_activation_hook(__FILE__, 'scf7dp_activation');

function scf7dp_deactivation() {}

register_deactivation_hook(__FILE__, 'scf7dp_deactivation');

add_action('wp_enqueue_scripts', 'scf7dp_scripts');

function scf7dp_scripts() {

    global $post;
    wp_enqueue_script( 'modernizr-input-types', plugins_url( '/js/modernizr.inputtypes.min.js', __FILE__ ) );

    wp_register_script( 'scf7dp-js', plugins_url( '/js/scf7dp.js', __FILE__ ) ); 

    wp_register_style( 'jquery-ui-css', plugins_url( '/css/jquery-ui.css', __FILE__ ) ); 
    
    $content = $post->post_content;
    $scf7dp_shortcode = 'contact-form-7';

    // @todo Find a way to target shortcodes in widgets
    if ( ! is_front_page() || has_shortcode( $content, $scf7dp_shortcode ) ) {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-effects-fade');
        wp_enqueue_script('jquery-effects-slide');
        wp_enqueue_script('jquery-effects-clip');
        wp_enqueue_script('scf7dp-js');
        wp_enqueue_style( 'jquery-ui-css' );
    }

    $effect      = (get_option('scf7dp_effect') == '') ? "slide" : get_option('scf7dp_effect');
    $show_week     = (get_option('scf7dp_show_week') == 'enabled') ? true : false;
    $monyearmenu    = (get_option('scf7dp_monyearmenu') == 'enabled') ? true : false;
        $config_array = array(
            'effect' => $effect,
            'showWeek' => $show_week,
            'monyearmenu' => $monyearmenu,
        );

    wp_localize_script('scf7dp-js', 'setting', $config_array);

}

add_action('admin_enqueue_scripts', 'scf7dp_admin_styles');

function scf7dp_admin_styles() {

    wp_register_style( 'scf7dp-styles', plugins_url( '/css/scf7dp.css', __FILE__, true ) ); 
    wp_enqueue_style( 'scf7dp-styles' );

}

add_action('admin_menu', 'scf7dp_plugin_settings');

function scf7dp_plugin_settings() {
    
    add_submenu_page('options-general.php', 'Smart CF7 DatePicker Settings', 'Smart CF7 DatePicker', 'administrator', 'datepicker_settings', 'datepicker_display_settings');
}

function datepicker_display_settings() {

    $slide_effect = (get_option('scf7dp_effect') == 'slide') ? 'selected' : '';
    $fade_effect = (get_option('scf7dp_effect') == 'fade') ? 'selected' : '';
    $clip_effect = (get_option('scf7dp_effect') == 'clip') ? 'selected' : '';
    $show_week  = (get_option('scf7dp_show_week') == 'enabled') ? 'checked' : '' ;
    $monyearmenu  = (get_option('scf7dp_monyearmenu') == 'enabled') ? 'checked' : '' ;

    $html = '<div class="scf7dp-wrap">

            <form method="post" name="options" action="options.php">
            <h2>CF7 Datepicker Settings</h2>' . wp_nonce_field('update-options') . '
            <table width="100%" cellpadding="10" class="form-table">
                <tr>
                    <td align="left" scope="row">
                    <label>Animations</label><select name="scf7dp_effect" >
                      <option value="slide" ' . $slide_effect . '>Slide</option>
                      <option value="fade" '.$fade_effect.'>Fade</option>
                      <option value="clip" '.$clip_effect.'>Clip</option>
                    </select>
                    </td> 
                </tr>
                <tr>
                    <td align="left" scope="row">
                    <label>Display Month & year menus</label><input type="checkbox" '.$monyearmenu.' name="scf7dp_monyearmenu" 
                    value="enabled" />
                    </td> 
                </tr>
                <tr>
                    <td align="left" scope="row">
                    <label>Show week of the year</label><input type="checkbox" '.$show_week.' name="scf7dp_show_week" 
                    value="enabled" />
                    </td> 
                </tr>
            </table>
            <p class="submit">
                <input type="hidden" name="action" value="update" />  
                <input type="hidden" name="page_options" value="scf7dp_monyearmenu,scf7dp_effect,scf7dp_show_week" /> 
                <input type="submit" name="Submit" value="Update" />
            </p>
            </form>

        </div>';
    echo $html;    

}
?>