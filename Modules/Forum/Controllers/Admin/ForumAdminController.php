<?php
/**
 * Forum Admin Controller
 */

namespace Modules\Forum\Controllers\Admin;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Http\ViewRenderer;

class ForumAdminController
{
    protected $view;

    public function __construct()
    {
        $this->view = new ViewRenderer();
    }

    public function index(Request $request)
    {
        return new Response($this->view->render('forum.admin.index', ['title' => 'Forum Admin']));
    }

    public function categories(Request $request)
    {
        return new Response($this->view->render('forum.admin.categories', ['title' => 'Forum Categories']));
    }

    public function moderators(Request $request)
    {
        return new Response($this->view->render('forum.admin.moderators', ['title' => 'Forum Moderators']));
    }

    public function settings(Request $request)
    {
        return new Response($this->view->render('forum.admin.settings', ['title' => 'Forum Settings']));
    }

    public function updateSettings(Request $request)
    {
        return new Response('Settings Updated', 200);
    }
}
