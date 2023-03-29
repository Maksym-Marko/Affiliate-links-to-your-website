<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPServer
{
    public static function registerAjax()
    {
        // Generate link
        add_action('wp_ajax_mxalfwp_link_generate', ['MXALFWPServer', 'link_generate']);

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

                    "SELECT id, link, link_data, user_id, earned, paid, bought, percent, status 
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

    public static function link_generate()
    {

        if (empty($_POST['nonce'])) wp_die();

        if (wp_verify_nonce($_POST['nonce'], 'mxalfwp_nonce_request_front')) {

            $url = trim($_POST['url']);

            $url = rtrim($url, '//');

            $url = sanitize_url($url);

            global $wpdb;

            $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

            $userId = get_current_user_id();

            $user = get_user_by('ID', $userId);

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

                // insert link
                $date = date('Y-m-d H:i:s');
                $insert = $wpdb->insert(

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

                if ($insert !== 1) {

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
}
