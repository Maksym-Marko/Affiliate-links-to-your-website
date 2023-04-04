<?php $link_data = maybe_unserialize($data->link_data); ?>

<div class="mxalfwp-sub-page-text-wrap">

    <!--  -->
    <div class="mxalfwp-page-breadcrumb mxalfwp-bg-white">
        <div class="mxalfwp-row mxalfwp-align-items-center">
            <div class="mxalfwp-col-lg-3 mxalfwp-col-md-4 mxalfwp-col-sm-4 mxalfwp-col-xs-12">
                <h4 class="mxalfwp-page-title">
                    <a href="<?php echo admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG); ?>" class="mxalfwp-common-link"><i class="fa fa-chevron-left" aria-hidden="true"></i> All links</a> |
                    <?php echo __('Link Data', 'mxalfwp-domain'); ?>
                </h4>
            </div>
            <div class="mxalfwp-col-lg-9 mxalfwp-col-sm-8 mxalfwp-col-md-8 mxalfwp-col-xs-12">

                <div class="mxalfwp-d-md-flex">
                    <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li class="mxalfwp-big-text">

                            <a href="<?php echo admin_url('admin.php?page=mxalfwp-manage-partner&user_id=' . $data->user_id); ?>" class="mxalfwp-common-link"><i class="fa fa-user" aria-hidden="true"></i> <?php echo __('Entire data of', 'mxalfwp-domain'); ?> <?php echo $data->user_name; ?></a>

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
                <?php echo __('Analytics', 'mxalfwp-domain'); ?>
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
                    <?php echo __('Partner', 'mxalfwp-domain'); ?>
                </h5>
                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <a href="<?php echo admin_url('admin.php?page=mxalfwp-manage-partner&user_id=' . $data->user_id); ?>" class="mxalfwp-common-link"><?php echo $data->user_name; ?></a>
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
                    <?php echo __('Affiliate Link', 'mxalfwp-domain'); ?>
                </h5>
                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo $data->link  . '/?mxpartnerlink=' . $data->link_key; ?>
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
                    <?php echo __('Pages', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo count($link_data['data']); ?>
                </div>

                <small><?php echo __('The number of pages that users have visited through the current affiliate link', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Views -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Views', 'mxalfwp-domain'); ?>
                </h5>


                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php
                    $views = 0;

                    foreach ($link_data['data'] as $key => $value) {
                        $views += count($value);
                    }

                    echo $views;
                    ?>
                </div>

                <small><?php echo __('Total number of page views', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- All Orders -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('All Orders', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo mxalfwpPartnerOrdersPerLink($data->user_id, $data->id); ?>
                </div>

                <small><?php echo __('How many orders have been made through the current affiliate link', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Completed Orders -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Completed Orders', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo mxalfwpPartnerCompletedOrdersPerLink($data->user_id, $data->id); ?>
                </div>

                <small><?php echo __('How many orders have been made through the current affiliate link', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Earned Amoun -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Earned Amount', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo get_option('mxalfwp_default_currency_sign') . ' ' . mxalfwpGetCompletedOrdersAmountPerLink($data->id); ?>
                </div>

                <small><?php echo __('How much did the current link make money', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Earned by Partner -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Earned by Partner', 'mxalfwp-domain'); ?>
                </h5>

                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo get_option('mxalfwp_default_currency_sign') . ' ' . mxalfwpGetPartnerCompletedOrdersAmountPerLink($data->id); ?>
                </div>

                <small><?php echo __('How much did the partner earn using this link', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

    </div>

    <!-- Section title -->
    <div class="mxalfwp-row">
        <div class="mxalfwp-col-md-12">
            <h3 class="mxalfwp-page-title mxalfwp-mt-30">
                <?php echo __('Pages visited through the current affiliate link', 'mxalfwp-domain'); ?>
            </h3>
        </div>
    </div>

    <?php mxalfwpAnalyticsPagesTableLayout($link_data); ?>

</div>