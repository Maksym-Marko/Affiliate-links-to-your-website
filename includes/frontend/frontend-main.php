<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_FrontEnd_Main
{

	/*
	* MXALTYW_FrontEnd_Main constructor
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
		mxaltyw_require_class_file_frontend( 'enqueue-scripts.php' );

		MXALTYW_Enqueue_Scripts_Frontend::mxaltyw_register();

		// shortcode
		mxaltyw_require_class_file_frontend( 'shortcode.php' );

		MXALTYW_Shortcode::create_shortcode();

	}

}

// Initialize
$initialize_admin_class = new MXALTYW_FrontEnd_Main();

// include classes
$initialize_admin_class->mxaltyw_additional_classes();