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

        // delete action
        $deleteId = isset($_GET['delete']) ? trim(sanitize_text_field($_GET['delete'])) : false;

        if ($deleteId) {

            if (isset($_GET['mxalfwp_nonce']) || wp_verify_nonce($_GET['mxalfwp_nonce'], 'delete')) {

                $this->modelInstance->deletePermanently($deleteId);
            }

            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG . '&item_status=trash'));

            return;
        }

        // restore action
        $restore_id = isset($_GET['restore']) ? trim(sanitize_text_field($_GET['restore'])) : false;

        if ($restore_id) {

            if (isset($_GET['mxalfwp_nonce']) || wp_verify_nonce($_GET['mxalfwp_nonce'], 'restore')) {

                $this->modelInstance->restoreItem($restore_id);
            }

            mxalfwpAdminRedirect(admin_url('admin.php?page=' . MXALFWP_MAIN_MENU_SLUG . '&item_status=trash'));

            return;
        }

        // trash action
        $trash_id = isset($_GET['trash']) ? trim(sanitize_text_field($_GET['trash'])) : false;

        if ($trash_id) {

            if (isset($_GET['mxalfwp_nonce']) || wp_verify_nonce($_GET['mxalfwp_nonce'], 'trash')) {

                $this->modelInstance->moveToTrash($trash_id);
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
}
