<?php

namespace NFWP\Support;

class AdminMenuOption
{
    /**
     * The text to be displayed in the title tags of the page when the menu is selected
     *
     * @var string
     */
    public $page_title;

    /**
     * The on-screen name text for the menu
     *
     * @var string
     */
    public $menu_title;

    /**
     * The capability required for this menu to be displayed to the user. User levels are deprecated and should not be used here!
     *
     * @var string
     */

    public $capability;
    /**
     * The slug name to refer to this menu by (should be unique for this menu). Prior to Version 3.0 this was called the file (or handle) parameter. If the function parameter is omitted, the menu_slug should be the PHP file that handles the display of the menu page content.
     *
     * @var string
     */
    public $menu_slug;

    /**
     * The function that displays the page content for the menu page.
     *
     * @var string
     */
    public $function;

    /**
     * The icon for this menu.
     *
     * @var string
     */
    public $icon_url;

    /**
     * The position in the menu order this menu should appear. By default, if this parameter is omitted, the menu will appear at the bottom of the menu structure. The higher the number, the lower its position in the menu.
     *
     * @var int
     */
    public $position;

    /**
     * bind params
     */
    public function __construct($param)
    {
        try {
            $this->page_title = $param['page_title'];
            $this->menu_title = $param['menu_title'];
            $this->capability = $param['capability'];
            $this->menu_slug  = $param['menu_slug'];
            $this->function   = $param['function'];
            $this->icon_url   = isset($param['icon_url']) ? $param['icon_url'] : null;
            $this->position   = isset($param['position']) ? (int) $param['position'] : null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0);
        }
    }
}
