<div class="mxalfwp-sub-page-text-wrap">

    <!--  -->
    <div class="mxalfwp-page-breadcrumb mxalfwp-bg-white">
        <div class="mxalfwp-row mxalfwp-align-items-center">
            <div class="mxalfwp-col-lg-3 mxalfwp-col-md-4 mxalfwp-col-sm-4 mxalfwp-col-xs-12">
                <h4 class="mxalfwp-page-title"><?php echo __('Settings', 'mxalfwp-domain'); ?></h4>
            </div>
            <div class="mxalfwp-col-lg-9 mxalfwp-col-sm-8 mxalfwp-col-md-8 mxalfwp-col-xs-12">

                <div class="mxalfwp-d-md-flex">
                    <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li class="mxalfwp-big-text">
                            <?php echo __('Use this short code to create a cabinet for your partners: ', 'mxalfwp-domain'); ?><b>[mxalfwp_partner_cabinet]</b>
                        </li>
                    </ol>
                    <!-- <ol class="mxalfwp-breadcrumb mxalfwp-ms-auto">
                        <li><a href="#" class="mxalfwp-fw-normal">Dashboard</a></li>
                    </ol>
                    <a href="https://www.wrappixel.com/templates/ampleadmin/" target="_blank" class="
                    mxalfwp-btn mxalfwp-btn-danger
                    mxalfwp-d-md-block
                    mxalfwp-pull-right
                    mxalfwp-ms-3
                    mxalfwp-hidden-xs mxalfwp-hidden-sm
                    mxalfwp-waves-effect mxalfwp-waves-light
                    mxalfwp-text-white
                  ">Upgrade to Pro</a>  -->
                </div>

            </div>
        </div>
    </div>

    <!--  -->
    <div id="mxalfwp_admin_settings">
        <mxalfwp_admin_settings_form
            :translation="translation"
            :percent="percent"
            :savesettings="saveSettings"
            :progress="progress"
            :currency="currency"
        ></mxalfwp_admin_settings_form>
    </div>

</div>