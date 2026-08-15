<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/*
* Model class
*/
class MXALFWPModel
{

    private $wpdb;

    /**
     * Table name
     */
    protected $table = MXALFWP_TABLE_SLUG;

    /**
     * fields
     */
    protected $fields = '*';

    /*
    * Model constructor
    */
    public function __construct()
    {

        global $wpdb;

        $this->wpdb = $wpdb;
    }

    /**
     * select row from the database
     */
    public function getRow($table = NULL, $wherName = NULL, $wherValue = NULL, $and = '')
    {

        $tableName = $this->wpdb->prefix . $this->table;

        if ($table !== NULL) {

            $tableName = $this->wpdb->prefix . $table;
        }

        $where = '';

        if ($wherName !== NULL && $wherValue !== NULL) {

            // $wherName is an internal column identifier; the value is bound via prepare().
            $where = $this->wpdb->prepare( 'WHERE ' . esc_sql( $wherName ) . ' = %s', $wherValue ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- identifier escaped with esc_sql(), value bound via prepare()
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $tableName is prefix + internal constant, $this->fields is a fixed internal column list, $where is pre-escaped via prepare(), $and is built internally from cast integers
        $getRow = $this->wpdb->get_row("SELECT $this->fields FROM $tableName {$where} {$and}");

        return $getRow;
    }

    /**
     * get results from the database
     */
    public function getResults($table = NULL, $wherName = NULL, $wherValue = 1, $and = '', $order = 'ORDER BY id DESC', $mask = '%d')
    {

        $tableName = $this->wpdb->prefix . $this->table;

        if ($table !== NULL) {

            $tableName = $this->wpdb->prefix . $table;
        }

        if ($wherName !== NULL) {

            // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $tableName is prefix + internal constant, $this->fields/$order are fixed internal identifiers, $wherName is escaped with esc_sql(), the value is bound via prepare() using the internal $mask placeholder, $and is built internally from cast integers
            $results = $this->wpdb->get_results(

                $this->wpdb->prepare(

                    "SELECT $this->fields
                        FROM $tableName
                        WHERE " . esc_sql( $wherName ) . "=$mask {$and}
                        {$order}",
                    $wherValue

                )

            );
            // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, PluginCheck.Security.DirectDB.UnescapedDBParameter
        } else {

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $tableName is prefix + internal constant, $this->fields is a fixed internal column list
            $results = $this->wpdb->get_results("SELECT $this->fields FROM $tableName");
        }

        return $results;
    }

    /**
     * update row
     */
    public function updateRow($table = NULL, $wherName = NULL, $wherValue = NULL, $columns = [], $masks = [])
    {

        $tableName = $this->wpdb->prefix . $this->table;

        if ($table !== NULL) {

            $tableName = $this->wpdb->prefix . $table;
        }

        if ($wherName == NULL || $wherValue == NULL) return false;

        $update = $this->wpdb->update(

            $tableName,
            $columns,
            [
                $wherName => $wherValue
            ],
            $masks

        );

        return $update;
    }

    /**
     * insert row
     */
    public function insertRow($table = NULL, $columns = [], $masks = [])
    {

        $tableName = $this->wpdb->prefix . $this->table;

        if ($table !== NULL) {

            $tableName = $this->wpdb->prefix . $table;
        }

        $insert = $this->wpdb->insert(

            $tableName,

            $columns,

            $masks

        );

        return $insert;
    }

    /**
     * get var
     */
    public function getVar($table = NULL, $countBy = 'id', $and = null)
    {

        $tableName = $this->wpdb->prefix . $this->table;

        if ($table !== NULL) {

            $tableName = $this->wpdb->prefix . $table;
        }

        $countBy = esc_sql( $countBy );

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $tableName is prefix + internal constant, $countBy escaped with esc_sql(), $and is built internally from cast integers
        $count = $this->wpdb->get_var( "SELECT COUNT($countBy) FROM {$tableName} WHERE 1=1 {$and}");

        return $count;

    }
}
