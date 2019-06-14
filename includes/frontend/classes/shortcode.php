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

		$get_serialize_options = get_option( 'mxaltyw_affiliate_links' );

		$get_unserialize_options = maybe_unserialize( $get_serialize_options );

		$options = array(

			'link_root' => $get_unserialize_options['link_root'],

			'link_slug' => $get_unserialize_options['link_slug']

		);

		add_shortcode( 'mxaltyw_affiliate_link', function() use ( $options ) {

			ob_start(); 

				// if user logged in
				if( is_user_logged_in() ) :

					// if doesn't have token
					if( get_user_meta( get_current_user_id(), 'mxaltyw_token_key', true ) == NULL ) :

					?>

						<form action="" id="mx_create_affiliate_link_form">
							
							<div class="mx_create_affiliate_link">
								
								<p class="mx_create_affiliate_link_success" style="display: none;">
									<b>
										<?php echo __( 'Now you can use this link to invite users to buy something on our site.', 'mxaltyw-domain' ); ?>
									</b>							
								</p>

								<p class="mx_create_affiliate_link_show_to_user" style="display: none;">
									<?php echo $options['link_root']; ?>?<?php echo $options['link_slug']; ?>=true&usertoken=<span>USER_TOKEN</span>
								</p>					

								<input type="hidden" id="mx_create_affiliate_link_input" />

								<a href="#" id="mx_create_affiliate_link_button"><?php echo __( 'Create Affiliate link', 'mxaltyw-domain' ); ?></a>

							</div>

							<div class="mx_save_affiliate_link_button_wrap">

								<input type="hidden" id="mxaltyw_nonce_user_token" name="mxaltyw_nonce_user_token" value="<?php echo wp_create_nonce( 'mxaltyw_nonce_user_token' ) ;?>" />

								<button id="mx_save_affiliate_link_button" style="display: none;"><?php echo __( 'Save and start cooperation', 'mxaltyw-domain' ); ?></button>

							</div>
							

						</form>

					<?php

				else : ?>

					<p class="mx_affiliate_link_exists">
						<b>
							<?php echo __( 'You\'ve already created an affiliate link.', 'mxaltyw-domain' ); ?>
						</b>
					</p>

					<p class="mx_affiliate_link_exists_body">
						<?php echo $options['link_root']; ?>?<?php echo $options['link_slug']; ?>=true&usertoken=<?php echo get_user_meta( get_current_user_id(), 'mxaltyw_token_key', true ); ?>
					</p>

				<?php endif;

			else : ?>

				<p>					
					<b><?php
						echo __( 'You must be logged in to create an affiliate link. Please, log in or registration.', 'mxaltyw-domain' );
					?></b>
					<a href="<?php echo get_site_url(); ?>/wp-login.php?action=register"><?php echo __( 'Register For This Site', 'mxaltyw-domain' ); ?></a> 
					<?php echo __( 'or', 'mxaltyw-domain' ); ?> 
					<a href="<?php echo get_site_url(); ?>/wp-login.php"><?php echo __( 'Log In', 'mxaltyw-domain' ); ?></a>
				</p>				

			<?php endif;

			return ob_get_clean();


		} );

	}
	

}