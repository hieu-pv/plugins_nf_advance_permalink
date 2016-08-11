<?php

namespace NFWP\Admin;

use NFWP\Helpers\View;
use NFWP\Support\AdminMenuOption;

class AdminMenu extends View
{
    private $blade;
    public function __construct()
    {
        parent::__construct();
        add_action('admin_menu', [$this, 'pluginMenu']);
    }
    public function pluginMenu()
    {
        foreach ($this->admin_menus as $value) {
            $admin_menu = new AdminMenuOption($value);
            add_menu_page($admin_menu->page_title, $admin_menu->menu_title, $admin_menu->capability, $admin_menu->menu_slug, [$this, $admin_menu->function], $admin_menu->icon_url, $admin_menu->position);
        }
    }
}
