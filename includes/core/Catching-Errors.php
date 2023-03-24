<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// require Display-Error.php
require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/error_handle/Display-Error.php';

// handle error
require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/error_handle/Error-Handle.php';

/*
* Cathing errors calss
*/
class MXALFWPCatchingErrors
{

	/**
	* Show notification missing class or methods
	*/
	public static function catchClassAttributesError( $className, $method )
	{

		$errorClassInstance = new MXALFWPErrorHandle();

		$errorDisplay = $errorClassInstance->classAttributesError( $className, $method );

		$error = NULL;

		if ($errorDisplay !== true) {
			$error = $errorDisplay;
		}		

		return $error;

	}

}
