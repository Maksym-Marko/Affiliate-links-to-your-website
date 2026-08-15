<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="mxalfwp-sub-page-text-wrap">

    <!--  -->
    <div class="mxalfwp-page-breadcrumb mxalfwp-bg-white">
        <div class="mxalfwp-row mxalfwp-align-items-center">
            <div class="mxalfwp-col-lg-3 mxalfwp-col-md-4 mxalfwp-col-sm-4 mxalfwp-col-xs-12">
                <h4 class="mxalfwp-page-title">
                    <a href="<?php echo esc_url( admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG) ); ?>" class="mxalfwp-common-link"><i class="fa fa-chevron-left" aria-hidden="true"></i> All links</a> |

                    <a href="<?php echo esc_url( admin_url('admin.php?page=' . MXALFWP_SINGLE_TABLE_ITEM_MENU) ); ?>&mxalfwp-link-id=<?php echo absint( $data['linkData']->id ); ?>" class="mxalfwp-common-link"><?php echo esc_html__('Link Data', 'affiliate-links-woocommerce'); ?></a> |

                    <?php echo esc_html__('Page Views', 'affiliate-links-woocommerce'); ?>
                </h4>
            </div>
            <div class="mxalfwp-col-lg-9 mxalfwp-col-sm-8 mxalfwp-col-md-8 mxalfwp-col-xs-12">

                <div class="mxalfwp-d-md-flex">
                    <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li class="mxalfwp-big-text">
                            <?php echo esc_html__('Data for: ', 'affiliate-links-woocommerce'); ?>
                            <a href="<?php echo esc_url( $data['visitedPage'] ); ?>" target="_blank"><?php echo esc_html( $data['visitedPage'] ); ?></a>
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
                <?php echo esc_html__('Page visit details', 'affiliate-links-woocommerce'); ?>
            </h3>
        </div>
    </div>

    <?php mxalfwpPageViewsTableLayout( $data ); ?>

</div>

