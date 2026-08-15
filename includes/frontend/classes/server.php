<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPServer
{
    public static function registerAjax()
    {
        // Generate link
        add_action('wp_ajax_mxalfwp_link_generate', ['MXALFWPServer', 'linkGenerate']);

        // Get current user's links
        add_action('wp_ajax_mxalfwp_get_links', ['MXALFWPServer', 'getLinks']);

        // Get current user's links conunt
        add_action('wp_ajax_mxalfwp_get_links_count', ['MXALFWPServer', 'getLinksCount']);

        // Save Affliliate link data (views, pages)
        add_action('wp_ajax_mxalfwp_save_link_data', ['MXALFWPServer', 'saveLinkData']);
        add_action('wp_ajax_nopriv_mxalfwp_save_link_data', ['MXALFWPServer', 'saveLinkData']);
    }

    /**
     * save link data
     */
    public static function saveLinkData()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mxalfwp_nonce_request_front')) {

            $userId = get_current_user_id();

            $url = isset($_POST['url']) ? strtolower( rtrim( trim( sanitize_url( wp_unslash( $_POST['url'] ) ) ), '//' ) ) : '';

            $linkKey = isset($_POST['link_key']) ? sanitize_text_field( wp_unslash( $_POST['link_key'] ) ) : '';

            $inst = new MXALFWPMainAdminModel();

            $and = "AND link_key = '$linkKey'";

            $linkData = $inst->getRow(NULL, 1, 1, $and);

            if ($linkData == NULL) {
                echo  'restore';
                wp_die();
            }

            // check if link is active
            if($linkData->status !== 'active') return;

            // 
            $linkTrackingData = maybe_unserialize($linkData->link_data);

            // if no data
            if (!is_array($linkTrackingData)) return;

            // if link owner
            if ($userId === intval($linkData->user_id)) return;

            // set link_data
            if (!isset($linkTrackingData['data'][$url])) {
                $linkTrackingData['data'][$url] = [];
            }

            $publicIP = self::getUserIp(); //'31.14.75.11';
            $json     = file_get_contents("http://ipinfo.io/$publicIP/geo");
            $json     = json_decode($json, true);

            $visitData = [
                'region' => '',
                'city'   => '',
                'date'   => gmdate('Y-m-d H:i:s')
            ];

            if (isset($json['region'])) {
                $visitData['region'] = sanitize_text_field($json['region']);
            }

            if (isset($json['city'])) {
                $visitData['city'] = sanitize_text_field($json['city']);
            }

            array_push($linkTrackingData['data'][$url], $visitData);

            // serialize data
            $serializedData = maybe_serialize($linkTrackingData);

            $updated = $inst->updateRow(
                NULL,
                'link_key',
                $linkKey,
                [
                    'link_data' => $serializedData
                ],
                [
                    '%s'
                ]
            );

            echo absint( $updated );
        }

        wp_die();
    }

    public static function getUserIp()
    {

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) );
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) );
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) );
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    /**
     * Get Links count
     */
    public static function getLinksCount()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mxalfwp_nonce_request_front')) {

            $inst   =  new MXALFWPModel();

            $userId = get_current_user_id();

            $and    = "AND user_id = $userId";

            $count  = $inst->getVar(NULL, 'id', $and);

            if ($count == NULL) {
                echo absint( $count );
                wp_die();
            }

            echo absint( $count );
        }

        wp_die();
    }

    /**
     * Get Links for partner
     */
    public static function getLinks()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mxalfwp_nonce_request_front')) {

            $userId      = get_current_user_id();

            $offset      = isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 10;
            $currentPage = isset( $_POST['current_page'] ) ? intval( $_POST['current_page'] ) : 1;

            $currentPage = ($currentPage * $offset) - $offset;

            global $wpdb;

            $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $linksData = $wpdb->get_results(
                $wpdb->prepare(
                    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- tableName is prefix+constant
                    "SELECT * FROM {$tableName} WHERE user_id = %d ORDER BY id DESC LIMIT %d, %d",
                    $userId,
                    $currentPage,
                    $offset
                )
            );

            $improvedResult = [];

            foreach ($linksData as $value) {

                $tmp = $value;

                $tmp->link_data = maybe_unserialize($value->link_data);

                $tmp->orders = mxalfwpPartnerCompletedOrdersPerLink($value->user_id, $value->id);

                $tmp->earned = mxalfwpGetPartnerCompletedOrdersAmountPerLink($value->id);

                array_push($improvedResult, $tmp);
            }

            echo json_encode($improvedResult);
        }

        wp_die();
    }

    public static function linkGenerate()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mxalfwp_nonce_request_front')) {

            $url = isset( $_POST['url'] ) ? strtok( sanitize_url( wp_unslash( $_POST['url'] ) ), '?' ) : '';

            $url = strtolower(rtrim(trim($url), '//'));

            global $wpdb;

            $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

            $userId = get_current_user_id();

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $tableName is prefix + internal constant, values bound via prepare()
            $findUrl = $wpdb->get_row(
                $wpdb->prepare(

                    "SELECT link FROM $tableName
                        WHERE link = %s
                        AND user_id = %s",
                    $url,
                    $userId

                )
            );

            $responce = [
                'status' => 'success',
                'message' => __('Link Created Successfully!', 'affiliate-links-woocommerce')
            ];

            if ($findUrl !== NULL) {
                $responce = [
                    'status' => 'failed',
                    'message' => __('You\'ve already created an affiliate link for this page!', 'affiliate-links-woocommerce')
                ];
            } else {

                $insertLink = self::insertLink($url, $userId);
                $insertUser = self::insertUser($userId);

                if ($insertLink !== 1) {

                    $responce = [
                        'status' => 'failed',
                        'message' => __('Something went wrong!', 'affiliate-links-woocommerce')
                    ];
                }
            }

            echo json_encode($responce);
        }

        wp_die();
    }

    public static function insertLink($url, $userId)
    {

        global $wpdb;

        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        $user = get_user_by('ID', $userId);

        // insert link
        $date = gmdate('Y-m-d H:i:s');

        $linkKey = wp_generate_password(18, false);

        $linkData = [
            'data' => [
                // 'http://kider-toy-shop.toy' => [
                //     [
                //         'region' => 'Ukraine',
                //         'city' => 'Kyiv',
                //         'date' => '2023-04-05 09:20:07'
                //     ],
                //     [
                //         'region' => 'Ukraine',
                //         'city' => 'Volyn',
                //         'date' => '2023-03-05 08:21:07'
                //     ],
                // ],
                // 'http://kider-toy-shop.toy' => [
                //     [
                //         'region' => 'Ukraine',
                //         'city' => 'Kyiv',
                //         'date' => '2023-04-05 08:23:02'
                //     ],
                // ]
            ]
        ];        

        $linkData = maybe_serialize($linkData);

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- $wpdb->insert() escapes values via the format array
        return $wpdb->insert(

            $tableName,

            [
                'link'       => $url,
                'user_id'    => $userId,
                'link_data'  => $linkData,
                'link_key'   => $linkKey,
                'user_name'  => $user->data->display_name,
                'percent'    => get_option('mxalfwp_default_percent'),
                'created_at' => $date,
                'updated_at' => $date,
            ],

            [
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            ]

        );
    }

    public static function insertUser($userId)
    {

        global $wpdb;

        $tableName = $wpdb->prefix . MXALFWP_USERS_TABLE_SLUG;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $tableName is prefix + internal constant, value bound via prepare()
        $partner = $wpdb->get_row(
            $wpdb->prepare(

                "SELECT id FROM $tableName
                    WHERE user_id = %d",
                $userId

            )
        );

        if ($partner == NULL) {

            // insert user
            $date = gmdate('Y-m-d H:i:s');

            $userKey = wp_generate_password(18, false);

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- $wpdb->insert() escapes values via the format array
            return $wpdb->insert(

                $tableName,

                [
                    'user_id'    => $userId,
                    'user_key'   => $userKey,
                    'created_at' => $date,
                    'updated_at' => $date,
                ],

                [
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                ]

            );
        }

        return false;
    }
}
