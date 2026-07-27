<?php
/**
 * Forum Controller
 */

namespace Modules\Forum\Controllers;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Http\ViewRenderer;

class ForumController
{
    protected $view;

    public function __construct()
    {
        $this->view = new ViewRenderer();
    }

    public function index(Request $request)
    {
        return new Response($this->view->render('forum.index', ['title' => 'Forum']));
    }

    public function showCategory(Request $request, $id)
    {
        return new Response($this->view->render('forum.category', ['id' => $id, 'title' => 'Category']));
    }

    public function showTopic(Request $request, $id)
    {
        return new Response($this->view->render('forum.topic', ['id' => $id, 'title' => 'Topic']));
    }

    public function createTopic(Request $request)
    {
        return new Response('Create Topic', 200);
    }

    public function storeReply(Request $request)
    {
        return new Response('Reply Stored', 200);
    }
}
