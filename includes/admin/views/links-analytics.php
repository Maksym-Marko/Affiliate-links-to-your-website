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

                            <a href="#" target="_blank" class="mxalfwp-common-link"><i class="fa fa-user" aria-hidden="true"></i> <?php echo __('Entire data of', 'mxalfwp-domain'); ?> <?php echo $data->user_name; ?></a>

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
                    <a href="<?php echo get_admin_url() . 'user-edit.php?user_id=' . $data->user_id; ?>" target="_blank" class="mxalfwp-common-link"><?php echo $data->user_name; ?></a>
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
                    <?php echo $data->link  . '/?mxpartnerlink=' . $data->user_id; ?>
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
                            $views += count( $value );
                        }
                
                        echo $views;
                    ?>
                </div>

                <small><?php echo __('Total number of page views', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

        <!-- Bought -->
        <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
            <div class="mxalfwp-white-box mxalfwp-analytics-info mxalfwp-text-center">
                <div class="mxalfwp-icon-box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h5 class="mxalfwp-box-title mxalfwp-mt-15">
                    <?php echo __('Bought', 'mxalfwp-domain'); ?>
                </h5>
                
                <div class="mxalfwp-counter mxalfwp-mb-15">
                    <?php echo $data->bought; ?>
                </div>

                <small><?php echo __('How many products have been purchased through the current affiliate link', 'mxalfwp-domain'); ?></small>

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
                    <?php echo $data->earned; ?>
                </div>

                <small><?php echo __('How much did the partner earn', 'mxalfwp-domain'); ?></small>

            </div>
        </div>

    </div>

    <?php mxalfwpAnalyticsPagesTableLayout(); ?>


    <h1><?php echo __('Edit Table Item', 'mxalfwp-domain'); ?></h1>



    <div class="mxalfwpmx_block_wrap">

        <form id="mxalfwp_form_update" class="mx-settings" method="post" action="">

            <input type="hidden" id="mxalfwp_id" name="mxalfwp_id" value="<?php echo $data->id; ?>" />

            <h2>This form is connected to this plugin's DB table</h2>

            <div>
                <label for="mxalfwp_title">Link</label>
                <br>
                <input type="text" name="mxalfwp_title" id="mxalfwp_title" value="<?php echo $data->link; ?>" />
            </div>
            <br>
            <div>
                <label for="mxalfwp_mx_description">Description</label>
                <br>
                <textarea name="mxalfwp_mx_description" id="mxalfwp_mx_description"></textarea>
            </div>

            <p class="mx-submit_button_wrap">
                <input type="hidden" id="mxalfwp_wpnonce" name="mxalfwp_wpnonce" value="<?php echo wp_create_nonce('mxalfwp_nonce_request'); ?>" />
                <input class="button-primary" type="submit" name="mxalfwp_submit" value="Save" />
            </p>

        </form>

    </div>

</div>