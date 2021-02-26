<?php

class ElevioSync
{
    public function __construct()
    {
        require_once dirname(__FILE__).'/models/category.php';
        require_once dirname(__FILE__).'/models/post.php';
        require_once dirname(__FILE__).'/models/tag.php';
    }

    public function run($query)
    {
        if ($query === 'categories') {
            return $this->syncCategories();
        } elseif ($query === 'posts') {
            return $this->syncTopics();
        }
    }

    public function syncCategories($args = [])
    {
        $args['taxonomy'] = Elevio::get_instance()->get_category_taxonomy();

        $wp_categories = get_categories($args);
        $categories = [];
        foreach ($wp_categories as $wp_category) {
            if ($wp_category->term_id == 1 && $wp_category->slug == 'uncategorized' && $args['taxonomy'] == 'category') {
                continue;
            }
            $category = new Elevio_Sync_Category($wp_category);
            $categories[] = $category;
        }

	    if ( $this->is_multilanguage_allowed() ) {
	        // Integrate with WPML
		    $categories = apply_filters( 'elevio_retrieve_categories_in_all_languages', $categories, $args );
	    }

	    if ( $this->is_aggregate_translated_articles_enabled() ) {
		    $categories = apply_filters( 'elevio_aggregate_translated_categories', $categories, $args['taxonomy'] );
	    }

        return $categories;
    }

    public function syncTopics($query = false, $wp_posts = false)
    {
        global $post, $wp_query;

        // We first force the post type to be our custom type...
        $_GET['post_type'] = Elevio::get_instance()->get_post_taxonomy();

        // Then, if a specific category is being requested, we manipulate the
        // parameter into a taxonomy request. 'cat' only works with normal
        // categories, not custom taxonomies.
        $tax_query = [];
        if (isset($_GET['cat'])) {
            $tax_query = [
                [
                    'taxonomy' => Elevio::get_instance()->get_category_taxonomy(),
                    'field'    => 'term_id',
                    'terms'    => $_GET['cat'],
                ],
            ];

            // We get rid of the 'cat' parameter too.
            unset($_GET['cat']);
        }

        // Allow the running of some extra filters on the retrieved topics
        $tax_query = apply_filters('elevio_posts_tax_query', $tax_query);
        $_GET['tax_query'] = $tax_query;
	    $filters = $_GET;

	    if ( $this->is_multilanguage_allowed() ) {
	        $filters = apply_filters( 'elevio_add_article_filters', $filters);
	    }

        query_posts(http_build_query($filters));

        $output = [];
        while (have_posts()) {
            the_post();
            if ($wp_posts) {
                $new_post = $post;
            } else {
                $new_post = new Elevio_Sync_Post($post);
            }

	        if ( $this->is_multilanguage_allowed() ) {
		        $new_post = apply_filters( 'elevio_append_language_id_to_article', $new_post, $post->ID );
	        }

	        $output[] = $new_post;
        }

	    if ( $this->is_aggregate_translated_articles_enabled() ) {
		    $output = apply_filters( 'elevio_aggregate_translated_articles', $output );
	    }

        return array_values($output);
    }

    protected function set_posts_query($query = false)
    {
        global $json_api, $wp_query;

        if (! $query) {
            $query = [];
        }

        $query = array_merge($query, $wp_query->query);

        if ($json_api->query->page) {
            $query['paged'] = $json_api->query->page;
        }

        if ($json_api->query->count) {
            $query['posts_per_page'] = $json_api->query->count;
        }

        if ($json_api->query->post_type) {
            $query['post_type'] = $json_api->query->post_type;
        }

        $query = apply_filters('json_api_query_args', $query);
        if (! empty($query)) {
            query_posts($query);
            do_action('json_api_query', $wp_query);
        }
    }

	private function is_multilanguage_allowed() {
		return Elevio::get_instance()->multi_language_is_enabled();
	}


	private function is_aggregate_translated_articles_enabled() {
		return boolval($this->is_multilanguage_allowed() && Elevio::get_instance()->aggregate_translated_articles());
	}
}
