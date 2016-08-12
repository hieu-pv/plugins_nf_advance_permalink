<?php

use App\Admin;
use App\Database;
use App\Models\Rule;
use App\RewriteRuler;

/*
Plugin Name: NF Advance Permalink
Plugin URI:  http://codersvn.com/wordpress/plugins/nf-advance-permalink
Description: This plugin will help you easy to rewrite specific url. Aswesome for SEO.
Version:     1.0.0
Author:      Hieupv
Author URI:  http://codersvn.com/about
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: http://codersvn.com/wordpress/plugins/nf-advance-permalink
Text Domain: http://codersvn.com/wordpress/plugins/nf-advance-permalink
 */

require __DIR__ . '/vendor/autoload.php';

global $wpdb;

define('DB_TABLE_NAME', $wpdb->prefix . 'nf_advance_permalink');
define('NF_ADVANCE_PERMALINK_DEBUG', true);
define('NF_ADVANCE_PERMALINK_LOG_PATH', __DIR__ . '/resources/logs/log.log');

class NFAdvancePermalink
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    /**
     * @var array
     *
     * Aggregate of rewrite rules
     */
    private $rules;
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        if (is_admin()) {
            new Admin;
            new Database(__FILE__);
            add_action('admin_enqueue_scripts', [$this, 'load_scripts']);
        }
        $this->rules = Rule::all();
        add_action('init', [$this, 'custom_rewrite']);
        add_filter('term_link', [$this, 'term_link_filter'], 10, 3);
    }
    public function custom_rewrite()
    {
        $rewriteRules = RewriteRuler::getInstance();
        $rewriteRules->render();
    }
    public function load_scripts()
    {
        wp_enqueue_style('nf-advance-permalink-select2', plugins_url('/nf-advance-permalink/bower_components/select2/dist/css/select2.min.css'));
        wp_enqueue_script('nf-advance-permalink-select2', plugins_url('/nf-advance-permalink/bower_components/select2/dist/js/select2.full.min.js'), array('jquery'));
        wp_enqueue_script('nf-advance-permalink-custom', plugins_url('/nf-advance-permalink/assets/js/app.js'), array('nf-advance-permalink-select2'));
    }
    public function term_link_filter($url, $term, $taxonomy)
    {
        $rules = $this->rules;
        $t     = $rules->search(function ($item) use ($term) {
            return $item->entity_id == $term->term_id;
        });
        if (isset($t) && is_int($t)) {
            $url = get_site_url(null, $rules[$t]->regex);
            return substr($url, count($url) - 1) == '/' ? $url : $url . '/';
        } else {
            return $url;
        }
    }

}
NFAdvancePermalink::getInstance();
