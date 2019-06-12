<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Error Handle calss
*/
class MXALTYW_Display_Error
{

	/**
	* Error notice
	*/
	public $mxaltyw_error_notice = '';

	public function __construct( $mxaltyw_error_notice )
	{

		$this->mxaltyw_error_notice = $mxaltyw_error_notice;

	}

	public function mxaltyw_show_error()
	{
		
		add_action( 'admin_notices', function() { ?>

			<div class="notice notice-error is-dismissible">

			    <p><?php echo $this->mxaltyw_error_notice; ?></p>
			    
			</div>
		    
		<?php } );

	}

}