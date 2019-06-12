<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Main_Page_Controller extends MXALTYW_Controller
{
	
	public function index()
	{

		$model_inst = new MXALTYW_Main_Page_Model();

		$data = $model_inst->mxaltyw_get_options();

		return new MXALTYW_View( 'main-page', $data );

	}

}