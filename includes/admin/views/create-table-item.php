<div class="mx-main-page-text-wrap">
    
    <h1><?php echo __( 'Create Table Item', 'mxalfwp-domain' ); ?></h1>

    <a href="<?php echo admin_url( 'admin.php?page=' . MXALFWP_MAIN_MENU_SLUG ); ?>">Go Back</a>

    <div class="mxalfwpmx_block_wrap">

        <form id="mxalfwp_form_create_table_item" class="mx-settings" method="post" action="">

            <div>
                <label for="mxalfwp_title">Title</label>
                <br>
                <input type="text" name="mxalfwp_title" id="mxalfwp_title" value="" />
            </div>
            <br>
            <div>
                <label for="mxalfwp_mx_description">Description</label>
                <br>
                <textarea name="mxalfwp_mx_description" id="mxalfwp_mx_description"></textarea>
            </div>

            <p class="mx-submit_button_wrap">
                <input type="hidden" id="mxalfwp_wpnonce" name="mxalfwp_wpnonce" value="<?php echo wp_create_nonce('mxalfwp_nonce_request'); ?>" />
                <input class="button-primary" type="submit" name="mxalfwp_submit" value="Create" />
            </p>

        </form>

    </div>

</div>