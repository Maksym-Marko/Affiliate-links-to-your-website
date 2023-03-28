<?php if (!is_user_logged_in()) : ?>

    <h2><?php echo __('Please, Sign in to go to this page', 'mxalfwp-domain'); ?></h2>

    <?php return; ?>

<?php endif; ?>

<div id="mxalfwp_cabinet">

    <mxalfwp_c_form
        :translation='translation'
        :ajaxdata="ajaxdata"
        :toquerystring="toQueryString"
        :getcurrentuserlinks="getCurrentUserLinks"
    ></mxalfwp_c_form>

    <mxalfwp_c_table
        :translation='translation'
        :links='links'
    ></mxalfwp_c_table>
    
</div>