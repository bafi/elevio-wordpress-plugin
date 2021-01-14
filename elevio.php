<?php
/*
Plugin Name: Elevio
Plugin URI: https://elev.io/
Description: A better way for your users to access the help they need.
Author: Elevio
Author URI: https://elev.io
Version: 4.1.0
*/

function elevio_posts_tax_query($tax_query)
{
    return $tax_query;
}

if (is_admin()) {
    require_once dirname(__FILE__).'/plugin_files/ElevioAdmin.class.php';
    ElevioAdmin::get_instance();
} else {
    require_once dirname(__FILE__).'/plugin_files/Elevio.class.php';
    Elevio::get_instance();
}

function elevio_sync_init()
{
    $request = $_REQUEST['elevio_sync'];
    require_once dirname(__FILE__).'/plugin_files/ElevioSync.class.php';
    $syncer = new ElevioSync();
    $output = $syncer->run($request);

    if (! is_null($output)) {
        header('Content-type: application/json');
        wp_send_json($output);
    }
}

if (isset($_REQUEST['elevio_sync'])) {
    add_action('wp_loaded', 'elevio_sync_init');
}
