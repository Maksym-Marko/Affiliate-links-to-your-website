<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// require Route-Registrar.php
require_once MXALTYW_PLUGIN_ABS_PATH . 'includes/core/Route-Registrar.php';

/*
* Routes class
*/
class MXALTYW_Route
{

	public function __construct()
	{
		// ...
	}
	
	public static function mxaltyw_get( ...$args )
	{

		return new MXALTYW_Route_Registrar( ...$args );

	}
	
}