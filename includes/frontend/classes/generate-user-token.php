<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Generate_User_Token
{

	/*
	* MXALTYW_Generate_User_Token
	*/
	public function __construct()
	{

	}

	/*
	* Observe function
	*/
	public static function mxaltyw_wp_frontend_ajax()
	{

		// generate token
		add_action( 'wp_ajax_mxaltyw_generate_user_token', array( 'MXALTYW_Generate_User_Token', 'prepare_generate_user_token' ), 10, 1 );

		// save token
		add_action( 'wp_ajax_mxaltyw_save_user_token', array( 'MXALTYW_Generate_User_Token', 'prepare_save_user_token' ), 10, 1 );		

	}

	// generate user token
	public static function prepare_generate_user_token()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxaltyw_nonce_user_token' ) ){

			// generate token
			echo wp_generate_password( 18, false );

		}

		wp_die();

	}

	// save user token
	public static function prepare_save_user_token()
	{

		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxaltyw_nonce_user_token' ) ){

			// save token
			$_update_user_meta = update_user_meta( get_current_user_id(), 'mxaltyw_token_key', sanitize_text_field( $_POST['token'] ) );

		}

		wp_die();

	}

}