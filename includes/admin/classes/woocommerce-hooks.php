<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXALTYW_Woocommerce_Hook
{

	/*
	* MXALTYW_Woocommerce_Hook
	*/
	public function __construct()
	{

	}

	/*
	* Registration of styles and scripts
	*/

	public function set_woocommerce_hooks()
	{

		// add affiliate field
		add_filter( 'woocommerce_checkout_fields', array( $this, 'mxaltyw_checkout_affiliate_field' ) );

		add_action( 'woocommerce_checkout_after_customer_details', array( $this, 'mxaltyw_checkout_extra_affiliate_field' ) );

		// save data
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'mxaltyw_checkout_save_affiliate_field' ), 10, 2 );
		
		// show data for admin
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'mxaltyw_display_order_data_in_admin' ) );

	}

		// 
		public function mxaltyw_checkout_affiliate_field($fields){
		    $fields['mxaltyw_extra_fields'] = array(

		            'mxaltyw_text_field' => array(
		                'type' 			=> 'text',
		                'required'  	=> false,
		                'label' 		=> '',
		                'input_class' 	=> array( 'mx-affiliate-link-checkout-input' )
		            )

		    );

		    return $fields;
		}

		// 
		public function mxaltyw_checkout_extra_affiliate_field(){

		    $checkout = WC()->checkout(); ?>

		    <?php
		       foreach ( $checkout->checkout_fields['mxaltyw_extra_fields'] as $key => $field ) : ?>

		            <?php woocommerce_form_field( $key, $field, '' ); ?>

		    <?php endforeach; ?>

		<?php }

		// save data
		public function mxaltyw_checkout_save_affiliate_field( $order_id, $posted ){

			if( isset( $posted['mxaltyw_text_field'] ) ) {

		        update_post_meta( $order_id, '_mxaltyw_text_field', sanitize_text_field( $posted['mxaltyw_text_field'] ) );

		    }

		}

		// show for admin
		public function mxaltyw_display_order_data_in_admin( $order ){  

			if( get_post_meta( $order->id, '_mxaltyw_text_field', true ) !== '' ) :

				if( ( mxaltyw_get_user_by_meta_data( 'mxaltyw_token_key', get_post_meta( $order->id, '_mxaltyw_text_field', true ) ) !== NULL ) ) :

				?>

				    <div class="order_data_column">
				        <h4>
				        	<?php _e( 'The affiliate link by ', 'woocommerce' ); ?>
				        	<?php
				        		echo '<a href="' . get_admin_url() . 'profile.php?' . mxaltyw_get_user_by_meta_data( 'mxaltyw_token_key', get_post_meta( $order->id, '_mxaltyw_text_field', true ) )->user_nicename . '" target="_blank">' . mxaltyw_get_user_by_meta_data( 'mxaltyw_token_key', get_post_meta( $order->id, '_mxaltyw_text_field', true ) )->display_name . '</a>';
				        	?>
				        </h4>
				        <div class="mx-affiliate-link">
				        <?php
				            // echo '<p><strong>' . __( 'The affiliate link by' ) . ': </strong>' . get_post_meta( $order->id, '_mxaltyw_text_field', true ) . '</p>';
				        ?>
				        </div>
				    </div>

				<?php endif;
				
			endif;
		}

}