<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// create table class
require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/core/create-table.php';

class MXALFWPBasisPluginClass
{

    private static $affiliateLinlksTableSlug = MXALFWP_TABLE_SLUG;
    private static $affiliateLinlksUsersTableSlug = MXALFWP_USERS_TABLE_SLUG;
    private static $affiliateLinlksOrdersTableSlug = MXALFWP_ORDERS_TABLE_SLUG;

    public static function activate()
    {

        // set option for rewrite rules CPT
        self::createOptionForActivation();

        // create links table
        self::createLinksTable();

        // create users table
        self::createUsersTable();

        // create orders table
        self::createOrdersTable();

    }

    public static function createLinksTable()
    {

        // Create table
        global $wpdb;

        // Table name
        $tableName    = $wpdb->prefix . self::$affiliateLinlksTableSlug;

        $productTable = new MXALFWPCreateTable($tableName);

        // add some column
        // user_name
        $productTable->varchar('user_name', 200, true, 'text');

        // user_id
        $productTable->int('user_id');

        // link
        $productTable->longtext('link');

        // links_data
        $productTable->longtext('link_data');

        // link key
        $productTable->varchar('link_key', 18, true, 'Jdl85uWngsN405Dmrb');

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

    public static function createUsersTable()
    {

        // Create table
        global $wpdb;

        // Table name
        $tableName    = $wpdb->prefix . self::$affiliateLinlksUsersTableSlug;

        $productTable = new MXALFWPCreateTable($tableName);

        // user_id
        $productTable->int('user_id');

        // paid
        $productTable->varchar('paid', 10, true, '0');

        // user key
        $productTable->varchar('user_key', 18, true, 'kfpei84h3o59ajg839');

        // status
        $productTable->varchar('status', 10, true, 'active');

        // created
        $productTable->datetime('created_at');

        // updated
        $productTable->datetime('updated_at');

        // create "id" column as AUTO_INCREMENT
        $productTable->create_columns();

        // create table
        $tableCreated = $productTable->createTable();
        
    }

    public static function createOrdersTable()
    {

        // Create table
        global $wpdb;

        // Table name
        $tableName    = $wpdb->prefix . self::$affiliateLinlksOrdersTableSlug;

        $productTable = new MXALFWPCreateTable($tableName);

        // order_id
        $productTable->int('order_id');

        // user_id
        $productTable->int('user_id');

        // link_id
        $productTable->int('link_id');

        // status
        $productTable->varchar('status', 20, true, 'on-hold');

        // amount
        $productTable->varchar('amount', 10, true, '0');

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
        // Set default percent
        if (!get_option('mxalfwp_default_percent')) {
            add_option('mxalfwp_default_percent', '5.0');
        }

        // Set default currency sign
        if (!get_option('mxalfwp_default_currency_sign')) {
            add_option('mxalfwp_default_currency_sign', '$');
        }
        // add_option('mxalfwp_flush_rewrite_rules', 'go_flush_rewrite_rules');
    }
}
