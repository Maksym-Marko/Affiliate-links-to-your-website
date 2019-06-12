<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Shortcode
{

	/*
	* MXALTYW_Shortcode
	*/
	public function __construct()
	{

	}

	/*
	* Create shortcode
	*/
	public static function create_shortcode()
	{

		$_data = array(

			'link_root' => get_site_url(),

			'link_slug' => 'mxpartnerlink'

		);

		add_shortcode( 'mxaltyw_affiliate_link', function() use ( $_data ) {

			ob_start(); ?>

				<div><?php echo $_data['link_root']; ?>?<?php echo $_data['link_slug']; ?>=true&usertoken=fiJIEbeHFEm</div>

			<?php

			return ob_get_clean();


		} );

	}
	

}