<?php

namespace NFWP\PageTemplate;

use Philo\Blade\Blade;

/*
Plugin Name: Page Template Plugin : 'Good To Be Bad'
Plugin URI: http://hbt.io/
Version: 1.0.1
Author: Harri Bell-Thomas
Author URI: http://hbt.io/
 */

class NFPageTemplate
{

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'get_instance'));
        $views       = $this->view;
        $cache       = $this->cache;
        $this->blade = new Blade($views, $cache);
    }

    public function get_instance()
    {
        $this->templates = array();

        // Add a filter to the attributes metabox to inject template into the cache.
        add_filter('page_attributes_dropdown_pages_args', [$this, 'register_project_templates']);

        // Add a filter to the save post to inject out template into the page cache
        add_filter('wp_insert_post_data', [$this, 'register_project_templates']);

        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter('template_include', [$this, 'view_project_template']);

        // Add your templates to this array.
        $this->templates = [__FILE__=> $this->name];
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     *
     */

    public function register_project_templates($atts)
    {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template($template)
    {

        global $post;

        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
            return $template;
        }

        $file = get_post_meta($post->ID, '_wp_page_template', true);

        return $this->view();
    }

    public function render($view, $data = [])
    {
        if (is_array($data)) {
            echo $this->blade->view()->make($view, $data)->render();
        } else {
            throw new Exception("data pass into view must be an array", 0);
        }
    }

}
