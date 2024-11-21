<?php
/**
 * Plugin Name: Summa
 * Version: 1.0
 * Author: sri
 * Text Domain: react_vite
 * Slug: react_vite
 */
?>
    <!--<div id="user-main"></div>-->
<?php
defined('USER_PLUGIN_SLUG') or define('USER_PLUGIN_SLUG', 'react_vite');


function React_plugin_menu_page() {
    add_menu_page(
        __( 'React integrated plugin', 'React plugin' ),
        __( 'React integrated plugin', 'React plugin' ),
        'manage_options',
        'react-plugin',
        'React_plugin_page_html',
        '',
        11
    );
}
add_action('admin_menu', 'React_plugin_menu_page');

function React_plugin_page_html()
{
    echo '<div id="wlr-point-remainder"></div>';
}

function addAdminScript() {
    $page = (string) isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ? $_GET['page'] : '';
    if ( $page != 'react-plugin' ) {
        return;
    }
    $localize = [
        'save_settings'  => wp_create_nonce( 'wser-save-settings' ),
        'ajax_url'       => admin_url( 'admin-ajax.php' ),
        'admin_url'      => admin_url(),
        'activity_menu'  => admin_url( 'admin.php?' . http_build_query( [
                'page' => USER_PLUGIN_SLUG,
                'view' => 'activity'
            ] ) ),
        'wser_success'   => __( 'Success', 'wc-subscription-email-reminder' ),
        'wser_error'     => __( 'Error', 'wc-subscription-email-reminder' ),
        'security_nonce' => wp_create_nonce( 'search-products' ),
    ];

    wp_enqueue_script( 'wser_settings_page', plugin_dir_url(__FILE__) . 'assets/Admin/Js/dist/main.bundle.js', [
        'jquery',
    ], 1.0 );
    wp_enqueue_style( 'wser_settings_page_style', plugin_dir_url(__FILE__) . 'assets/Admin/Css/dist/style.css', [
        'woocommerce_admin_styles'
    ], 1.0 );
    wp_localize_script( 'wser_settings_page', 'wser_localize_data', $localize );
}
add_action( 'admin_enqueue_scripts', 'addAdminScript' );


defined('ABSPATH') || exit;
?>