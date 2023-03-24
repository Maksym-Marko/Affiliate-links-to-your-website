<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPEnqueueScriptsFrontend
{

    /*
    * Registration of styles and scripts
    */
    public static function registerScripts()
    {

        // register scripts and styles
        add_action('wp_enqueue_scripts', ['MXALFWPEnqueueScriptsFrontend', 'enqueue']);
    }

    public static function enqueue()
    {

        wp_enqueue_style('mxalfwp_font_awesome', MXALFWP_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css');

        wp_enqueue_style('mxalfwp_style', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/css/style.css', ['mxalfwp_font_awesome'], MXALFWP_PLUGIN_VERSION, 'all');

        // include Vue.js
        // dev version
        wp_enqueue_script('mxalfwp_vue2', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/add/vue/vue-dev.js', [], MXALFWP_PLUGIN_VERSION, true);

        // production version
        // wp_enqueue_script( 'mxalfwp_vue2', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/add/vue/vue-prod.js', [], MXALFWP_PLUGIN_VERSION, true );

        wp_enqueue_script('mxalfwp_script', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/js/script.js', ['mxalfwp_vue2'], MXALFWP_PLUGIN_VERSION, true);

        wp_localize_script('mxalfwp_script', 'mxalfwp_frontend_localize', [

            'nonce'       => wp_create_nonce('mxalfwp_nonce_request_front'),
            'ajax_url'    => admin_url('admin-ajax.php'),

            'translation' => [
                'text_1'  => __('Generate Affiliate Link', 'mxalfwp-domain'),
                'text_2'  => __('Page URL', 'mxalfwp-domain'),
                'text_3'  => __('Generate Link', 'mxalfwp-domain'),
                'text_4'  => __('My Links Data', 'mxalfwp-domain'),
                'text_5'  => __('Link', 'mxalfwp-domain'),
                'text_6'  => __('Views', 'mxalfwp-domain'),
                'text_7'  => __('Bought', 'mxalfwp-domain'),
                'text_8'  => __('Earned', 'mxalfwp-domain'),
                'text_9'  => __('Paied', 'mxalfwp-domain'),
                'text_10' => __('You must use current website\'s pages to create affiliate link!', 'mxalfwp-domain'),
                'text_11' => __('URL Incorrect!', 'mxalfwp-domain'),
                'text_12' => __('Server Error!', 'mxalfwp-domain'),
                
            ]

        ]);
    }
}
