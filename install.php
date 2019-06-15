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

		update_option( 'mxaltyw_affiliate_links', $options );

	}

	public static function deactivate()
	{

	}

}