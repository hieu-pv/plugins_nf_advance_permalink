<?php

namespace App;

use App\Models\Rule;
use App\RewriteRuler;
use Illuminate\Support\Collection;
use NFWP\Admin\AdminMenu;
use Symfony\Component\HttpFoundation\Request;

class Admin extends AdminMenu
{
    /**
     * The direction of template folder
     *
     * @var string
     */
    protected $view;

    /**
     * The direction of template cache folder
     *
     * @var string
     */
    protected $cache;

    /**
     * The aggregate of admin menu item
     *
     * @var array
     */
    //fill your data here
    protected $admin_menus = [
        [
            'page_title' => 'NF Advance Permalink',
            'menu_title' => 'NF Advance Permalink',
            'capability' => 'manage_options',
            'menu_slug'  => 'nf-advance-permalink',
            'function'   => 'AdminPage',
            'icon_url'   => '',
            'position'   => '80',
        ],
    ];

    public function __construct()
    {
        $this->view  = __DIR__ . '/../resources/views';
        $this->cache = __DIR__ . '/../resources/cache';
        parent::__construct();
        add_action('admin_post_save_rule', [$this, 'saveRule']);
        add_action('admin_post_delete_rule', [$this, 'deleteRule']);
    }
    public function saveRule()
    {
        $request  = Request::createFromGlobals();
        $data     = $request->request->all();
        $taxonomy = get_term($data['entity_id']);
        if (Rule::where('regex', $data['regex'])->get()->isEmpty()) {
            $rule            = new Rule;
            $rule->type      = $taxonomy->taxonomy;
            $rule->entity_id = $data['entity_id'];
            $rule->regex     = $data['regex'];
            $rule->save();
            RewriteRuler::render();
            flush_rewrite_rules(true);
            wp_redirect(admin_url('admin.php?page=nf-advance-permalink&success=true'));
        } else {
            wp_redirect(admin_url('admin.php?page=nf-advance-permalink&error=true'));
        }
    }
    public function deleteRule()
    {
        $request = Request::createFromGlobals();
        if ($request->request->has('id')) {
            $rule = Rule::find($request->request->get('id'));
            $rule->delete();
            wp_redirect(admin_url('admin.php?page=nf-advance-permalink&success=true'));
        } else {
            wp_redirect(admin_url('admin.php?page=nf-advance-permalink&error=true'));
        }
    }
    public function AdminPage()
    {

        $taxonomies = new Collection(get_taxonomies(['hide_empty' => false]));
        $taxonomies = $taxonomies->filter(function ($tax) {
            return !in_array($tax, ['nav_menu', 'link_category', 'post_format', 'product_shipping_class', 'product_type']);
        });
        $terms = get_terms(['taxonomy' => $taxonomies->all()]);
        $rules = Rule::all();
        $rules = $rules->map(function ($item) {
            $item->taxonomy = get_term($item->entity_id);
            return $item;
        });
        $this->render('admin', ['terms' => $terms, 'rules' => $rules]);
    }
}
