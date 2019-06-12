<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// require Display_Error.php
require_once MXALTYW_PLUGIN_ABS_PATH . 'includes/core/error_handle/Display-Error.php';

// handle error
require_once MXALTYW_PLUGIN_ABS_PATH . 'includes/core/error_handle/Error-Handle.php';

/*
* Cathing errors calss
*/
class MXALTYW_Catching_Errors
{

	/**
	* Show notification missing class or methods
	*/
	public static function mxaltyw_catch_class_attributes_error( $class_name, $method )
	{

		$error_class_inst = new MXALTYW_Error_Handle();

		$error_display = $error_class_inst->mxaltyw_class_attributes_error( $class_name, $method );

		$error = NULL;

		if( $error_display !== true ) {

			$error = $error_display;

		}		

		return $error;

	}

}