<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class MXALTYW_Basis_Plugin_Class
{

	public static function activate()
	{

		$options = array(

			'link_root' => get_site_url(),

			'link_slug' => 'mxpartnerlink'


		);

		$serialize_option = maybe_serialize( $options );

		update_option( 'mxaltyw_affiliate_links', $serialize_option );

	}

	public static function deactivate()
	{

	}

}