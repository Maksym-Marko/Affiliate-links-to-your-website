<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php $mxalfwp_link_data = maybe_unserialize($data->link_data); ?>

<div class="mxalfwp-sub-page-text-wrap">

    <!--  -->
    <div class="mxalfwp-page-breadcrumb mxalfwp-bg-white">
        <div class="mxalfwp-row mxalfwp-align-items-center">
            <div class="mxalfwp-col-lg-3 mxalfwp-col-md-4 mxalfwp-col-sm-4 mxalfwp-col-xs-12">
                <h4 class="mxalfwp-page-title">
                    <a href="<?php echo esc_url( admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG) ); ?>" class="mxalfwp-common-link"><i class="fa fa-chevron-left" aria-hidden="true"></i> All links</a> |
                    <?php echo esc_html__('Link Data', 'affiliate-links-woocommerce'); ?>
                </h4>
            </div>
            <div class="mxalfwp-col-lg-9 mxalfwp-col-sm-8 mxalfwp-col-md-8 mxalfwp-col-xs-12">

                <div class="mxalfwp-d-md-flex">
                    <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li class="mxalfwp-big-text">

                            <a href="<?php echo esc_url( admin_url('admin.php?page=mxalfwp-manage-partner&user_id=' . absint( $data->user_id )) ); ?>" class="mxalfwp-common-link"><i class="fa fa-user" aria-hidden="true"></i> <?php echo esc_html__('Entire data of', 'affiliate-links-woocommerce'); ?> <?php echo esc_html( $data->user_name ); ?></a>

                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- Section title -->
    <div class="mxalfwp-row">
        <div class="mxalfwp-col-md-12">
            <h3 class="mxalfwp-page-title mxalfwp-mt-30">
                <?php echo esc_html__('Analytics', 'affiliate-links-woocommerce'); ?>
            </h3>
        </div>
    </div>

    <!-- Section -->
    <div class="mxalfwp-row mxalfwp-justify-content-center mxalfwp-mt-15">

        <!-- User Name -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Partner', 'affiliate-links-woocommerce'); ?>
                </h5>
                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <a href="<?php echo esc_url( admin_url('admin.php?page=mxalfwp-manage-partner&user_id=' . absint( $data->user_id )) ); ?>" class="mxalfwp-common-link"><?php echo esc_html( $data->user_name ); ?></a>
                </div>
            </div>
        </div>

        <!-- Affiliate Link -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-link" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Affiliate Link', 'affiliate-links-woocommerce'); ?>
                </h5>
                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo esc_html( $data->link  . '/?mxpartnerlink=' . $data->link_key ); ?>
                </div>
            </div>
        </div>

        <!-- Pages -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-files-o" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Pages', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo absint( count($mxalfwp_link_data['data']) ); ?>
                </div>

                <small><?php echo esc_html__('The number of pages that users have visited through the current affiliate link', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- Views -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Views', 'affiliate-links-woocommerce'); ?>
                </h5>


                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php
                    $mxalfwp_views = 0;

                    foreach ($mxalfwp_link_data['data'] as $mxalfwp_key => $mxalfwp_value) {
                        $mxalfwp_views += count($mxalfwp_value);
                    }

                    echo absint( $mxalfwp_views );
                    ?>
                </div>

                <small><?php echo esc_html__('Total number of page views', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- All Orders -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('All Orders', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo absint( mxalfwpPartnerOrdersPerLink($data->user_id, $data->id) ); ?>
                </div>

                <small><?php echo esc_html__('How many orders have been made through the current affiliate link', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- Completed Orders -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Completed Orders', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo absint( mxalfwpPartnerCompletedOrdersPerLink($data->user_id, $data->id) ); ?>
                </div>

                <small><?php echo esc_html__('How many orders have been made through the current affiliate link', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- Earned Amoun -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Earned Amount', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo esc_html( get_option('mxalfwp_default_currency_sign') ) . ' ' . esc_html( mxalfwpGetCompletedOrdersAmountPerLink($data->id) ); ?>
                </div>

                <small><?php echo esc_html__('How much did the current link make money', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

        <!-- Earned by Partner -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo esc_html__('Earned by Partner', 'affiliate-links-woocommerce'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo esc_html( get_option('mxalfwp_default_currency_sign') ) . ' ' . esc_html( mxalfwpGetPartnerCompletedOrdersAmountPerLink($data->id) ); ?>
                </div>

                <small><?php echo esc_html__('How much did the partner earn using this link', 'affiliate-links-woocommerce'); ?></small>

            </div>
        </div>

    </div>

    <!-- Section title -->
    <div class="mxalfwp-row">
        <div class="mxalfwp-col-md-12">
            <h3 class="mxalfwp-page-title mxalfwp-mt-30">
                <?php echo esc_html__('Pages visited through the current affiliate link', 'affiliate-links-woocommerce'); ?>
            </h3>
        </div>
    </div>

    <?php mxalfwpAnalyticsPagesTableLayout($mxalfwp_link_data); ?>

</div>
