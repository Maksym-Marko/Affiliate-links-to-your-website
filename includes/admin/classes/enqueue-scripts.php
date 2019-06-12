<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Enqueue_Scripts
{

	/*
	* MXALTYW_Enqueue_Scripts
	*/
	public function __construct()
	{

	}

	/*
	* Registration of styles and scripts
	*/
	public static function mxaltyw_register()
	{

		// register scripts and styles
		add_action( 'admin_enqueue_scripts', array( 'MXALTYW_Enqueue_Scripts', 'mxaltyw_enqueue' ) );

	}

		public static function mxaltyw_enqueue()
		{

			wp_enqueue_style( 'mxaltyw_font_awesome', MXALTYW_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );

			wp_enqueue_style( 'mxaltyw_admin_style', MXALTYW_PLUGIN_URL . 'includes/admin/assets/css/style.css', array( 'mxaltyw_font_awesome' ), MXALTYW_PLUGIN_VERSION, 'all' );

			wp_enqueue_script( 'mxaltyw_admin_script', MXALTYW_PLUGIN_URL . 'includes/admin/assets/js/script.js', array( 'jquery' ), MXALTYW_PLUGIN_VERSION, false );
			
		}

}