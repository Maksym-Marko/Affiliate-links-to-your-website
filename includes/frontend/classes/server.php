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
        add_action('wp_ajax_mxalfwp_get_links', ['MXALFWPServer', 'get_links']);
    }

    public static function get_links()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_front')) {

            $userId = get_current_user_id();

            global $wpdb;

            $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

            $results = $wpdb->get_results(

                $wpdb->prepare(

                    "SELECT id, link, link_data, user_id, earned, bought, percent, status 
                        FROM $tableName                         
                        WHERE user_id=%d
                        ORDER BY id DESC",
                    $userId

                )

            );

            $improvedResult = [];

            foreach ($results as $value) {

                $tmp = $value;

                $tmp->link_data = maybe_unserialize($value->link_data);

                array_push($improvedResult, $tmp);
            }
            
            echo json_encode( $improvedResult);

        }

        wp_die();
    }

    public static function linkGenerate()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_front')) {

            $url = sanitize_url(rtrim(trim($_POST['url']),'//'));

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

                $insertLink = self::insertLink( $url, $userId );
                $insertUser = self::insertUser( $userId );

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

    public static function insertLink( $url, $userId )
    {

        global $wpdb;

        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        $user = get_user_by('ID', $userId);

        // insert link
        $date = date('Y-m-d H:i:s');

        return $wpdb->insert(

            $tableName,

            [
                'link'       => $url,
                'user_id'    => $userId,
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
            ]

        );
    }

    public static function insertUser( $userId )
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

        if( $partner == NULL ) {

            // insert user
            $date = date('Y-m-d H:i:s');

            $userKey = wp_generate_password( 18, false );

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
