<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPShortcodeCabinet
{

    public static function add_shortcode()
    {
        add_shortcode( 'mxalfwp_partner_cabinet', ['MXALFWPShortcodeCabinet', 'cabinet'] );    
    }

    public static function cabinet()
    {

        ob_start();

        include_once MXALFWP_PLUGIN_ABS_PATH . 'includes/frontend/pages/cabinet.php';

        return ob_get_clean();

    }

}