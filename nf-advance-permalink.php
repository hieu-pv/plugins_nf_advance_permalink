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

class NFAdvancePermalink
{
    public function __construct()
    {
        new Admin;
        new Database(__FILE__);
        add_action('init', [$this, 'custom_rewrite_basic']);
        add_action('admin_enqueue_scripts', [$this, 'load_scripts']);
        add_filter('term_link', [$this, 'term_link_filter'], 10, 3);
    }
    public function custom_rewrite_basic()
    {
        RewriteRuler::render();
    }
    public function load_scripts()
    {
        wp_enqueue_style('nf-advance-permalink-select2', plugins_url('/nf-advance-permalink/bower_components/select2/dist/css/select2.min.css'));
        wp_enqueue_script('nf-advance-permalink-select2', plugins_url('/nf-advance-permalink/bower_components/select2/dist/js/select2.full.min.js'), array('jquery'));
        wp_enqueue_script('nf-advance-permalink-custom', plugins_url('/nf-advance-permalink/assets/js/app.js'), array('nf-advance-permalink-select2'));
    }
    public function term_link_filter($url, $term, $taxonomy)
    {
        $rules = Rule::all();
        $t     = $rules->search(function ($item) use ($term) {
            return $item->entity_id == $term->term_id;
        });
        if (isset($t) && !is_bool($t) && $t != false) {
            return get_site_url(null, $rules[$t]->regex);
        } else {
            return $url;
        }
    }

}
new NFAdvancePermalink;
