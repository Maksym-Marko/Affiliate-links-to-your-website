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
        add_action('admin_enqueue_scripts', ['MXALFWPEnqueueScripts', 'enqueue']);
    }

    public static function enqueue()
    {

        wp_enqueue_style('mxalfwp_font_awesome', MXALFWP_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css');

        wp_enqueue_style('mxalfwp_common_style', MXALFWP_PLUGIN_URL . '/assets/css/common-style.css', ['mxalfwp_font_awesome'], MXALFWP_PLUGIN_VERSION, 'all');

        wp_enqueue_style('mxalfwp_admin_style', MXALFWP_PLUGIN_URL . 'includes/admin/assets/css/style.css', ['mxalfwp_common_style'], MXALFWP_PLUGIN_VERSION, 'all');

        // include Vue.js
        // dev version
        wp_enqueue_script('mxalfwp_vue2', MXALFWP_PLUGIN_URL . 'assets/add/vue/vue-dev.js', [], MXALFWP_PLUGIN_VERSION, true);

        // production version
        // wp_enqueue_script( 'mxalfwp_vue2', MXALFWP_PLUGIN_URL . 'assets/add/vue/vue-prod.js', [], MXALFWP_PLUGIN_VERSION, true );

        wp_enqueue_script('mxalfwp_admin_script', MXALFWP_PLUGIN_URL . 'includes/admin/assets/js/script.js', ['jquery', 'mxalfwp_vue2'], MXALFWP_PLUGIN_VERSION, true);

        wp_localize_script('mxalfwp_admin_script', 'mxalfwp_admin_localize', [

            'ajax_url'   => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('mxalfwp_nonce_request_admin'),
            'main_page'  => admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG),
            'percent'    => get_option( 'mxalfwp_default_percent' ),
            'currency'   => get_option( 'mxalfwp_default_currency_sign' ),

            'translation' => [
                'text_1'  => __('Vue DO NOT activated!!!', 'mxalfwp-domain'),
                'text_2'  => __('Percent Per Buy', 'mxalfwp-domain'),
                'text_3'  => __('Your partner will get this percentage of the product price.', 'mxalfwp-domain'),
                'text_4'  => __('Save', 'mxalfwp-domain'),
                'text_5'  => __('Please enter a number.', 'mxalfwp-domain'),
                'text_6'  => __('Percent must be between 0.1 and 99.', 'mxalfwp-domain'),
                'text_7'  => __('Server Error!', 'mxalfwp-domain'),
                'text_8'  => __('Are you sure you want to manage this partner?', 'mxalfwp-domain'),
                'text_9'  => __('Currency Sign', 'mxalfwp-domain'),
                'text_10' => __('This sign will be displayed next to the amounts', 'mxalfwp-domain'),
                'text_11'  => __('The currency sign must contain from 1 to 5 characters', 'mxalfwp-domain'),
            ]

        ]);
    }
}
