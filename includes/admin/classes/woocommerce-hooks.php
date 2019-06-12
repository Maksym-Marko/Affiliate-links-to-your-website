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
		add_filter( 'woocommerce_checkout_fields', array( $this, 'cloudways_custom_checkout_fields' ) );

		add_action( 'woocommerce_checkout_after_customer_details', array( $this, 'cloudways_extra_checkout_fields' ) );

		// save data
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'cloudways_save_extra_checkout_fields' ), 10, 2 );

		// display data
		add_action( 'woocommerce_thankyou', array( $this, 'cloudways_display_order_data' ), 20 );
		add_action( 'woocommerce_view_order', array( $this, 'cloudways_display_order_data' ), 20 );

		// show data for admin
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'cloudways_display_order_data_in_admin' ) );

	}

		// 
		public function cloudways_custom_checkout_fields($fields){
		    $fields['cloudways_extra_fields'] = array(

		            'cloudways_text_field' => array(
		                'type' => 'text',
		                'required'      => true,
		                'label' => __( 'Input Text Field' ),
		                'value' => 'dsdsds'
		            )

		    );

		    return $fields;
		}

		// 
		public function cloudways_extra_checkout_fields(){
		    $checkout = WC()->checkout(); ?>
		    <div class="extra-fields">
		    <h3><?php _e( 'Additional Fields' ); ?></h3>
		    <?php
		       foreach ( $checkout->checkout_fields['cloudways_extra_fields'] as $key => $field ) : ?>
		            <?php woocommerce_form_field( $key, $field, '43432' ); ?>
		        <?php endforeach; ?>
		    </div>
		<?php }

		// save data
		public function cloudways_save_extra_checkout_fields( $order_id, $posted ){
		    // don't forget appropriate sanitization if you are using a different field type
		    if( isset( $posted['cloudways_text_field'] ) ) {
		        update_post_meta( $order_id, '_cloudways_text_field', sanitize_text_field( $posted['cloudways_text_field'] ) );
		    }
		}

		// show for user
		public function cloudways_display_order_data( $order_id ){  ?>
		    <h2><?php _e( 'Extra Information' ); ?></h2>
		    <table class="shop_table shop_table_responsive additional_info">
		        <tbody>
		            <tr>
		                <th><?php _e( 'Input Text Field:' ); ?></th>
		                <td><?php echo get_post_meta( $order_id, '_cloudways_text_field', true ); ?></td>
		            </tr>
		        </tbody>
		    </table>
		<?php }

		// show for admin
		public function cloudways_display_order_data_in_admin( $order ){  ?>
		    <div class="order_data_column">
		        <h4><?php _e( 'Additional Information', 'woocommerce' ); ?><a href="#" class="edit_address"><?php _e( 'Edit', 'woocommerce' ); ?></a></h4>
		        <div class="address">
		        <?php
		            echo '<p><strong>' . __( 'Text Field' ) . ':</strong>' . get_post_meta( $order->id, '_cloudways_text_field', true ) . '</p>';?>
		        </div>
		        <div class="edit_address">
		            <?php woocommerce_wp_text_input( array( 'id' => '_cloudways_text_field', 'label' => __( 'Some field' ), 'wrapper_class' => '_billing_company_field' ) ); ?>
		        </div>
		    </div>
		<?php }


}