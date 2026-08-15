<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if (!is_user_logged_in()) : ?>

    <h2 class="mxalfwp-h2"><?php esc_html_e('Please, Sign in to go to this page', 'affiliate-links-woocommerce'); ?></h2>

    <?php if (in_array('woocommerce/woocommerce.php', get_option('active_plugins'), true)) : ?>

        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e('Login', 'affiliate-links-woocommerce'); ?></a>

    <?php endif; ?>

    <?php return; ?>

<?php endif; ?>

<?php

$mxalfwp_user_id = get_current_user_id();

$mxalfwp_earned = mxalfwpPartnerEarnedAmount($mxalfwp_user_id);
$mxalfwp_orders = mxalfwpPartnerAllCompetedOrders($mxalfwp_user_id);
$mxalfwp_paid   = mxalfwpPartnerPaid($mxalfwp_user_id);

?>

<div class="mxalfwp_partner_cabinet">

    <div id="mxalfwp_cabinet">

        <mxalfwp_c_form :translation='translation' :ajaxdata="ajaxdata" :toquerystring="toQueryString" :getcurrentuserlinks="getCurrentUserLinks" :partnerstatus="partnerStatus"></mxalfwp_c_form>

        <mxalfwp_c_table :translation='translation' :links='links'></mxalfwp_c_table>

        <mxalfwp_c_pagination :count="linksCount" :perpage="perPage" :currentpage="currentPage" :pageloading="pageLoading" @mxalfwp-get-page="setPage"></mxalfwp_c_pagination>

    </div>

</div>

<div class="mxalfwp-sub-page-text-wrap">

    <!-- Section title -->
    <div class="mxalfwp-row">
        <div class="mxalfwp-col-md-12">
            <h3 class="mxalfwp-page-title mxalfwp-mt-30">
                <?php esc_html_e('Analytics', 'affiliate-links-woocommerce'); ?>
            </h3>
        </div>
    </div>

    <!-- Section -->
    <div class="mxalfwp-row mxalfwp-justify-content-center mxalfwp-mt-15">

        <!-- Orders -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php esc_html_e('Orders', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo absint( $mxalfwp_orders ); ?>
                </div>

                <small><?php esc_html_e('How many orders have been made through your affiliate links', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- Earned -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php esc_html_e('Earned', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo esc_html( get_option('mxalfwp_default_currency_sign') ) . ' ' . esc_html( $mxalfwp_earned ); ?>
                </div>

                <small><?php esc_html_e('How much did you earn', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- Paid -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php esc_html_e('Paid', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo esc_html( get_option('mxalfwp_default_currency_sign') ) . ' ' . esc_html( $mxalfwp_paid ); ?>
                </div>

                <small><?php esc_html_e('How much did you earn', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

    </div>

</div>