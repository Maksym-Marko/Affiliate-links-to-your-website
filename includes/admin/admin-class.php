<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Admin_Main
{

	// list of model names used in the plugin
	public $models_collection = [
		'MXALTYW_Main_Page_Model'
	];

	/*
	* MXALTYW_Admin_Main constructor
	*/
	public function __construct()
	{

	}

	/*
	* Additional classes
	*/
	public function mxaltyw_additional_classes()
	{

		// enqueue_scripts class
		mxaltyw_require_class_file_admin( 'enqueue-scripts.php' );

		MXALTYW_Enqueue_Scripts::mxaltyw_register();

		// woocommerce hooks
		mxaltyw_require_class_file_admin( 'woocommerce-hooks.php' );

		$woocommerce_hooks_instance = new MXALTYW_Woocommerce_Hook();

		$woocommerce_hooks_instance->set_woocommerce_hooks();

	}

	/*
	* Models Connection
	*/
	public function mxaltyw_models_collection()
	{

		// require model file
		foreach ( $this->models_collection as $model ) {
			
			mxaltyw_use_model( $model );

		}		

	}

	/**
	* registration ajax actions
	*/
	public function mxaltyw_registration_ajax_actions()
	{

		// ajax requests to main page
		MXALTYW_Main_Page_Model::mxaltyw_wp_ajax();

	}

	/*
	* Routes collection
	*/
	public function mxaltyw_routes_collection()
	{

		// main menu item
		MXALTYW_Route::mxaltyw_get( 'MXALTYW_Main_Page_Controller', 'index', '', [
			'page_title' => 'Affiliate links Settings',
			'menu_title' => 'Affiliate links',
			'dashicons' => 'dashicons-rest-api'
		] );

	}

}

// Initialize
$initialize_admin_class = new MXALTYW_Admin_Main();

// include classes
$initialize_admin_class->mxaltyw_additional_classes();

// include models
$initialize_admin_class->mxaltyw_models_collection();

// ajax requests
$initialize_admin_class->mxaltyw_registration_ajax_actions();

// include controllers
$initialize_admin_class->mxaltyw_routes_collection();