<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Main page Model
*/
class MXALTYW_Main_Page_Model extends MXALTYW_Model
{

	/*
	* Observe function
	*/
	public static function mxaltyw_wp_ajax()
	{

		add_action( 'wp_ajax_mxaltyw_update_option', array( 'MXALTYW_Main_Page_Model', 'prepare_update_options' ), 10, 1 );

	}

	/*
	* Prepare for data updates
	*/
	public static function prepare_update_options()
	{
		
		// Checked POST nonce is not empty
		if( empty( $_POST['nonce'] ) ) wp_die( '0' );

		// Checked or nonce match
		if( wp_verify_nonce( $_POST['nonce'], 'mxaltyw_nonce_request' ) ){

			// Update data
			self::update_options( $_POST );		

		}

		wp_die();

	}

		// Update data
		public static function update_options( $_post )
		{

			$options = array(

				'link_root' => $_post['mxaltyw_link_root'],

				'link_slug' => 'mxpartnerlink',


			);

			$serialize_option = maybe_serialize( $options );

			update_option( 'mxaltyw_affiliate_links', $serialize_option );

		}

	// get options
	public function mxaltyw_get_options()
	{

		$get_serialize_options = get_option( 'mxaltyw_affiliate_links' );

		$get_unserialize_options = maybe_unserialize( $get_serialize_options );

		$options = array(

			'link_root' => $get_unserialize_options['link_root'],

			'link_slug' => $get_unserialize_options['link_slug']

		);

		return $options;

	}
	
}