<?php

// uninstall
if (!defined('WP_UNINSTALL_PLUGIN')) die();

global $wpdb;

// table name
$mxalfwp_table_names   = [];

$mxalfwp_table_names[] = $wpdb->prefix . 'mxalfwp_table_slug';

// drop table(s);
foreach ($mxalfwp_table_names as $mxalfwp_table_name) {

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.NotPrepared -- one-time uninstall cleanup; DROP TABLE cannot use placeholders and the identifier is escaped with esc_sql()
    $wpdb->query( 'DROP TABLE IF EXISTS ' . esc_sql( $mxalfwp_table_name ) );
}

// Delete posts CPT
$posts = get_posts(['post_type' => 'mxalfwp_book', 'numberposts' => -1]);

foreach ($posts as $post) {
    wp_delete_post($post->ID, true);
}

//delete_option( 'some_option' );
