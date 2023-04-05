<?php if (!is_user_logged_in()) : ?>

    <h2 class="mxalfwp-h2"><?php echo __('Please, Sign in to go to this page', 'mxalfwp-domain'); ?></h2>

    <?php if (in_array('woocommerce/woocommerce.php', get_option('active_plugins'), true)) : ?>

        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php echo __('Login', 'mxalfwp-domain'); ?></a>

    <?php endif; ?>

    <?php return; ?>

<?php endif; ?>

<?php

$userId = get_current_user_id();

$earned = mxalfwpPartnerEarnedAmount($userId);
$orders = mxalfwpPartnerAllCompetedOrders($userId);
$paid   = mxalfwpPartnerPaid($userId);

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
                <?php echo __('Analytics', 'mxalfwp-domain'); ?>
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
                    <?php echo __('Orders', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo $orders; ?>
                </div>

                <small><?php echo __('How many orders have been made through your affiliate links', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Earned -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Earned', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo get_option('mxalfwp_default_currency_sign') . ' ' . $earned; ?>
                </div>

                <small><?php echo __('How much did you earn', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Paid -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Paid', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo get_option('mxalfwp_default_currency_sign') . ' ' . $paid; ?>
                </div>

                <small><?php echo __('How much did you earn', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

    </div>

</div>