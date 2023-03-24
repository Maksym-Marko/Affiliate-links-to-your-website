<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class MXALFWPCustomTable extends WP_List_Table
{

    /*
    * MXALFWPCustomTable
    */

    public function __construct( $args = [] )
    {

        parent::__construct(
            [
                'singular' => 'mxalfwp_singular',
                'plural'   => 'mxalfwp_plural',
            ]
        );

    }

    public function prepare_items()
    {

        global $wpdb;

        // pagination
        $perPage     = 20;
        $currentPage = $this->get_pagenum();

        if (1 < $currentPage) {
            $offset = $perPage * ( $currentPage - 1 );
        } else {
            $offset = 0;
        }

        // sortable
        $order = isset( $_GET['order'] ) ? trim( sanitize_text_field( $_GET['order'] ) ) : 'desc';
        $orderBy = isset( $_GET['orderby'] ) ? trim( sanitize_text_field( $_GET['orderby'] ) ) : 'id';

        // search
        $search = '';

        if (!empty($_REQUEST['s'])) {
            $search = "AND title LIKE '%" . esc_sql( $wpdb->esc_like( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) ) ) . "%' ";
        }

        // status
        $itemStatus = isset( $_GET['user_status'] ) ? trim( $_GET['user_status'] ) : 'active';
        $status = "AND status = '$itemStatus'";
        
        // get data
        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        $items = $wpdb->get_results(
            "SELECT * FROM {$tableName} WHERE 1 = 1 {$status} {$search}" .
            $wpdb->prepare( "ORDER BY {$orderBy} {$order} LIMIT %d OFFSET %d;", $perPage, $offset ),
            ARRAY_A
        );

        $count = $wpdb->get_var( "SELECT COUNT(id) FROM {$tableName} WHERE 1 = 1 {$status} {$search};" );

        // set data
        $this->items = $items;

        // set comumn headers
        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [
            $columns,
            $hidden,
            $sortable,
        ];

        // Set the pagination.
        $this->set_pagination_args(
            [
                'total_items' => $count,
                'per_page'    => $perPage,
                'total_pages' => ceil( $count / $perPage ),
            ]
        );

    }

    public function get_columns()
    {

        return [
            'cb'          => '<input type="checkbox" />',
            'id'          => __( 'ID', 'mxalfwp-domain' ),
            'user_id'     => __( 'User ID', 'mxalfwp-domain' ),
            'user_name'   => __( 'User Name', 'mxalfwp-domain' ),
            'link_data'  => __( 'Links Data', 'mxalfwp-domain' ),
            'link'        => __( 'Link', 'mxalfwp-domain' ),
            'earned'      => __( 'Earned', 'mxalfwp-domain' ),
            'paied'       => __( 'Paied', 'mxalfwp-domain' ),
            'status'      => __( 'Status', 'mxalfwp-domain' ),
            'created_at'  => __( 'Created', 'mxalfwp-domain' ),
        ];
        
    }    

    public function get_hidden_columns()
    {

        return [
            'id',
            'link_data',
            'user_id',
            'status',
        ];

    }

    public function get_sortable_columns()
    {

        return [
            'user_name' => [
                'user_name',
                false
            ]
        ];
        
    }

    public function column_default( $item, $columnName )
    {

        do_action( "manage_mxalfwp_items_custom_column", $columnName, $item );

    }

    public function column_cb( $item )
    {
        
        echo sprintf( '<input type="checkbox" class="mxalfwp_bulk_input" name="mxalfwp-action-%1$s" value="%1$s" />', $item['id'] );
    
    }

    public function column_id( $item )
    {

        echo $item['id'];

    }

    public function column_user_id( $item )
    {

        echo $item['user_id'];

    }

    public function column_user_name( $item )
    {

        $url      = admin_url( 'admin.php?page=' . MXALFWP_SINGLE_TABLE_ITEM_MENU );

        $user_id  = get_current_user_id();

        $can_edit = current_user_can( 'edit_user', $user_id );

        $output   = '<strong>';

        if ($can_edit) {

            $output .= '<a href="' . esc_url( $url ) . '&user-details=' . $item['user_id'] . '">' . $item['user_name'] . '</a>';

            $actions['edit']  = '<a href="' . esc_url( $url ) . '&user-details=' . $item['user_id'] . '">' . __( 'Manage', 'mxalfwp-domain' ) . '</a>';
            $actions['blocked'] = '<a class="submitdelete" aria-label="' . esc_attr__( 'Blocked', 'mxalfwp-domain' ) . '" href="' . esc_url(
                wp_nonce_url(
                    add_query_arg(
                        [
                            'blocked' => $item['user_id'],
                        ],
                        $url
                    ),
                    'blocked',
                    'mxalfwp_nonce'
                )
            ) . '">' . esc_html__( 'Blocked', 'mxalfwp-domain' ) . '</a>';

            $itemStatus = isset( $_GET['user_status'] ) ? trim( $_GET['user_status'] ) : 'active';

            if ($itemStatus == 'blocked') {

                unset( $actions['edit'] );
                unset( $actions['blocked'] );

                $actions['restore'] = '<a aria-label="' . esc_attr__( 'Restore', 'mxalfwp-domain' ) . '" href="' . esc_url(
                    wp_nonce_url(
                        add_query_arg(
                            [
                                'restore' => $item['id'],
                            ],
                            $url
                        ),
                        'restore',
                        'mxalfwp_nonce'
                    )
                ) . '">' . esc_html__( 'Restore', 'mxalfwp-domain' ) . '</a>';

                $actions['delete'] = '<a class="submitdelete" aria-label="' . esc_attr__( 'Delete Permanently', 'mxalfwp-domain' ) . '" href="' . esc_url(
                    wp_nonce_url(
                        add_query_arg(
                            [
                                'delete' => $item['id'],
                            ],
                            $url
                        ),
                        'delete',
                        'mxalfwp_nonce'
                    )
                ) . '">' . esc_html__( 'Delete Permanently', 'mxalfwp-domain' ) . '</a>';

            }
    
            $rowActions = [];
    
            foreach ($actions as $action => $link) {
                $rowActions[] = '<span class="' . esc_attr( $action ) . '">' . $link . '</span>';
            }
    
            $output .= '<div class="row-actions">' . implode( ' | ', $rowActions ) . '</div>';
                
        } else {

            $output .= $item['title'];

        }

        $output .= '</strong>';

        echo $output;

    }

    public function column_link( $item )
    {

        var_dump( $item['link'] );

    }

    public function column_link_data( $item )
    {

        var_dump( $item['link_data'] );

    }

    public function column_earned( $item )
    {

        var_dump( $item['earned'] );

    }

    public function column_paied( $item )
    {

        var_dump( $item['paied'] );

    }

    public function column_created_at( $item )
    {

        echo $item['created_at'];

    }

    protected function get_bulk_actions()
    {

        if (!current_user_can('edit_posts')) {
            return [];
        }

        $itemStatus = isset( $_GET['user_status'] ) ? trim( $_GET['user_status'] ) : 'active';

        $action = [
            'blocked' => __( 'Move to blocked', 'mxalfwp-domain' ),
        ];

        if ($itemStatus == 'blocked') {

            unset( $action['blocked'] );

            $action['restore'] = __( 'Restore Item', 'mxalfwp-domain' );
            $action['delete']  = __( 'Delete Permanently', 'mxalfwp-domain' );

        }

        return $action;

    }

    public function search_box( $text, $inputId )
    {

        if (empty($_REQUEST['s']) && ! $this->has_items()) {
            return;
        }

        ?>
            <p class="search-box">
                <label class="screen-reader-text" for="<?php echo esc_attr( $inputId ); ?>"><?php echo $text; ?>:</label>
                <input type="search" id="<?php echo esc_attr( $inputId ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
                    <?php submit_button( $text, '', '', false, ['id' => 'mxalfwp-search-submit'] ); ?>
            </p>
        <?php

    }

    protected function get_views()
    {

        global $wpdb;

        $tableName     = $wpdb->prefix . MXALFWP_TABLE_SLUG;
        $itemStatus    = isset( $_GET['user_status'] ) ? trim( $_GET['user_status'] ) : 'active';
        $activeNumber = $wpdb->get_var( "SELECT COUNT(id) FROM {$tableName} WHERE status='active';" );
        $blockedNumber   = $wpdb->get_var( "SELECT COUNT(id) FROM {$tableName} WHERE status='blocked';" );
        $url           = admin_url( 'admin.php?page=' . MXALFWP_MAIN_MENU_SLUG );

        $statusLinks   = [];

        // active
        $statusLinks['active'] = [
            'url'     => add_query_arg( 'user_status', 'active', $url ),
            'label'   => sprintf(
                _nx(
                    'Active <span class="count">(%s)</span>',
                    'Active <span class="count">(%s)</span>',
                    $activeNumber,
                    'active'
                ),
                number_format_i18n( $activeNumber )
            ),
            'current' => 'active' == $itemStatus,
        ];

        if ($activeNumber == 0) {
            unset( $statusLinks['active'] );
        }

        // blocked
        $statusLinks['blocked'] = [
            'url'     => add_query_arg( 'user_status', 'blocked', $url ),
            'label'   => sprintf(
                _nx(
                    'Blocked <span class="count">(%s)</span>',
                    'Blocked <span class="count">(%s)</span>',
                    $blockedNumber,
                    'blocked'
                ),
                number_format_i18n( $blockedNumber )
            ),
            'current' => 'blocked' == $itemStatus,
        ];

        if ($blockedNumber == 0) {
            unset( $statusLinks['blocked'] );
        }

        return $this->get_views_links( $statusLinks );

    }

    public function no_items()
    {

        $itemStatus = isset( $_GET['user_status'] ) ? trim( $_GET['user_status'] ) : 'active';
        
        if ($itemStatus == 'blocked') {

            _e( 'No items found from blocked users.' );

        } else {

            _e( 'No affiliate activity found.' );

        }

    }

}

if (!function_exists('mxalfwpTableLayout')) {

    function mxalfwpTableLayout() {

        global $wpdb;
    
        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;
    
        $isTable = $wpdb->get_var(
    
            $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $tableName ) )
    
        );
    
        if (!$isTable) return;
    
        $tableInstance = new MXALFWPCustomTable();
        
        $tableInstance->prepare_items();
    
        $tableInstance->views();
    
        echo '<form id="mxalfwp_custom_talbe_search_form" method="post">';
            $tableInstance->search_box( 'Search Items', 'mxalfwp_custom_talbe_search_input' );
        echo '</form>';
    
        echo '<form id="mxalfwp_custom_talbe_form" method="post">';
            $tableInstance->display();
        echo '</form>';
    
    }

}