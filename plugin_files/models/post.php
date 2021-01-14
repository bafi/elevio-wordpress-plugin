<?php

class Elevio_Sync_Post
{
    // Note:
    //   JSON_API_Post objects must be instantiated within The Loop.

    public $id;

    // Integer
    public $type;

    // String
    public $slug;

    // String
    public $url;

    // String
    public $status;

    // String ("draft", "published", or "pending")
    public $title;

    // String
    public $title_plain;

    // String
    public $content;

    // String (modified by read_more query var)
    public $excerpt;

    // String
    public $date;

    // String (modified by date_format query var)
    public $modified;

    // String (modified by date_format query var)
    public $categories;

    // Array of objects
    public $tags;

    // Array of objects
    public $author;

    // Object
    public $comments;

    // Array of objects
    public $attachments;

    // Array of objects
    public $comment_count;

    // Integer
    public $comment_status;

    // String ("open" or "closed")
    public $thumbnail;

    // String
  public $custom_fields;   // Object (included by using custom_fields query var)

  public function Elevio_Sync_Post($wp_post = null)
  {
      if (! empty($wp_post)) {
          $this->import_wp_object($wp_post);
      }
  }

    public function import_wp_object($wp_post)
    {
        global $json_api, $post;
        $this->id = (int) $wp_post->ID;
        setup_postdata($wp_post);
        $this->set_value('type', $wp_post->post_type);
        $this->set_value('title', get_the_title($this->id));
        $this->set_value('title_plain', strip_tags(@$this->title));
        $this->set_content_value();
        $this->set_categories_value();
        $this->set_tags_value();
    }

    public function set_value($key, $value)
    {
        $this->$key = $value;
    }

    public function set_content_value()
    {
        global $json_api;
        $content = get_post_field('post_content', $this->id);
        $content = apply_filters('elevio_post_content_before', $content); // This the users theme modify post content before...
        $content = do_shortcode($content);
        $content = apply_filters('elevio_post_content_after', $content); // ...and after processing.
        $this->content = $content;
    }

    public function set_categories_value()
    {
        $args['taxonomy'] = Elevio::get_instance()->get_category_taxonomy();
        global $json_api;
        $this->categories = [];
        if ($wp_categories = get_the_terms($this->id, $args['taxonomy'])) {
            foreach ($wp_categories as $wp_category) {
                $category = new Elevio_Sync_Category($wp_category);
                if ($category->id == 1 && $category->slug == 'uncategorized' && $args['taxonomy'] == 'category') {
                    // Skip the 'uncategorized' category
                    continue;
                }
                $this->categories[] = $category;
            }
        }
    }

    public function set_tags_value()
    {
        $args['tag_taxonomy'] = Elevio::get_instance()->get_tag_taxonomy();
        global $json_api;
        $this->tags = [];
        if ($wp_tags = get_the_terms($this->id, $args['tag_taxonomy'])) {
            foreach ($wp_tags as $wp_tag) {
                $this->tags[] = new Elevio_Sync_Tag($wp_tag);
            }
        }
    }
}
