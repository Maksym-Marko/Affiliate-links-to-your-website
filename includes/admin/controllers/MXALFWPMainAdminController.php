<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPMainAdminController extends MXALFWPController
{

    protected $modelInstance;

    public function __construct()
    {

        $this->modelInstance = new MXALFWPMainAdminModel();
    }

    public function index()
    {

        return new MXALFWPMxView('affiliate-links');
    }

    public function submenu()
    {

        return new MXALFWPMxView('sub-page');
    }

    public function visitedPageDetails()
    {

        $linkId = isset($_GET['mxalfwp-link-id']) ? trim(sanitize_text_field($_GET['mxalfwp-link-id'])) : 0;

        $visitedPage = isset($_GET['mxalfwp-visited-page']) ? trim(sanitize_url($_GET['mxalfwp-visited-page'])) : 0;

        $linkData = $this->modelInstance->getRow(NULL, 'id', intval($linkId));

        if ($linkData == NULL) {

            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG));
            return;
        }

        $data = [
            'linkData'    => $linkData,
            'visitedPage' => $visitedPage,
        ];

        return new MXALFWPMxView('visited-page-details', $data);
    }

    public function settingsMenuItemAction()
    {

        return new MXALFWPMxView('settings-page');
    }

    public function linksAnalytics()
    {

        // restore action
        $restoreId = isset($_GET['restore']) ? trim(sanitize_text_field($_GET['restore'])) : false;

        if ($restoreId) {

            if (isset($_GET['mxalfwp_nonce']) || wp_verify_nonce($_GET['mxalfwp_nonce'], 'restore')) {

                $this->modelInstance->restoreItem($restoreId);
            }

            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG . '&item_status=trash'));

            return;
        }

        // trash action
        $trashId = isset($_GET['trash']) ? trim(sanitize_text_field($_GET['trash'])) : false;

        if ($trashId) {

            if (isset($_GET['mxalfwp_nonce']) || wp_verify_nonce($_GET['mxalfwp_nonce'], 'trash')) {

                $this->modelInstance->moveToTrash($trashId);
            }

            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG));

            return;
        }

        // edit action
        $linkId = isset($_GET['mxalfwp-link-id']) ? trim(sanitize_text_field($_GET['mxalfwp-link-id'])) : 0;

        $data = $this->modelInstance->getRow(NULL, 'id', intval($linkId));

        if ($data == NULL) {
            if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == NULL) {
                mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG));
            } else {
                mxalfwpAdminRedirect($_SERVER['HTTP_REFERER']);
            }

            return;
        }

        return new MXALFWPMxView('links-analytics', $data);
    }

    // create table item
    public function createTableItem()
    {
        return new MXALFWPMxView('create-table-item');
    }

    // create table item
    public function managePartner()
    {

        $userId = isset($_GET['user_id']) ? trim(sanitize_text_field($_GET['user_id'])) : false;

        if (!$userId) {
            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG));
            return;
        }

        // Active links
        $active = "AND status = 'active'";
        $activeLinksData  = $this->modelInstance->getResults(NULL, 'user_id', intval($userId), $active);
        $activePageViews  = 0;
        $activePages      = 0;
        $activeLinks      = 0;

        // Trash links
        $trash = "AND status = 'trash'";
        $trashLinksData  = $this->modelInstance->getResults(NULL, 'user_id', intval($userId), $trash);
        $trashPageViews  = 0;
        $trashPages      = 0;
        $trashLinks      = 0;

        if (count($activeLinksData) == 0 && count($trashLinksData) == 0) {
            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG));
            return;
        }

        $activeLinks = count($activeLinksData);
        $trashLinks  = count($trashLinksData);        
        
        // 
        $activePagesArray = [];
        $trashPagesArray  = [];

        // Active Data Parse
        foreach ($activeLinksData as $key => $value) {
            $unserialized = maybe_unserialize($value->link_data);
            if ($unserialized !== NULL) {

                //
                $activePagesArray = array_merge($activePagesArray, $unserialized['data']);

                //
                foreach ($unserialized['data'] as $value_) {
                    $activePageViews += count($value_);
                }
            }

        }

        $activePages = count($activePagesArray);


        // Trash Data Parse
        foreach ($trashLinksData as $key => $value) {
            $unserialized = maybe_unserialize($value->link_data);
            if ($unserialized !== NULL) {

                // 
                $trashPagesArray = array_merge($trashPagesArray, $unserialized['data']);

                //
                foreach ($unserialized['data'] as $value_) {
                    $trashPageViews += count($value_);
                }
            }

        }

        $trashPages = count($trashPagesArray);

        $userLinkData = $this->modelInstance->getRow(NULL, 'user_id', intval($userId));
        $userData = $this->modelInstance->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($userId));

        $userKey = null;

        if( $userData !== NULL ) {
            $userKey = $userData->user_key;
        }

        $orders = mxalfwpPartnerAllCompetedOrders($userLinkData->user_id);
        $earned = mxalfwpPartnerEarnedAmount($userLinkData->user_id);

        $data = [
            // 'activeLinksData' => $activeLinksData,
            'userData'  => [
                'user_id'     => $userLinkData->user_id,
                'user_name'   => $userLinkData->user_name,
                'user_key'    => $userKey,
                'status'      => $userData->status,
            ],
            'activeLinks'     => $activeLinks,
            'activePages'     => $activePages,
            'activePageViews' => $activePageViews,

            'trashLinks'     => $trashLinks,
            'trashPages'     => $trashPages,
            'trashPageViews' => $trashPageViews,

            'orders'         => $orders,
            'earned'         => $earned,
            'paid'           => number_format($userData->paid, 2)
        ];

        return new MXALFWPMxView('manage-partner', $data);
    }


}
