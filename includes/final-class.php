<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

final class MXALFWPAffiliateLinksForWoocommercePluginWPPGenerator
{

    /*
    * Include required core files
    */
    public function includeCore()
    {        

        // helpers
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/helpers.php';

        // cathing errors
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/Catching-Errors.php';

        // Route
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/Route.php';

        // Models
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/Model.php';

        // Views
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/View.php';

        // Controllers
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/Controller.php';

    }

    /*
    * Include Admin Path
    */
    public function includeAdminPath()
    {

        // Part of the Administrator
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/admin/admin-main.php';
    
    }

    /*
    * Include Frontend Path
    */
    public function includeFrontendPath()
    {

        // Part of the Frontend
        require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/frontend/frontend-main.php';
    
    }

}

// create an instance of final class
$mxalfwp_wppGenerator = new MXALFWPAffiliateLinksForWoocommercePluginWPPGenerator();

// run core files
$mxalfwp_wppGenerator->includeCore();

// include admin parth
$mxalfwp_wppGenerator->includeAdminPath();

// include frontend parth
$mxalfwp_wppGenerator->includeFrontendPath();
