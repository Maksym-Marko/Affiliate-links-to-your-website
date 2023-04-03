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

        // Save Affliliate link data (views, pages)
        add_action('wp_ajax_mxalfwp_save_link_data', ['MXALFWPServer', 'saveLinkData']);
    }

    /**
     * save link data
     */
    public static function saveLinkData()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_front')) {

            $url = strtolower(sanitize_url(rtrim(trim($_POST['url']), '//')));

            $linkKey = sanitize_text_field($_POST['link_key']);

            $inst = new MXALFWPMainAdminModel();

            $and = "AND link_key = '$linkKey'";

            $linkData = $inst->getRow(NULL, 1, 1, $and);

            if ($linkData == NULL) {
                echo  'restore';
                wp_die();
            }

            // 
            $linkTrackingData = maybe_unserialize($linkData->link_data);

            if (!is_array($linkTrackingData)) return;

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
                'date'   => date('Y-m-d H:i:s')
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

            echo $updated;
        }

        wp_die();
    }

    public static function getUserIp()
    {

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    /**
     * Get Links for partner
     */
    public static function getLinks()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_front')) {

            $userId = get_current_user_id();

            $inst = new MXALFWPMainAdminModel();

            $linksData = $inst->getResults(NULL, 'user_id', intval($userId));

            $improvedResult = [];

            foreach ($linksData as $value) {

                $tmp = $value;

                $tmp->link_data = maybe_unserialize($value->link_data);

                array_push($improvedResult, $tmp);
            }

            echo json_encode($improvedResult);
        }

        wp_die();
    }

    public static function linkGenerate()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_front')) {

            $url = strtok($_POST['url'], '?');

            $url = strtolower(sanitize_url(rtrim(trim($url), '//')));

            global $wpdb;

            $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

            $userId = get_current_user_id();

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
                'message' => __('Link Created Successfully!', 'mxalfwp-domain')
            ];

            if ($findUrl !== NULL) {
                $responce = [
                    'status' => 'failed',
                    'message' => __('You\'ve already created an affiliate link for this page!', 'mxalfwp-domain')
                ];
            } else {

                $insertLink = self::insertLink($url, $userId);
                $insertUser = self::insertUser($userId);

                if ($insertLink !== 1) {

                    $responce = [
                        'status' => 'failed',
                        'message' => __('Something went wrong!', 'mxalfwp-domain')
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
        $date = date('Y-m-d H:i:s');

        $linkKey = wp_generate_password(18, false);

        return $wpdb->insert(

            $tableName,

            [
                'link'       => $url,
                'user_id'    => $userId,
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

        $partner = $wpdb->get_row(
            $wpdb->prepare(

                "SELECT id FROM $tableName 
                    WHERE user_id = %d",
                $userId

            )
        );

        if ($partner == NULL) {

            // insert user
            $date = date('Y-m-d H:i:s');

            $userKey = wp_generate_password(18, false);

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
