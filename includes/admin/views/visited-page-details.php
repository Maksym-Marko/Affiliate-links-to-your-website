<div class="mxalfwp-sub-page-text-wrap">

    <!--  -->
    <div class="mxalfwp-page-breadcrumb mxalfwp-bg-white">
        <div class="mxalfwp-row mxalfwp-align-items-center">
            <div class="mxalfwp-col-lg-3 mxalfwp-col-md-4 mxalfwp-col-sm-4 mxalfwp-col-xs-12">
                <h4 class="mxalfwp-page-title">
                    <a href="<?php echo admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG); ?>" class="mxalfwp-common-link"><i class="fa fa-chevron-left" aria-hidden="true"></i> All links</a> |

                    <a href="<?php echo admin_url('admin.php?page=' . MXALFWP_SINGLE_TABLE_ITEM_MENU); ?>&mxalfwp-link-id=<?php echo $data['linkData']->id ?>" class="mxalfwp-common-link"><?php echo __('Link Data', 'mxalfwp-domain'); ?></a> |

                    <?php echo __('Page Views', 'mxalfwp-domain'); ?>
                </h4>
            </div>
            <div class="mxalfwp-col-lg-9 mxalfwp-col-sm-8 mxalfwp-col-md-8 mxalfwp-col-xs-12">

                <div class="mxalfwp-d-md-flex">
                    <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li class="mxalfwp-big-text">
                            <?php echo __('Data for: ', 'mxalfwp-domain'); ?>
                            <a href="<?php echo $data['visitedPage']; ?>" target="_blank"><?php echo $data['visitedPage']; ?></a>
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
                <?php echo __('Page visit details', 'mxalfwp-domain'); ?>
            </h3>
        </div>
    </div>

    <?php mxalfwpPageViewsTableLayout( $data ); ?>

</div>

