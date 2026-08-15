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

        wp_enqueue_style('mxalfwp_common_style', MXALFWP_PLUGIN_URL . '/assets/css/common-style.css', ['mxalfwp_font_awesome'], MXALFWP_PLUGIN_VERSION, 'all');

        wp_enqueue_style('mxalfwp_style', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/css/style.css', ['mxalfwp_common_style'], MXALFWP_PLUGIN_VERSION, 'all');

        // include Vue.js
        // dev version
        wp_enqueue_script('mxalfwp_vue2', MXALFWP_PLUGIN_URL . 'assets/add/vue/vue-dev.js', [], MXALFWP_PLUGIN_VERSION, true);

        // production version
        // wp_enqueue_script( 'mxalfwp_vue2', MXALFWP_PLUGIN_URL . 'assets/add/vue/vue-prod.js', [], MXALFWP_PLUGIN_VERSION, true );

        wp_enqueue_script('mxalfwp_script', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/js/script.js', ['mxalfwp_vue2'], MXALFWP_PLUGIN_VERSION, true);        

        wp_localize_script('mxalfwp_script', 'mxalfwp_frontend_localize', [

            'nonce'          => wp_create_nonce('mxalfwp_nonce_request_front'),
            'ajax_url'       => admin_url('admin-ajax.php'),
            'partner_status' => mxalfwpGetPartnerStatus( get_current_user_id() ),

            'translation' => [
                'text_1'  => __('Generate Affiliate Link', 'affiliate-links-woocommerce'),
                'text_2'  => __('Page URL', 'affiliate-links-woocommerce'),
                'text_3'  => __('Generate Link', 'affiliate-links-woocommerce'),
                'text_4'  => __('My Links Data', 'affiliate-links-woocommerce'),
                'text_5'  => __('Link', 'affiliate-links-woocommerce'),
                'text_6'  => __('Views', 'affiliate-links-woocommerce'),
                'text_7'  => __('Orders', 'affiliate-links-woocommerce'),
                'text_8'  => __('Earned', 'affiliate-links-woocommerce'),
                'text_9'  => __('Paid', 'affiliate-links-woocommerce'),
                'text_10' => __('You must use current website\'s pages to create affiliate link!', 'affiliate-links-woocommerce'),
                'text_11' => __('URL Incorrect!', 'affiliate-links-woocommerce'),
                'text_12' => __('Server Error!', 'affiliate-links-woocommerce'),
                'text_13' => __('Vue DO NOT activated!!!', 'affiliate-links-woocommerce'),
                'text_14' => __('Visited pages', 'affiliate-links-woocommerce'),                
                'text_15' => __('You are blocked.', 'affiliate-links-woocommerce'),
                'text_16' => get_option('mxalfwp_default_currency_sign'),
                'text_17' => __('You may use these links on your website or social media account. When a user buys a product on our site, you get a percentage.', 'affiliate-links-woocommerce'),
                'text_18' => __('Copy the URL of the page or product and paste it into the box below to create an affiliate link.', 'affiliate-links-woocommerce')
            ]

        ]);

        wp_enqueue_script('mxalfwp_script_data_saver', MXALFWP_PLUGIN_URL . 'includes/frontend/assets/js/data-saver.js', ['mxalfwp_script'], MXALFWP_PLUGIN_VERSION, true);
    }
}
