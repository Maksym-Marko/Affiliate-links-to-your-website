<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Enqueue_Scripts_Frontend
{

	/*
	* MXALTYW_Enqueue_Scripts_Frontend
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
		add_action( 'wp_enqueue_scripts', array( 'MXALTYW_Enqueue_Scripts_Frontend', 'mxaltyw_enqueue' ) );

	}

		public static function mxaltyw_enqueue()
		{

			wp_enqueue_style( 'mxaltyw_font_awesome', MXALTYW_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );
			
			wp_enqueue_style( 'mxaltyw_style', MXALTYW_PLUGIN_URL . 'includes/frontend/assets/css/style.css', array( 'mxaltyw_font_awesome' ), MXALTYW_PLUGIN_VERSION, 'all' );

			wp_enqueue_script( 'mxaltyw_cookies', MXALTYW_PLUGIN_URL . 'includes/frontend/assets/js/js.cookie.min.js', array( 'jquery' ), MXALTYW_PLUGIN_VERSION, false );
			
			wp_enqueue_script( 'mxaltyw_script', MXALTYW_PLUGIN_URL . 'includes/frontend/assets/js/script.js', array( 'jquery', 'mxaltyw_cookies' ), MXALTYW_PLUGIN_VERSION, false );

			wp_localize_script( 'mxaltyw_script', 'mxaltyw_frontend_script_localize', array(

				'ajaxurl' 		=> admin_url( 'admin-ajax.php' )

			) );
		
		}

}