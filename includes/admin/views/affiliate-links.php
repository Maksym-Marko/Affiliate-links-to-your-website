<div class="mx-main-page-text-wrap">

    <!--  -->
    <div class="mxalfwp-page-breadcrumb mxalfwp-bg-white">
        <div class="mxalfwp-row mxalfwp-align-items-center">
            <div class="mxalfwp-col-lg-3 mxalfwp-col-md-4 mxalfwp-col-sm-4 mxalfwp-col-xs-12">
                <h4 class="mxalfwp-page-title"><?php echo __('Affiliate Links Data', 'mxalfwp-domain'); ?></h4>
            </div>
            <div class="mxalfwp-col-lg-9 mxalfwp-col-sm-8 mxalfwp-col-md-8 mxalfwp-col-xs-12">

                <div class="mxalfwp-d-md-flex">
                    <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li class="mxalfwp-big-text">
                            <?php echo __('Use this short code to create a cabinet for your partners: ', 'mxalfwp-domain'); ?><b>[mxalfwp_partner_cabinet]</b>
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <?php do_action( 'mxalfwp_affiliate_links_before_table' ); ?>
    
    <div class="wrap">

        <?php
        mxalfwpTableLayout();
        ?>

    </div>

    <?php do_action( 'mxalfwp_affiliate_links_after_table' ); ?>

</div>