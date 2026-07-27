<?php
/**
 * Core Module - Home Controller
 * Handles home page and base application routes
 */

namespace Modules\Core\Controllers;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class HomeController
{
    /**
     * Home page
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'OmniCMS - صفحه اصلی',
            'description' => 'سیستم مدیریت محتوای چندمنظوره',
            'modules' => ['Blog', 'Forum', 'Shop']
        ];
        
        return new Response($this->renderView('home.index', $data));
    }
    
    /**
     * About page
     */
    public function about(Request $request)
    {
        $data = [
            'title' => 'درباره ما',
            'version' => '1.0.0'
        ];
        
        return new Response($this->renderView('home.about', $data));
    }
    
    /**
     * Contact page
     */
    public function contact(Request $request)
    {
        $data = [
            'title' => 'تماس با ما',
            'success' => null,
            'errors' => []
        ];
        
        return new Response($this->renderView('home.contact', $data));
    }
    
    /**
     * Submit contact form
     */
    public function submitContact(Request $request)
    {
        // Validate input
        $errors = [];
        
        if (!$request->input('name')) {
            $errors['name'] = 'نام الزامی است';
        }
        
        if (!$request->input('email') || !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'ایمیل معتبر الزامی است';
        }
        
        if (!$request->input('message') || strlen($request->input('message')) < 10) {
            $errors['message'] = 'پیام باید حداقل ۱۰ کاراکتر باشد';
        }
        
        if (!empty($errors)) {
            $data = [
                'title' => 'تماس با ما',
                'success' => null,
                'errors' => $errors,
                'old' => $request->all()
            ];
            
            return new Response($this->renderView('home.contact', $data), 422);
        }
        
        // In a real application, you would send email or save to database here
        
        $data = [
            'title' => 'تماس با ما',
            'success' => 'پیام شما با موفقیت ارسال شد. متشکریم!',
            'errors' => []
        ];
        
        return new Response($this->renderView('home.contact', $data));
    }
    
    /**
     * Render view helper
     */
    private function renderView($view, $data = [])
    {
        $viewPath = MODULES_PATH . '/Core/Views/' . str_replace('.', '/', $view) . '.blade.php';
        
        if (!file_exists($viewPath)) {
            // Fallback to app views
            $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.blade.php';
        }
        
        if (!file_exists($viewPath)) {
            return '<h1>View not found: ' . e($view) . '</h1>';
        }
        
        extract($data);
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
}
