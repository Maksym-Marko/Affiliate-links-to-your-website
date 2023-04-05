<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPRouteRegistrar
{

    /**
    * set controller
    */
    public $controller = '';

    /**
    * set action
    */
    public $action     = '';

    /**
    * set slug or parent menu slug
    */
    public $slug = MXALFWP_MAIN_MENU_SLUG;

    /**
    * catch class error
    */
    public $classAttributesError = NULL;

    /**
    * set properties
    */
    public $properties = [
        'page_title' => 'Title of the page',
        'menu_title' => 'Link Name',
        'capability' => 'manage_options',
        'menu_slug'  => MXALFWP_MAIN_MENU_SLUG,
        'dashicons'  => 'dashicons-share',
        'position'   => 111
    ];

    /**
    * set slug of sub menu
    */
    public $subMenuSlug = false;

    /**
    * set plugin name
    */
    public $pluginName;

    /**
    * MXALFWPRouteRegistrar constructor
    */
    public function __construct( ...$args )
    {

        $this->pluginName = MXALFWP_PLUGN_BASE_NAME;

        // set data
        $this->mxalfwpSetData( ...$args );

    }

    /**
    * require class
    */
    public function requireController( $controller )
    {

        if (file_exists(MXALFWP_PLUGIN_ABS_PATH . "includes/admin/controllers/{$controller}.php")) {
            require_once MXALFWP_PLUGIN_ABS_PATH . "includes/admin/controllers/{$controller}.php";
        }

    }

    /**
    * $controller     - Controller
    *
    * $action         - Action
    *
    * $slug           - if NULL - menu item will investment into
    *                        MXALFWP_MAIN_MENU_SLUG menu item
    *
    * $menuProperties - menu properties
    *
    * $subMenuSlug    - slug of sub menu
    *
    * $settingsArea   - place item to settings area (core WP Settings menu item)
    *
    */
    public function mxalfwpSetData( $controller, $action, $slug = MXALFWP_MAIN_MENU_SLUG, array $menuProperties = [], $subMenuSlug = false, $settingsArea = false )
    {

        // set controller
        $this->controller = $controller;

        // set action
        $this->action     = $action;

        // set slug
        if ($slug == NULL) {

            $this->slug = MXALFWP_MAIN_MENU_SLUG;

        } else {

            $this->slug = $slug;

        }

        // set properties
        foreach ($menuProperties as $key => $value) {
            $this->properties[$key] = $value;
        }

        // callback function
        $mxalfwpCallbackFunctionMenu = 'createAdminMainMenu';

        /*
        * check if it's submenu
        * set subMenuSlug
        */
        if ($subMenuSlug !== false) {

            $this->subMenuSlug = $subMenuSlug;

            $mxalfwpCallbackFunctionMenu = 'createAdminSubMenu';
            
        }

        /*
        * check if it's settings menu item
        */
        if ($settingsArea !== false) {

            $mxalfwpCallbackFunctionMenu = 'settingsAreaMenuItem';

            // add link Settings under the name of the plugin
            add_filter( "plugin_action_links_$this->pluginName", [$this, 'createSettingsLink'] );
            
        }

        /**
        * require controller
        */
        $this->requireController( $this->controller );

        /**
        * catching errors of class attrs
        */
        $isErrorClassAtr = MXALFWPCatchingErrors::catchClassAttributesError( $this->controller, $this->action );
        
        // catch error class attr
        if ($isErrorClassAtr !== NULL) {
            $this->classAttributesError = $isErrorClassAtr;
        }

        // register admin menu
        add_action( 'admin_menu', [$this, $mxalfwpCallbackFunctionMenu] );

    }

    /**
    * Create Main menu
    */
    public function createAdminMainMenu()
    {

        add_menu_page( __( $this->properties['page_title'], 'mxalfwp-domain' ),
            __( $this->properties['menu_title'], 'mxalfwp-domain' ),
            $this->properties['capability'],
            $this->slug,
            [ $this, 'viewConnector' ],
            $this->properties['dashicons'], // icons https://developer.wordpress.org/resource/dashicons/#id
            $this->properties['position'] );
    }

    /**
    * Create Sub menu
    */
    public function createAdminSubMenu()
    {
        
        // create a sub menu
        add_submenu_page( $this->slug,
            __( $this->properties['page_title'], 'mxalfwp-domain' ),
            __( $this->properties['menu_title'], 'mxalfwp-domain' ),
            $this->properties['capability'],
            $this->subMenuSlug,
            [ $this, 'viewConnector' ]
        );

    }

    /**
    * Create Settings area menu item
    */
    public function settingsAreaMenuItem()
    {
        
        // create a settings menu
        add_options_page(
            __( $this->properties['page_title'], 'mxalfwp-domain' ),
            __( $this->properties['menu_title'], 'mxalfwp-domain' ),
            $this->properties['capability'],
            $this->subMenuSlug,
            [ $this, 'viewConnector' ]
        );

    }
        public function createSettingsLink( $links )
        {

            $settingsLink = '<a href="' . get_admin_url() . 'admin.php?page=' . $this->subMenuSlug . '">' . __( $this->properties['menu_title'], 'mxalfwp-domain' ) . '</a>'; // options-general.php

            array_unshift( $links, $settingsLink );

            return $links;

        }

        // connect view
        public function viewConnector()
        {

            if ($this->classAttributesError == NULL) {

                $classInstance = new $this->controller();

                call_user_func( [$classInstance, $this->action] );

            }

        }

}
