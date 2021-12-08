<?php
/*
Plugin Name: Elevio
Plugin URI: https://elev.io/
Description: A better way for your users to access the help they need.
Author: Elevio
Author URI: https://elev.io
Version: 4.3.0
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
add_filter('elevio_retrieve_categories_in_all_languages', 'elevio_retrieve_categories_in_all_languages', 10, 2);
add_filter('elevio_append_language_id_to_article', 'elevio_append_language_id_to_article', 10, 2);
add_filter('elevio_add_article_filters', 'elevio_add_article_filters', 10, 2);
add_filter('elevio_aggregate_translated_articles', 'elevio_aggregate_translated_articles');
add_filter('elevio_aggregate_translated_categories', 'elevio_aggregate_translated_categories', 10, 2);

/**
 * Aggregate the translated categories
 * @param $categories
 * @param $cat_type
 *
 * @return mixed
 */
function elevio_aggregate_translated_categories($categories, $cat_type)
{
    global $sitepress;
    $default_language = $sitepress->get_default_language();
    foreach ($categories as $key => $category) {
        $category_id            = wpml_object_id_filter($category->id, $cat_type, true, $default_language);
        $categories[ $key ]->id = $category_id;
    }

    return $categories;
}

/**
 * Aggregate the translated articles
 * @param $posts
 *
 * @return mixed
 */
function elevio_aggregate_translated_articles($posts)
{
    global $sitepress;
    $post_type        = Elevio::get_instance()->get_post_taxonomy();
    $default_language = $sitepress->get_default_language();
    foreach ($posts as $key => $post) {
        $post_id           = wpml_object_id_filter($post->id, $post_type, true, $default_language);
        $posts[ $key ]->id = $post_id;
    }

    return $posts;
}

/**
 * Append more filters on articles
 * @param $filters
 *
 * @return mixed
 */
function elevio_add_article_filters($filters)
{
    $filters['suppress_filters'] = true;
    return $filters;
}

/**
 * Append language id to article
 * @param $post
 * @param $post_id
 *
 * @return mixed
 */
function elevio_append_language_id_to_article($post, $post_id)
{
    $language_code = elevio_get_language_code($post_id);
    if ($language_code) {
        $post->language_id = $language_code;
    }

    return $post;
}

/**
 * Get the language of the post
 * @param $post_id
 *
 * @return false|mixed
 */
function elevio_get_language_code($post_id)
{
    if (! has_filter('wpml_post_language_details')) {
        return false;
    }

    $output = apply_filters('wpml_post_language_details', null, $post_id);
    if (is_array($output) && isset($output['language_code'])) {
        return $output['language_code'];
    }

    return false;
}

/**
 * Append language id to all categories using the WPML methods
 * @param $categories
 * @param $args
 *
 * @return mixed
 */
function elevio_retrieve_categories_in_all_languages($categories, $args)
{

    // Append active language to categories
    foreach ($categories as $key => $category) {
        $categories[ $key ]->language_id = ICL_LANGUAGE_CODE;
    }

    global $sitepress;
    $args['taxonomy'] = Elevio::get_instance()->get_category_taxonomy();

    // Loop on available languages
    foreach ($sitepress->get_active_languages() as $active_language) {
        $language_code = $active_language['code'];

        // Escape getting the default language
        if (ICL_LANGUAGE_CODE === $language_code) {
            continue;
        }

        // Change site language by code
        do_action('wpml_switch_language', $language_code);
        $wp_categories = get_categories($args);

        // Loop on categories and append the language ID
        foreach ($wp_categories as $wp_category) {
            if ($wp_category->term_id == 1 && $wp_category->slug == 'uncategorized' && $args['taxonomy'] == 'category') {
                continue;
            }
            $category              = new Elevio_Sync_Category($wp_category);
            $category->language_id = $language_code;
            $categories[]          = $category;
        }
    }

    // Reset to default site language
    do_action('wpml_switch_language', ICL_LANGUAGE_CODE);

    return $categories;
}
