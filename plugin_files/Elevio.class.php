<?php

class Elevio
{
    // singleton pattern
    protected static $instance;

    /**
     * Absolute path to plugin files.
     */
    protected $plugin_url = null;

    /**
     * Elevio parameters.
     */
    protected $login = null;

    protected $account_id = null;

    protected $secret_id = null;

    protected $category_taxonomy = null;

    protected $post_taxonomy = null;

    protected $tag_taxonomy = null;

    protected $version = null;

    /**
     * Remembers if Elevio account id is set.
     */
    protected static $elevio_installed = false;

    /**
     * Starts the plugin.
     */
    protected function __construct()
    {
        add_action('wp_footer', [$this, 'tracking_code']);
    }

    public static function get_instance()
    {
        if (! isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    /**
     * Returns plugin files absolute path.
     *
     * @return string
     */
    public function get_plugin_url()
    {
        if (is_null($this->plugin_url)) {
            $this->plugin_url = WP_PLUGIN_URL.'/elevio/plugin_files';
        }

        return $this->plugin_url;
    }

    /**
     * Returns true if Elevio account id set properly,
     * false otherwise.
     *
     * @return bool
     */
    public function is_enabled()
    {
        return get_option('elevio_is_enabled', true);
    }

    /**
     * Returns true if Elevio account id set properly,
     * false otherwise.
     *
     * @return bool
     */
    public function is_installed()
    {
        return $this->get_account_id() !== false && $this->get_secret_id() !== false;
    }

    /**
     * Returns Elevio account id.
     *
     * @return int
     */
    public function get_account_id()
    {
        if (is_null($this->account_id)) {
            $this->account_id = get_option('elevio_account_id');
        }

        return $this->account_id;
    }

    /**
     * Returns Elevio account id.
     *
     * @return int
     */
    public function get_category_taxonomy()
    {
        if (is_null($this->category_taxonomy)) {
            $this->category_taxonomy = get_option('elevio_category_taxonomy', 'category');
        }

        return $this->category_taxonomy;
    }

    /**
     * Returns Elevio account id.
     *
     * @return int
     */
    public function get_post_taxonomy()
    {
        if (is_null($this->post_taxonomy)) {
            $this->post_taxonomy = get_option('elevio_post_taxonomy', 'post');
        }

        return $this->post_taxonomy;
    }

    /**
     * Returns Elevio tag taxonomy.
     *
     * @return int
     */
    public function get_tag_taxonomy()
    {
        if (is_null($this->tag_taxonomy)) {
            $this->tag_taxonomy = get_option('elevio_tag_taxonomy', 'post_tag');
        }

        return $this->tag_taxonomy;
    }

    /**
     * Returns Elevio secret_id.
     *
     * @return int
     */
    public function get_secret_id()
    {
        if (is_null($this->secret_id)) {
            $this->secret_id = get_option('elevio_secret_id');
        }

        return $this->secret_id;
    }

    /**
     * Returns version they want to load
     * This is a new setting, so needs to backfill.
     *
     * @return int
     */
    public function get_version()
    {
        if (is_null($this->version)) {
            $this->version = (int) get_option('elevio_version');

            if (! ($this->version)) {
                if (! ($this->get_account_id())) {
                    // they are new, load v4
                    $this->version = 4;
                } else {
                    // they were already using... be safe and load v3
                    $this->version = 3;
                }
            }
        }

        return $this->version;
    }

    /**
     * Injects tracking code.
     */
    public function tracking_code()
    {
        if ($this->is_enabled()) {
            $this->get_helper('TrackingCode');
        }
    }

    /**
     * Echoes given helper.
     */
    public static function get_helper($class, $echo = true)
    {
        $class .= 'Helper';

        if (class_exists($class) == false) {
            $path = dirname(__FILE__).'/helpers/'.$class.'.class.php';
            if (file_exists($path) !== true) {
                return false;
            }

            require_once $path;
        }

        $c = new $class;

        if ($echo) {
            echo $c->render();

            return true;
        } else {
            return $c->render();
        }
    }
}
