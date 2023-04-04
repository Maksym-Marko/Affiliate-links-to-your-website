<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Main page Model
 */
class MXALFWPMainAdminModel extends MXALFWPModel
{

    /*
    * Observe function
    */
    public static function wpAjax()
    {

        add_action('wp_ajax_mxalfwp_bulk_actions', ['MXALFWPMainAdminModel', 'prepareBulkActions'], 10);

        // Settings page
        add_action('wp_ajax_mxalfwp_save_settings', ['MXALFWPMainAdminModel', 'prepareSaveSettings'], 10);

        // pay a partner
        add_action('wp_ajax_mxalfwp_pay_partner', ['MXALFWPMainAdminModel', 'preparePayPartner'], 10);

        // block a partner
        add_action('wp_ajax_mxalfwp_block_partner', ['MXALFWPMainAdminModel', 'prepareBlockPartner'], 10);
    }

    /*
    * Block Partner
    */
    public static function prepareBlockPartner()
    {

        // Checked POST nonce is not empty
        if (empty($_POST['nonce'])) wp_die('0');

        // Checked or nonce match
        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_admin')) {

            self::blockPartner($_POST);
        }

        wp_die();
    }

    public static function blockPartner($data)
    {

        $inst = new MXALFWPMainAdminModel();

        $userKey = sanitize_text_field($data['user_key']);

        $and = "AND user_key = '$userKey'";

        $userData = $inst->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($data['user_id']), $and);

        $responce = [
            'status' => 'success',
            'message' => __('The Partner Unblocked!', 'mxalfwp-domain')
        ];

        // user not found
        if ($userData == NULL) {

            $responce = [
                'status' => 'failed',
                'message' => __('No partner found!', 'mxalfwp-domain')
            ];

            echo json_encode($responce);

            return;
        }

        $status = 'active';

        if ($userData->status == 'active') {

            $status = 'blocked';

            $responce = [
                'status' => 'success',
                'message' => __('The Partner Blocked!', 'mxalfwp-domain')
            ];
        }

        // save changes
        $updated = $inst->updateRow(
            MXALFWP_USERS_TABLE_SLUG,
            'user_id',
            intval($data['user_id']),
            [
                'status' => $status
            ],
            [
                '%s'
            ]
        );

        if ($updated !== 1) {

            $responce = [
                'status' => 'failed',
                'message' => __('Something went wrong!', 'mxalfwp-domain')
            ];

            echo json_encode($responce);

            return;
        }

        $linksData = self::partnerLinksData(intval($data['user_id']));

        foreach ($linksData as $key => $value) {
            if ($value->status == 'trash') {
                $inst->updateRow(
                    NULL,
                    'user_id',
                    intval($data['user_id']),
                    [
                        'status' => 'active'
                    ],
                    [
                        '%s'
                    ]
                );
            } else {
                $inst->updateRow(
                    NULL,
                    'user_id',
                    intval($data['user_id']),
                    [
                        'status' => 'trash'
                    ],
                    [
                        '%s'
                    ]
                );
            }
        }

        echo json_encode($responce);
    }

    /*
    * Pay Partner
    */
    public static function preparePayPartner()
    {

        // Checked POST nonce is not empty
        if (empty($_POST['nonce'])) wp_die('0');

        // Checked or nonce match
        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_admin')) {

            self::payPartner($_POST);
        }

        wp_die();
    }

    public static function payPartner($data)
    {

        $inst     = new MXALFWPMainAdminModel();

        $userKey  = sanitize_text_field($data['user_key']);

        $and      = "AND user_key = '$userKey'";

        $userData = $inst->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($data['user_id']), $and);

        $responce = [
            'status' => 'success',
            'message' => __('Paid amount changed!', 'mxalfwp-domain')
        ];

        // user not found
        if ($userData == NULL) {

            $responce = [
                'status' => 'failed',
                'message' => __('No partner found!', 'mxalfwp-domain')
            ];

            echo json_encode($responce);

            return;
        }

        // check paid data
        if (floatval($data['amount']) <= floatval($userData->paid)) {

            $responce = [
                'status' => 'failed',
                'message' => __('The amount of the last payment is ', 'mxalfwp-domain') . floatval($userData->paid) . __('. You cannot pay the same amount or less!', 'mxalfwp-domain')
            ];

            echo json_encode($responce);

            return;
        }

        // 
        $linksData = self::partnerLinksData(intval($data['user_id']));

        if (count($linksData) == 0) {

            $responce = [
                'status' => 'failed',
                'message' => __('Something went wrong with links Data!', 'mxalfwp-domain')
            ];

            echo json_encode($responce);

            return;
        }

        $earned = mxalfwpPartnerEarnedAmount(intval($data['user_id']));

        if (floatval($data['amount']) > $earned) {

            $responce = [
                'status' => 'failed',
                'message' => __('You cannot top up more than ', 'mxalfwp-domain') . $earned
            ];

            echo json_encode($responce);

            return;
        }

        // save changes
        $updated = $inst->updateRow(
            MXALFWP_USERS_TABLE_SLUG,
            'user_id',
            intval($data['user_id']),
            [
                'paid' => floatval($data['amount'])
            ],
            [
                '%s'
            ]
        );

        if ($updated !== 1) {

            $responce = [
                'status' => 'failed',
                'message' => __('Something went wrong. Paid amount not saved!', 'mxalfwp-domain') . $earned
            ];

            echo json_encode($responce);

            return;
        }

        echo json_encode($responce);
    }

    public static function partnerLinksData($userId)
    {
        $inst = new MXALFWPMainAdminModel();

        $linksData = $inst->getResults(NULL, 'user_id', intval($userId));

        return $linksData;
    }

    /*
    * Settings
    */
    public static function prepareSaveSettings()
    {

        // Checked POST nonce is not empty
        if (empty($_POST['nonce'])) wp_die('0');

        // Checked or nonce match
        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_admin')) {

            $updatedPercent = update_option('mxalfwp_default_percent', floatval($_POST['percent']));
            $updatedCurrency = update_option('mxalfwp_default_currency_sign', sanitize_text_field($_POST['currency']));

            $responce = [
                'status' => 'success',
                'message' => __('Settings updated!', 'mxalfwp-domain')
            ];

            echo json_encode($responce);
        }

        wp_die();
    }

    /*
    * Prepare to bulk actions
    */
    public static function prepareBulkActions()
    {

        // Checked POST nonce is not empty
        if (empty($_POST['nonce'])) wp_die('0');

        // Checked or nonce match
        if (wp_verify_nonce($_POST['nonce'], 'bulk-mxalfwp_plural')) {

            // delete
            if ($_POST['bulk_action']  == 'delete') {

                if (!current_user_can('edit_posts')) return;

                self::actionDelete($_POST['ids']);

                return;
            }

            // restore
            if ($_POST['bulk_action']  == 'restore') {

                if (!current_user_can('edit_posts')) return;

                self::actionRestore($_POST['ids']);

                return;
            }

            // move to trash
            if ($_POST['bulk_action']  == 'trash') {

                if (!current_user_can('edit_posts')) return;

                self::actionTrash($_POST['ids']);

                return;
            }
        }

        wp_die();
    }

    /**
     * Handle bulk actions 
     */
    // Delete permanently
    public static function actionDelete($ids)
    {

        foreach ($ids as $id) {
            (new self)->deletePermanently($id);
        }

        return;
    }

    // Restore
    public static function actionRestore($ids)
    {

        foreach ($ids as $id) {
            (new self)->restoreItem($id);
        }

        return;
    }

    // Move to Trash
    public static function actionTrash($ids)
    {

        foreach ($ids as $id) {
            (new self)->moveToTrash($id);
        }

        return;
    }

    /*
    * Actions
    */
    // restore item
    public function restoreItem($id)
    {

        global $wpdb;

        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        $wpdb->update(

            $tableName,
            [
                'status' => 'active',
            ],
            [
                'id'     => $id
            ],
            [
                '%s',
            ]

        );
    }
    // move to trash
    public function moveToTrash($id)
    {

        global $wpdb;

        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        $wpdb->update(

            $tableName,
            [
                'status' => 'trash',
            ],
            [
                'id'     => $id
            ],
            [
                '%s',
            ]

        );
    }

    // delete permanently
    public function deletePermanently($id)
    {

        // ...

    }
}
