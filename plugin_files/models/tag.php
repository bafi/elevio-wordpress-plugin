<?php

class Elevio_Sync_Tag
{
    public $id;

    // Integer
    public $slug;

    // String
    public $title;

    // String
    public $description; // String

    public function Elevio_Sync_Tag($wp_tag = null)
    {
        if ($wp_tag) {
            $this->import_wp_object($wp_tag);
        }
    }

    public function import_wp_object($wp_tag)
    {
        $this->id = (int) $wp_tag->term_id;
        $this->slug = $wp_tag->slug;
        $this->title = $wp_tag->name;
    }
}
