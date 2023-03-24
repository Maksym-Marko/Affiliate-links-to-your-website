<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPFrontEndMain
{

    /*
    * Additional classes
    */
    public function additionalClasses()
    {

        // enqueue_scripts class
        mxalfwpRequireClassFileFrontend( 'enqueue-scripts.php' );

        MXALFWPEnqueueScriptsFrontend::registerScripts();

        // add cabinet shotrcode
        mxalfwpRequireClassFileFrontend( 'shortcodes/cabinet.php' );

        MXALFWPShortcodeCabinet::add_shortcode();

        // ajax
        mxalfwpRequireClassFileFrontend( 'server.php' );

        MXALFWPServer::registerAjax();        

    }

}

// Initialize
$initialize_frontend_class = new MXALFWPFrontEndMain();

// include classes
$initialize_frontend_class->additionalClasses();
