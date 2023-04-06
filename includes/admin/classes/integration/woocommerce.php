<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPIntegrationWoocommerce
{

    public function registerActions()
    {

        // if woocommerce doesn't activeate = return
        if (!in_array('woocommerce/woocommerce.php', get_option('active_plugins'), true)) {
            add_action('mxalfwp_affiliate_links_before_table', [$this, 'woocommerceRequiredMessage']);
            return;
        }

        // orders status changes
        add_action('woocommerce_order_status_changed', [$this, 'manageOrders'], 10, 3);

        // show data for admin
		add_action( 'woocommerce_admin_order_data_after_order_details', [$this, 'displayPartnerInOrder'] );

    }

    public function displayPartnerInOrder($order)
    { 

        $orderData = mxalfwpGetOrderById($order->get_id());

        if(!$orderData) return;

        $user = get_user_by( 'id', $orderData['user_id'] );

?>
        <br>
        <p class="form-field form-field-wide wc-affiliate-partner">

            <?php echo __('Affiliate link by ', 'mxalfwp-domain'); ?>
            <a href="<?php echo admin_url('admin.php?page=mxalfwp-manage-partner&user_id=' . $orderData['user_id']); ?>" class="mxalfwp-common-link" target="_blank"><?php echo $user->display_name;?></a>

        </p>

<?php
    }

    public function woocommerceRequiredMessage()
    { ?>
        <div class="mxalfwp-p20 mxalfwp-analytics-info mxalfwp-text-center">
            <p class="notice notice-warning">
                <?php echo __('WooCommerce plugin is not activated. You can still use "Affiliate Links Expert" plugin to creating affiliate links and tracking visit data. You cannot track the number of items purchased. To track the number of items purchased, use the WooCommerce plugin or integrate your payment methods with the "Affiliate Links Expert" plugin.', 'mxalfwp-domain'); ?>
                <?php echo __('Contact', 'mxalfwp-domain'); ?> <a href="https://markomaksym.com.ua/" target="_blank">Maksym Marko</a> - <?php echo __('the plugin\'s creator, if you need help!', 'mxalfwp-domain'); ?>
            </p>
        </div>
<?php }

    public function manageOrders($id, $previous_status, $next_status)
    {
        
        $inst       = new MXALFWPMainAdminModel();

        // looking for in orders table
        $orderData  = $inst->getRow(MXALFWP_ORDERS_TABLE_SLUG, 'order_id', intval($id));
        
        // date
        $date = date('Y-m-d H:i:s');

        // create order
        if($orderData == NULL) {

            // if no cookies
            if (!isset($_COOKIE['mxalfwpLinkIdentifier'])) return;

            $linkKey    = sanitize_text_field($_COOKIE['mxalfwpLinkIdentifier']);

            $and        = "AND link_key = '$linkKey' AND status = 'active'";

            $linkData   = $inst->getRow(NULL, 1, 1, $and);

            if ($linkData == NULL) return;

            $orderWC    = wc_get_order($id);

            $amount     = $orderWC->get_total();

            $insert = $inst->insertRow(
                MXALFWP_ORDERS_TABLE_SLUG,
                [
                    'order_id'   => $id,
                    'user_id'    => $linkData->user_id,
                    'link_id'    => $linkData->id,
                    'status'     => $next_status,
                    'amount'     => $amount,
                    'created_at' => $date,
                    'updated_at' => $date,
                ],
                [
                    '%d',
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                ]
            );

            return;

        }

        // update order
        if($orderData !== NULL) {

            // get link key by order id
            $linkKey = mxalfwpGetLinkKeyByOrderId($id);

            if (!$linkKey) return;

            $linkKey    = sanitize_text_field($linkKey);

            $and        = "AND link_key = '$linkKey' AND status = 'active'";

            $linkData   = $inst->getRow(NULL, 1, 1, $and);

            if ($linkData == NULL) return;

            $orderWC    = wc_get_order($id);

            $amount     = $orderWC->get_total();

            // update
            $updated = $inst->updateRow(
                MXALFWP_ORDERS_TABLE_SLUG,
                'order_id',
                intval($id),
                [
                    'status'     => $next_status,
                    'amount'     => $amount,
                    'updated_at' => $date,
                ],
                [
                    '%s',
                    '%d',
                    '%s',
                ]
            );

            return;

        }

        return;

    }
}

(new MXALFWPIntegrationWoocommerce)->registerActions();
