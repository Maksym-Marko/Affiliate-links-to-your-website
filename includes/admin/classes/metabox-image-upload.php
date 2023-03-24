<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Metaboxes. Upload images
 */
class MXALFWPMetaboxesImageUpload
{

    public static function registerScripts()
    {
        add_action( 'admin_enqueue_scripts', ['MXALFWPMetaboxesImageUpload', 'upload_image_scrips'] );
    }

        public static function upload_image_scrips()
        {

            wp_enqueue_script( 'mxalfwp_image-upload', MXALFWP_PLUGIN_URL . 'includes/admin/assets/js/image-upload.js', ['jquery'], MXALFWP_PLUGIN_VERSION, false );

        }

}
