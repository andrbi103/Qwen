<?php
/**
 * Shop Admin Controller
 */

namespace Modules\Shop\Controllers\Admin;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Http\ViewRenderer;

class ShopAdminController
{
    protected $view;

    public function __construct()
    {
        $this->view = new ViewRenderer();
    }

    public function index(Request $request)
    {
        return new Response($this->view->render('shop.admin.index', ['title' => 'Shop Admin']));
    }

    public function products(Request $request)
    {
        return new Response($this->view->render('shop.admin.products', ['title' => 'Shop Products']));
    }

    public function orders(Request $request)
    {
        return new Response($this->view->render('shop.admin.orders', ['title' => 'Shop Orders']));
    }

    public function categories(Request $request)
    {
        return new Response($this->view->render('shop.admin.categories', ['title' => 'Shop Categories']));
    }

    public function settings(Request $request)
    {
        return new Response($this->view->render('shop.admin.settings', ['title' => 'Shop Settings']));
    }

    public function updateSettings(Request $request)
    {
        return new Response('Settings Updated', 200);
    }
}
