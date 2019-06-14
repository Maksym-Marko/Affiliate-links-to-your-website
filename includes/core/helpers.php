<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Require class for admin panel
*/
function mxaltyw_require_class_file_admin( $file ) {

	require_once MXALTYW_PLUGIN_ABS_PATH . 'includes/admin/classes/' . $file;

}


/*
* Require class for frontend panel
*/
function mxaltyw_require_class_file_frontend( $file ) {

	require_once MXALTYW_PLUGIN_ABS_PATH . 'includes/frontend/classes/' . $file;

}

/*
* Require a Model
*/
function mxaltyw_use_model( $model ) {

	require_once MXALTYW_PLUGIN_ABS_PATH . 'includes/admin/models/' . $model . '.php';

}

/*
* find user by meta data
*/
function mxaltyw_get_user_by_meta_data( $meta_key, $meta_value ) {

	$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	$meta_key,
			'meta_value'	=>	$meta_value
		)
	);

	$users = $user_query->get_results();

	return $users[0];

}