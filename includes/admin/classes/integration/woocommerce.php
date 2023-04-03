<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPIntegrationWoocommerce extends MXALFWPIntegration
{

    public function registerActions()
    {

        // if woocommerce doesn't activeate = return
        if(!in_array('woocommerce/woocommerce.php', get_option('active_plugins'), true)) {
            add_action( 'mxalfwp_affiliate_links_before_table', [$this, 'woocommerceRequiredMessage'] );
            return;
        }

        add_action( 'woocommerce_order_status_changed', [$this, 'manageOrders'], 10, 3 );
        
    }

    public function woocommerceRequiredMessage()
    { ?>
        <div class="mxalfwp-p20 mxalfwp-danger-box mxalfwp-analytics-info mxalfwp-text-center">
            <h2 class="mxalfwp-page-title"><?php echo __( 'Please install and activeate WooCommerce plugin', 'mxalfwp-domain' ); ?></h2>
        </div>
    <?php }

    public function manageOrders($id, $previous_status, $next_status)
    {
        // checking
        $this->addPurchase();
        $this->cancelPurchase();

        // mxalfwpDebugToFile([
        //     'id' => $id,
        //     'previous_status' => $previous_status,
        //     'next_status' => $next_status
        // ]);

    }

    public function addPurchase()
    {

       

    }

    public function cancelPurchase()
    {

       

    }

}

(new MXALFWPIntegrationWoocommerce)->registerActions();