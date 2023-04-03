<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class MXALFWPPageViews extends WP_List_Table
{

    /*
    * MXALFWPPageViews
    */

    public $linkData = NULL;
    public $visitedPage = NULL;

    public function __construct($args = [])
    {
        parent::__construct(
            [
                'singular' => 'mxalfwp_pv_singular',
                'plural'   => 'mxalfwp_pv_plural',
            ]
        );
        $this->linkData    = $args['linkData'];
        $this->visitedPage = $args['visitedPage'];
    }

    public function prepare_items()
    {

        // pagination
        $perPage     = 20;
        $currentPage = $this->get_pagenum();

        if (1 < $currentPage) {
            $offset = $perPage * ($currentPage - 1);
        } else {
            $offset = 0;
        }

        // get data
        $dataFull = [];
        $data = [];

        if ($this->linkData !== NULL) {
            $unserialize = maybe_unserialize($this->linkData->link_data);
            $dataFull = $unserialize['data'];
        }

        foreach ($dataFull as $key => $value) {
            if ($key == $this->visitedPage) {
                $data = $value;
                break;
            }
        }

        // set data
        $items = [];

        $dataPerPage = $data;

        if (count($data) > 10) {
            $dataPerPage = array_slice($data, $offset, $perPage);
        }

        $items = $dataPerPage;

        $count = count($data);

        $this->items = $items;

        // set comumn headers
        $columns  = $this->get_columns();

        $this->_column_headers = [
            $columns
        ];

        // Set the pagination.
        $this->set_pagination_args(
            [
                'total_items' => $count,
                'per_page'    => $perPage,
                'total_pages' => ceil($count / $perPage),
            ]
        );
    }

    public function get_columns()
    {
        return [
            'region' => __('Region', 'mxalfwp-domain'),
            'city'   => __('City', 'mxalfwp-domain'),
            'date'   => __('Visit Date', 'mxalfwp-domain'),
        ];
    }

    public function column_default($item, $columnName)
    {
        do_action("manage_mxalfwp_page_views_custom_column", $columnName, $item);
    }

    public function column_region($item)
    {
        echo $item['region'];
    }

    public function column_city($item)
    {
        echo $item['city'];
    }

    public function column_date($item)
    {
        echo $item['date'];
    }
}

if (!function_exists('mxalfwpPageViewsTableLayout')) {

    function mxalfwpPageViewsTableLayout($data)
    {

        $tableInstance = new MXALFWPPageViews($data);

        $tableInstance->prepare_items();

        echo '<form id="mxalfwp_page_views_form" method="post">';
        $tableInstance->display();
        echo '</form>';
    }
}
