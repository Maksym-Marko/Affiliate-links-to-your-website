<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPEnqueueScripts
{

    /*
    * Registration of styles and scripts
    */
    public static function registerScripts()
    {

        // register scripts and styles
        add_action( 'admin_enqueue_scripts', ['MXALFWPEnqueueScripts', 'enqueue'] );

    }

        public static function enqueue()
        {

            wp_enqueue_style( 'mxalfwp_font_awesome', MXALFWP_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );

            wp_enqueue_style( 'mxalfwp_admin_style', MXALFWP_PLUGIN_URL . 'includes/admin/assets/css/style.css', [ 'mxalfwp_font_awesome' ], MXALFWP_PLUGIN_VERSION, 'all' );

            wp_enqueue_script( 'mxalfwp_admin_script', MXALFWP_PLUGIN_URL . 'includes/admin/assets/js/script.js', [ 'jquery' ], MXALFWP_PLUGIN_VERSION, false );

            wp_localize_script( 'mxalfwp_admin_script', 'mxalfwp_admin_localize', [

                'ajaxurl'   => admin_url( 'admin-ajax.php' ),
                'main_page' => admin_url( 'admin.php?page=' . MXALFWP_MAIN_MENU_SLUG ),

            ] );

        }

}
