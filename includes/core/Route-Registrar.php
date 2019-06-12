<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Route_Registrar
{
	
	/**
	* set controller
	*/
	public $controller = '';

	/**
	* set action
	*/
	public $action = '';

	/**
	* set slug or parent menu slug
	*/
	public $slug = MXALTYW_MAIN_MENU_SLUG;

	/**
	* catch class error
	*/
	public $class_attributes_error = NULL;

	/**
	* set properties
	*/
	public $properties = [
		'page_title' 	=> 'Title of the page',
		'menu_title' 	=> 'Link Name',
		'capability' 	=> 'manage_options',
		'menu_slug' 	=> MXALTYW_MAIN_MENU_SLUG,
		'dashicons' 	=> 'dashicons-image-filter',
		'position' 		=> 111
	];

	/**
	* set slug of sub menu
	*/
	public $sub_menu_slug = false;

	/**
	* MXALTYW_Route_Registrar constructor
	*/
	public function __construct( ...$args )
	{

		// set data
		$this->mxaltyw_set_data( ...$args );

	}

	/**
	* require class
	*/
	public function mxaltyw_require_controller( $controller )
	{

		if( file_exists( MXALTYW_PLUGIN_ABS_PATH . "includes/admin/controllers/{$controller}.php" ) ) {

			require_once MXALTYW_PLUGIN_ABS_PATH . "includes/admin/controllers/{$controller}.php";

		}

	}

	/**
	* $controller 		- Controller
	*
	* $action 			- Action
	*
	* $slug 			- if NULL - menu item will investment into
	*						MXALTYW_MAIN_MENU_SLUG menu item
	*
	* $menu_properties 	- menu properties
	*
	* $sub_menu_slug 	- slug of sub menu
	*
	*/
	public function mxaltyw_set_data( $controller, $action, $slug = MXALTYW_MAIN_MENU_SLUG, array $menu_properties, $sub_menu_slug = false )
	{

		// set controller
		$this->controller = $controller;

		// set action
		$this->action = $action;

		// set slug
		if( $slug == NULL ) {

			$this->slug = MXALTYW_MAIN_MENU_SLUG;

		} else {

			$this->slug = $slug;

		}

		// set properties
		foreach ( $menu_properties as $key => $value ) {
			
			$this->properties[$key] = $value;

		}

		// callback function
		$mxaltyw_callback_function_menu = 'mxaltyw_create_admin_main_menu';

		/*
		* check if it's submenu
		* set sub_menu_slug
		*/
		if( $sub_menu_slug !== false ) {

			$this->sub_menu_slug = $sub_menu_slug;

			$mxaltyw_callback_function_menu = 'mxaltyw_create_admin_sub_menu';
			
		}

		/**
		* require controller
		*/
		$this->mxaltyw_require_controller( $this->controller );

		/**
		* catching errors of class attrs
		*/
		$is_error_class_atr = MXALTYW_Catching_Errors::mxaltyw_catch_class_attributes_error( $this->controller, $this->action );
		
		// catch error class attr
		if( $is_error_class_atr !== NULL ) {

			$this->class_attributes_error = $is_error_class_atr;

		}

		// register admin menu
		add_action( 'admin_menu', array( $this, $mxaltyw_callback_function_menu ) );

	}

	/**
	* Create Main menu
	*/
	public function mxaltyw_create_admin_main_menu()
	{

		add_menu_page( __( $this->properties['page_title'], 'mxaltyw-domain' ),
			 __( $this->properties['menu_title'], 'mxaltyw-domain' ),
			 $this->properties['capability'],
			 $this->slug,
			 array( $this, 'mxaltyw_view_connector' ),
			 $this->properties['dashicons'], // icons https://developer.wordpress.org/resource/dashicons/#id
			 $this->properties['position'] );

	}

	/**
	* Create Sub menu
	*/
	public function mxaltyw_create_admin_sub_menu()
	{
		
		// create a menu
		add_submenu_page( $this->slug,
			 __( $this->properties['page_title'], 'mxaltyw-domain' ),
			 __( $this->properties['menu_title'], 'mxaltyw-domain' ),
			 $this->properties['capability'],
			 $this->sub_menu_slug,
			 array( $this, 'mxaltyw_view_connector' )
		);

	}

		// connect view
		public function mxaltyw_view_connector()
		{

			if( $this->class_attributes_error == NULL ) {

				$class_inst = new $this->controller();

				call_user_func( array( $class_inst, $this->action ) );

			}
			
		}

}