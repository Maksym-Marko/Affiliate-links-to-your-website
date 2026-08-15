<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class MXALFWPAffiliateLinks extends WP_List_Table
{

    /*
    * MXALFWPAffiliateLinks
    */
    public $search     = '';
    public $status     = '';
    public $searchUser = '';

    public function __construct($args = [])
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
            $offset = $perPage * ($currentPage - 1);
        } else {
            $offset = 0;
        }

        // sortable — these are read-only display parameters; whitelist values to prevent SQL injection
        $allowedOrder = ['asc', 'desc'];
        $order = isset($_GET['order']) ? strtolower( sanitize_key( wp_unslash( $_GET['order'] ) ) ) : 'desc'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!in_array($order, $allowedOrder, true)) {
            $order = 'desc';
        }

        $allowedOrderBy = ['id', 'user_id', 'user_name', 'link', 'created_at'];
        $orderBy = isset($_GET['orderby']) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'id'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!in_array($orderBy, $allowedOrderBy, true)) {
            $orderBy = 'id';
        }

        // search
        if (!empty($_REQUEST['s'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $this->search = $wpdb->prepare( 'AND link LIKE %s', '%' . $wpdb->esc_like( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) ) . '%' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }

        // status — whitelist to prevent SQL injection
        $allowedStatuses = ['active', 'trash'];
        $itemStatus = isset($_GET['link_status']) ? sanitize_text_field( wp_unslash( $_GET['link_status'] ) ) : 'active'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!in_array($itemStatus, $allowedStatuses, true)) {
            $itemStatus = 'active';
        }
        $this->status = $wpdb->prepare( 'AND status = %s', $itemStatus );

        // Links per User — absint ensures safe integer for SQL
        $itemUserId = isset($_GET['user_id']) ? absint( $_GET['user_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ($itemUserId) {
            $this->searchUser = $wpdb->prepare( 'AND user_id = %d', $itemUserId );
        }

        // get data
        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- tableName from prefix+constant; orderBy/order whitelisted above; status/search/searchUser pre-escaped via prepare()
        $items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$tableName} WHERE 1 = 1 {$this->status} {$this->search} {$this->searchUser} ORDER BY {$orderBy} {$order} LIMIT %d OFFSET %d;",
                $perPage,
                $offset
            ),
            ARRAY_A
        );

        $count = $wpdb->get_var(
            "SELECT COUNT(id) FROM {$tableName} WHERE 1 = 1 {$this->status} {$this->search} {$this->searchUser};"
        );
        // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

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
                'total_pages' => ceil($count / $perPage),
            ]
        );
    }

    public function get_columns()
    {

        return [
            'cb'              => '<input type="checkbox" />',
            'id'              => __('ID', 'affiliate-links-woocommerce'),
            'user_id'         => __('User ID', 'affiliate-links-woocommerce'),
            'user_name'       => __('User Name', 'affiliate-links-woocommerce'),
            'link_data'       => __('Links Data', 'affiliate-links-woocommerce'),
            'link'            => __('Link', 'affiliate-links-woocommerce'),
            'pages'           => __('Pages', 'affiliate-links-woocommerce'),
            'views'           => __('Views', 'affiliate-links-woocommerce'),
            'orders'          => __('Orders', 'affiliate-links-woocommerce'),
            'orders_competed' => __('Competed Orders', 'affiliate-links-woocommerce'),
            'earned_amount'   => __('Earned Amoun', 'affiliate-links-woocommerce'),
            'earned'          => __('Earned by Partner', 'affiliate-links-woocommerce'),
            'status'          => __('Status', 'affiliate-links-woocommerce'),
            'created_at'      => __('Created', 'affiliate-links-woocommerce'),
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

    public function column_default($item, $columnName)
    {

        do_action("manage_mxalfwp_items_custom_column", $columnName, $item); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
    }

    public function column_cb($item)
    {

        if (mxalfwpGetPartnerStatus($item['user_id']) == 'active') {
            printf( '<input type="checkbox" class="mxalfwp_bulk_input" name="mxalfwp-action-%1$s" value="%1$s" />', absint( $item['id'] ) );
        }
    }

    public function column_id($item)
    {

        echo absint( $item['id'] );
    }

    public function column_user_id($item)
    {

        echo absint( $item['user_id'] );
    }

    public function column_user_name($item)
    {

        $url      = admin_url('admin.php?page=' . MXALFWP_SINGLE_TABLE_ITEM_MENU);

        $userId  = get_current_user_id();

        $userStatus = mxalfwpGetPartnerStatus($userId);

        $canEdit = current_user_can('edit_user', $userId);

        $output   = '<strong>';

        if ($canEdit) {

            $output .= '<a href="' . esc_url( admin_url('admin.php?page=mxalfwp-manage-partner&user_id=' . absint( $item['user_id'] ) ) ) . '">' . esc_html( $item['user_name'] ) . '</a>';

            $actions['edit']  = '<a href="' . esc_url($url) . '&mxalfwp-link-id=' . absint( $item['id'] ) . '">' . esc_html__('Link Analytics', 'affiliate-links-woocommerce') . '</a>';
            $actions['trash'] = '<a class="submitdelete" aria-label="' . esc_attr__('Trash', 'affiliate-links-woocommerce') . '" href="' . esc_url(
                wp_nonce_url(
                    add_query_arg(
                        [
                            'trash' => $item['id'],
                        ],
                        $url
                    ),
                    'trash',
                    'mxalfwp_nonce'
                )
            ) . '">' . esc_html__('Trash', 'affiliate-links-woocommerce') . '</a>';

            $itemStatus = isset($_GET['link_status']) ? sanitize_text_field( wp_unslash( $_GET['link_status'] ) ) : 'active'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

            if ($itemStatus == 'trash') {

                unset($actions['edit']);
                unset($actions['trash']);

                if ($userStatus == 'active') {
                    $actions['restore'] = '<a aria-label="' . esc_attr__('Restore', 'affiliate-links-woocommerce') . '" href="' . esc_url(
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
                    ) . '">' . esc_html__('Restore', 'affiliate-links-woocommerce') . '</a>';
                } else {
                    $actions['blocked'] = esc_html__('This user is blocked', 'affiliate-links-woocommerce');
                }

            }

            $rowActions = [];

            foreach ($actions as $action => $link) {
                $rowActions[] = '<span class="' . esc_attr($action) . '">' . $link . '</span>';
            }

            $output .= '<div class="row-actions">' . implode(' | ', $rowActions) . '</div>';
        } else {

            $output .= esc_html( $item['title'] );
        }

        $output .= '</strong>';

        echo wp_kses_post( $output );
    }

    public function column_link($item)
    {

        echo esc_html( $item['link'] . '/?mxpartnerlink=' . $item['link_key'] );
    }

    public function column_pages($item)
    {

        $linkData = maybe_unserialize($item['link_data']);

        echo absint( count($linkData['data']) );
    }

    public function column_views($item)
    {

        $linkData = maybe_unserialize($item['link_data']);

        $views = 0;

        foreach ($linkData['data'] as $key => $value) {
            $views += count($value);
        }

        echo absint( $views );
    }

    public function column_orders($item)
    {

        echo absint( mxalfwpPartnerOrdersPerLink($item['user_id'], $item['id']) );

    }

    public function column_orders_competed($item)
    {

        echo absint( mxalfwpPartnerCompletedOrdersPerLink($item['user_id'], $item['id']) );

    }

    public function column_earned_amount($item)
    {

        echo esc_html( get_option('mxalfwp_default_currency_sign') ) . ' ' . esc_html( mxalfwpGetCompletedOrdersAmountPerLink($item['id']) );

    }

    public function column_link_data($item)
    {

        // var_dump( $item['link_data'] );

    }

    public function column_earned($item)
    {

        echo esc_html( get_option('mxalfwp_default_currency_sign') ) . ' ' . esc_html( mxalfwpGetPartnerCompletedOrdersAmountPerLink($item['id']) );
    }

    public function column_created_at($item)
    {

        echo esc_html( $item['created_at'] );
    }

    protected function get_bulk_actions()
    {

        if (!current_user_can('edit_posts')) {
            return [];
        }

        $itemStatus = isset($_GET['link_status']) ? sanitize_text_field( wp_unslash( $_GET['link_status'] ) ) : 'active'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        $action = [
            'trash' => __('Move to trash', 'affiliate-links-woocommerce'),
        ];

        if ($itemStatus == 'trash') {

            unset($action['trash']);

            $action['restore'] = __('Restore Links', 'affiliate-links-woocommerce');
            // $action['delete']  = __( 'Delete Permanently', 'affiliate-links-woocommerce' );

        }

        return $action;
    }

    public function search_box($text, $inputId)
    {

        if (empty($_REQUEST['s']) && !$this->has_items()) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr($inputId); ?>"><?php echo esc_html( $text ); ?>:</label>
            <input type="search" id="<?php echo esc_attr($inputId); ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button($text, '', '', false, ['id' => 'mxalfwp-search-submit']); ?>
        </p>
<?php

    }

    protected function get_views()
    {

        global $wpdb;

        $tableName    = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        $itemStatus   = 'active';

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- tableName from prefix+constant; searchUser pre-escaped via prepare()
        $activeNumber = $wpdb->get_var(
            "SELECT COUNT(id) FROM {$tableName} WHERE status='active' {$this->searchUser};"
        );
        $trashNumber  = $wpdb->get_var(
            "SELECT COUNT(id) FROM {$tableName} WHERE status='trash' {$this->searchUser};"
        );
        // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

        if ($this->status !== $wpdb->prepare( 'AND status = %s', 'active' )) {
            $itemStatus = 'trash';
        }

        $url           = admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG);

        $statusLinks   = [];

        // active
        $statusLinks['active'] = [
            'url'     => add_query_arg('link_status', 'active', $url),
            'label'   => sprintf(
                // translators: %s: number of active affiliate links
                _nx(
                    'Active <span class="count">(%s)</span>',
                    'Active <span class="count">(%s)</span>',
                    $activeNumber,
                    'active',
                    'affiliate-links-woocommerce'
                ),
                number_format_i18n($activeNumber)
            ),
            'current' => 'active' == $itemStatus,
        ];

        if ($activeNumber == 0) {
            unset($statusLinks['active']);
        }

        // trash
        $statusLinks['trash'] = [
            'url'     => add_query_arg('link_status', 'trash', $url),
            'label'   => sprintf(
                // translators: %s: number of trashed affiliate links
                _nx(
                    'Trash <span class="count">(%s)</span>',
                    'Trash <span class="count">(%s)</span>',
                    $trashNumber,
                    'trash',
                    'affiliate-links-woocommerce'
                ),
                number_format_i18n($trashNumber)
            ),
            'current' => 'trash' == $itemStatus,
        ];

        if ($trashNumber == 0) {
            unset($statusLinks['trash']);
        }

        return $this->get_views_links($statusLinks);
    }

    public function no_items()
    {

        $itemStatus = isset($_GET['link_status']) ? sanitize_text_field( wp_unslash( $_GET['link_status'] ) ) : 'active'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        if ($itemStatus == 'trash') {

            esc_html_e('No items found from trash users.', 'affiliate-links-woocommerce');
        } else {

            esc_html_e('No affiliate activity found.', 'affiliate-links-woocommerce');
        }
    }
}

if (!function_exists('mxalfwpTableLayout')) {

    function mxalfwpTableLayout()
    {

        global $wpdb;

        $tableName = $wpdb->prefix . MXALFWP_TABLE_SLUG;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $isTable = $wpdb->get_var(

            $wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->esc_like($tableName))

        );

        if (!$isTable) return;

        $tableInstance = new MXALFWPAffiliateLinks();

        $tableInstance->prepare_items();

        $tableInstance->views();

        echo '<form id="mxalfwp_custom_talbe_search_form" method="post">';
        $tableInstance->search_box('Search Link', 'mxalfwp_custom_talbe_search_input');
        echo '</form>';

        echo '<form id="mxalfwp_custom_talbe_form" method="post">';
        $tableInstance->display();
        echo '</form>';
    }
}
