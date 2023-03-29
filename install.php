<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// create table class
require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/create-table.php';

class MXALFWPBasisPluginClass
{

    private static $tableSlug = MXALFWP_TABLE_SLUG;

    public static function activate()
    {

        // set option for rewrite rules CPT
        self::createOptionForActivation();

        // Create table
        global $wpdb;

        // Table name
        $tableName    = $wpdb->prefix . self::$tableSlug;

        $productTable = new MXALFWPCreateTable($tableName);

        // add some column
        // user_name
        $productTable->varchar('user_name', 200, true, 'text');

        // user_id
        $productTable->int('user_id');

        // link
        $productTable->longtext('link');

        // links_data
        $linkData = [
            'data' => [
                // 'http://affiliate-links-woocommerce.local/product/hoodie-with-logo/' => [
                //     [
                //         'location' => 'Ukraine, Kyiv',
                //         'date'     => '0000-00-00 00:00:00'
                //     ],
                //     [
                //         'location' => 'Ukraine, Volyn',
                //         'date'     => '0000-00-00 00:00:00'
                //     ],
                // ],
                // 'http://affiliate-links-woocommerce.local/products/' => [
                //     [
                //         'location' => 'Ukraine, Kyiv',
                //         'date'     => '0000-00-00 00:00:00'
                //     ],
                // ]
            ]
        ];

        $productTable->longtext('link_data', false, maybe_serialize($linkData));

        // bought
        $productTable->int('bought');

        // earned
        $productTable->int('earned');

        // paid
        $productTable->int('paid');

       // percent
       $productTable->varchar('percent', 20, true, '0');

        // statue
        $productTable->varchar('status', 20, true, 'active'); // trash

        // created
        $productTable->datetime('created_at');

        // updated
        $productTable->datetime('updated_at');

        // create "id" column as AUTO_INCREMENT
        $productTable->create_columns();

        // create table
        $tableCreated = $productTable->createTable();
    }

    public static function deactivate()
    {

        // Rewrite rules
        flush_rewrite_rules();
    }

    /*
    * This function sets the option in the table for CPT rewrite rules
    */
    public static function createOptionForActivation()
    {
        if (!get_option('mxalfwp_default_percent')) {
            add_option('mxalfwp_default_percent', '5.0');
        }
        // add_option('mxalfwp_flush_rewrite_rules', 'go_flush_rewrite_rules');
    }
}
