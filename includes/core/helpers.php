<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/*
* Require class for admin panel
*/
function mxalfwpRequireClassFileAdmin( $file ) {

    require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/admin/classes/' . $file;

}


/*
* Require class for frontend panel
*/
function mxalfwpRequireClassFileFrontend( $file ) {

    require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/frontend/classes/' . $file;

}

/*
* Require a Model
*/
function mxalfwpUseModel( $model ) {

    require_once MXALFWP_PLUGIN_ABS_PATH . 'includes/admin/models/' . $model . '.php';

}

/*
* Debugging
*/
function mxalfwpDebugToFile( $content ) {

    $content = mxalfwpContentToString( $content );

    $path = MXALFWP_PLUGIN_ABS_PATH . 'mx-debug' ;

    if (!file_exists($path)) {

        mkdir( $path, 0777, true );

        file_put_contents( $path . '/mx-debug.txt', $content );

    } else {

        file_put_contents( $path . '/mx-debug.txt', $content );

    }

}
    // pretty debug text to the file
    function mxalfwpContentToString( $content ) {

        ob_start();

        var_dump( $content );

        return ob_get_clean();

    }

/*
* Manage posts columns. Add column to position
*/
function mxalfwpInsertNewColumnToPosition( array $columns, int $position, array $newColumn ) {

    $chunkedArray = array_chunk( $columns, $position, true );

    $result = array_merge( $chunkedArray[0], $newColumn, $chunkedArray[1] );

    return $result;

}

/*
* Redirect from admin panel
*/
function mxalfwpAdminRedirect( $url ) {

    if (!$url) return;

    add_action( 'admin_footer', function() use ( $url ) {
        echo "<script>window.location.href = '$url';</script>";
    } );

}

/*
* Earned
*/
function mxalfwpPartnerEarned( $userId ) {

    $earned = 0;

    $inst   = new MXALFWPModel();

    $linksData = $inst->getResults(NULL, 'user_id', intval($userId));

    if( count( $linksData ) == 0 ) {
        return 0;
    }

    foreach ($linksData as $value) {
        $earned += floatval($value->earned);
    }

    return $earned;

}

/*
* Bought
*/
function mxalfwpPartnerBought( $userId ) {

    $bought = 0;

    $inst =  new MXALFWPModel();

    $linksData = $inst->getResults(NULL, 'user_id', intval($userId));

    if( count( $linksData ) == 0 ) {
        return 0;
    }

    foreach ($linksData as $value) {
        $bought += intval($value->bought);
    }

    return $bought;

}

/*
* Paid
*/
function mxalfwpPartnerPaid( $userId ) {

    $paid = 0;

    $inst =  new MXALFWPModel();

    $linksData = $inst->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($userId));

    if( $linksData == NULL ) {
        return 0;
    }

    return $linksData->paid;

}

/*
* Get Partner Status
*/
function mxalfwpGetPartnerStatus( $userId ) {

    $inst =  new MXALFWPModel();

    $linksData = $inst->getRow(MXALFWP_USERS_TABLE_SLUG, 'user_id', intval($userId));

    if( $linksData == NULL ) {
        return 0;
    }

    return $linksData->status;

}