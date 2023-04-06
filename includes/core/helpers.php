<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/*
* Require class for admin panel
*/
function mxalfwpRequireClassFileAdmin($file)
{

    require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/admin/classes/' . $file;
}


/*
* Require class for frontend panel
*/
function mxalfwpRequireClassFileFrontend($file)
{

    require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/frontend/classes/' . $file;
}

/*
* Require a Model
*/
function mxalfwpUseModel($model)
{

    require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/admin/models/' . $model . '.php';
}

/*
* Debugging
*/
function mxalfwpDebugToFile($content)
{

    $content = mxalfwpContentToString($content);

    $path = MXALFWP_PLUGIN_ABS_PATH . 'mx-debug';

    if (!file_exists($path)) {

        mkdir($path, 0777, true);

        file_put_contents($path . '/mx-debug.txt', $content);
    } else {

        file_put_contents($path . '/mx-debug.txt', $content);
    }
}
// pretty debug text to the file
function mxalfwpContentToString($content)
{

    ob_start();

    var_dump($content);

    return ob_get_clean();
}

/*
* Manage posts columns. Add column to position
*/
function mxalfwpInsertNewColumnToPosition(array $columns, int $position, array $newColumn)
{

    $chunkedArray = array_chunk($columns, $position, true);

    $result = array_merge($chunkedArray[0], $newColumn, $chunkedArray[1]);

    return $result;
}

/*
* Redirect from admin panel
*/
function mxalfwpAdminRedirect($url)
{

    if (!$url) return;

    add_action('admin_footer', function () use ($url) {
        echo "<script>window.location.href = '$url';</script>";
    });
}

/*
* Earned
*/
function mxalfwpPartnerEarnedAmount($userId)
{

    $earned = 0;

    $inst =  new MXALFWPModel();

    $and = "AND status = 'completed'";

    $ordersData = $inst->getResults(MXALFWP_ORDERS_TABLE_SLUG, 'user_id', intval($userId), $and);

    if ($ordersData == NULL) {
        return $earned;
    }

    foreach ($ordersData as $value) {
        $linkId = $value->link_id;

        $linkData = $inst->getRow(NULL, 'id', intval($linkId));

        if ($linkData == NULL) {
            $earned += 0;
            continue;
        } else {

            $percent = floatval($linkData->percent);
            $amount  = floatval($value->amount);
            $earned  += ($amount * $percent) / 100;

        }
    }

    return number_format( $earned, 2 );
}

/*
* All Orders made
*/
function mxalfwpPartnerAllOrders($userId)
{

    $inst   =  new MXALFWPModel();

    $userId = intval($userId);

    $and    = "AND user_id = $userId";

    $count  = $inst->getVar(MXALFWP_ORDERS_TABLE_SLUG, 'user_id', $and);

    if ($count == NULL) return 0;

    return $count;
}

/*
* All Competed Orders made
*/
function mxalfwpPartnerAllCompetedOrders($userId)
{

    $inst   =  new MXALFWPModel();

    $userId = intval($userId);

    $and    = "AND user_id = $userId AND status = 'completed'";

    $count  = $inst->getVar(MXALFWP_ORDERS_TABLE_SLUG, 'user_id', $and);

    if ($count == NULL) return 0;

    return $count;
}

/*
* Orders per link made
*/
function mxalfwpPartnerOrdersPerLink($userId, $linkId)
{

    $inst   =  new MXALFWPModel();

    $userId = intval($userId);
    $linkId = intval($linkId);

    $and    = "AND user_id = $userId AND link_id = $linkId";

    $count  = $inst->getVar(MXALFWP_ORDERS_TABLE_SLUG, 'user_id', $and);

    if ($count == NULL) return 0;

    return $count;
}

/*
* Competed Orders per link made
*/
function mxalfwpPartnerCompletedOrdersPerLink($userId, $linkId)
{

    $inst   =  new MXALFWPModel();

    $userId = intval($userId);
    $linkId = intval($linkId);

    $and    = "AND user_id = $userId AND link_id = $linkId AND status = 'completed'";

    $count  = $inst->getVar(MXALFWP_ORDERS_TABLE_SLUG, 'user_id', $and);

    if ($count == NULL) return 0;

    return $count;
}

/*
* Paid
*/
function mxalfwpPartnerPaid($userId)
{

    $inst =  new MXALFWPModel();

    $linksData = $inst->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($userId));

    if ($linksData == NULL) {
        return 0;
    }

    return number_format($linksData->paid, 2);
}

/*
* Get Partner Status
*/
function mxalfwpGetPartnerStatus($userId)
{

    $inst =  new MXALFWPModel();

    $linksData = $inst->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($userId));

    if ($linksData == NULL) {
        return 0;
    }

    return $linksData->status;
}

/*
* Get Completed Orders Amount Per Link
*/
function mxalfwpGetCompletedOrdersAmountPerLink($linkId)
{

    $amount = 0;

    $inst =  new MXALFWPModel();

    $and = "AND status = 'completed'";

    $ordersData = $inst->getResults(MXALFWP_ORDERS_TABLE_SLUG, 'link_id', intval($linkId), $and);

    if ($ordersData == NULL) {
        return $amount;
    }

    foreach ($ordersData as $value) {
        $amount += floatval($value->amount);
    }

    return $amount;
}

/*
* Get Completed Orders Amount Per Link
*/
function mxalfwpGetPartnerCompletedOrdersAmountPerLink($linkId) {

    $earned = 0;

    $inst =  new MXALFWPModel();

    $and = "AND status = 'completed'";

    $ordersData = $inst->getResults(MXALFWP_ORDERS_TABLE_SLUG, 'link_id', intval($linkId), $and);

    if ($ordersData == NULL) {
        return $earned;
    }

    foreach ($ordersData as $value) {
        $linkId = $value->link_id;

        $linkData = $inst->getRow(NULL, 'id', intval($linkId));

        if ($linkData == NULL) {
            $earned += 0;
            continue;
        } else {

            $percent = floatval($linkData->percent);
            $amount  = floatval($value->amount);
            $earned  += ($amount * $percent) / 100;
        }
    }

    return number_format($earned, 2);

}

/*
* Get Partner's Completed Orders Amount
*/
function mxalfwpGetPartnerCompletedOrdersAmount($userId) {

    $amount = 0;

    $inst =  new MXALFWPModel();

    $and = "AND status = 'completed'";

    $ordersData = $inst->getResults(MXALFWP_ORDERS_TABLE_SLUG, 'user_id', intval($userId), $and);

    if ($ordersData == NULL) {
        return $amount;
    }

    foreach ($ordersData as $value) {
        $linkId = $value->link_id;

        $linkData = $inst->getRow(NULL, 'id', intval($linkId));

        if ($linkData == NULL) {
            $amount += 0;
            continue;
        } else {

            $amount  += floatval($value->amount);

        }
    }

    return number_format( $amount, 2 );

}

/*
* Get Partner's Completed Orders Amount
*/
function mxalfwpGetOrderById($orderId) {

    $inst =  new MXALFWPModel();

    $orderData = $inst->getRow(MXALFWP_ORDERS_TABLE_SLUG, 'order_id', intval($orderId));

    if ($orderData == NULL) {
        return false;
    }

    return [
        'user_id' => $orderData->user_id,
        'link_id' => $orderData->link_id
    ];

}

/*
* Get link row by id
*/
function mxalfwpGetLinkRowById($linkId) {

    $inst =  new MXALFWPModel();

    $linkData = $inst->getRow(NULL, 'id', intval($linkId));

    if ($linkData == NULL) {
        return false;
    }

    return $linkData;

}

/*
* Get link_key by order id
*/
function mxalfwpGetLinkKeyByOrderId($orderId) {

    $order = mxalfwpGetOrderById($orderId);

    if( $order ) {

        if(mxalfwpGetPartnerStatus($order['user_id']) == 'active') {

            $linkData = mxalfwpGetLinkRowById($order['link_id']);

            if($linkData) {
                return $linkData->link_key;
            }
        }

    }

    return false;

}
