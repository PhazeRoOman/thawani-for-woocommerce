<?php

namespace Thawani;


class AdminDashboard
{


    public function __construct()
    {

        add_action('admin_menu', [$this, 'init_menu']);
    }

    public function init_menu()
    {
        add_menu_page(
            __('Thawani Gateway', "thawani"),
            __('Thawani Gateway', "thawani"),
            'manage_woocommerce',
            'thawani_gw',
            [$this, 'dashboard_template'],
            // THAWANI_GW_ICON,
            $this->menu_icon(),
            10
        );
    }

    public function dashboard_template()
    {
        include THAWANI_GW_DIR . 'template/index.php';
    }
    public function menu_icon()
    {
        $svg_icon = '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><defs><style>.cls-1 {fill: #6fbf49;}</style></defs><path class="cls-1" d="M11.52,9.93A1.71,1.71,0,1,1,9.8,8.23h0A1.72,1.72,0,0,1,11.52,9.93Z"/><path class="cls-1" d="M16,9.93a1.71,1.71,0,1,1-1.71-1.71h0A1.71,1.71,0,0,1,16,9.92Z"/><path class="cls-1" d="M13.59,5.89a1.71,1.71,0,1,1-1.71-1.71h0A1.72,1.72,0,0,1,13.59,5.89Z"/><path class="cls-1" d="M12,16.76A5.75,5.75,0,0,1,6.24,11H3.29a8.71,8.71,0,1,0,17.42.18V11H17.76A5.75,5.75,0,0,1,12,16.76Z"/></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg_icon);
    }
}
