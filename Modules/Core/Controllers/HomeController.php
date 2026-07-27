<?php
/**
 * Core Module - Home Controller
 * Handles home page and base application routes
 */

namespace Modules\Core\Controllers;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Log\Logger;

class HomeController
{
    /**
     * Home page
     */
    public function index(Request $request)
    {
        Logger::info('Home page accessed');
        
        $data = [
            'title' => 'OmniCMS - صفحه اصلی',
            'description' => 'سیستم مدیریت محتوای چندمنظوره',
            'modules' => ['Blog', 'Forum', 'Shop']
        ];
        
        $content = view('front.home.index', $data);
        return new Response($content);
    }
    
    /**
     * About page
     */
    public function about(Request $request)
    {
        Logger::info('About page accessed');
        
        $data = [
            'title' => 'درباره ما',
            'version' => '1.0.0'
        ];
        
        $content = view('front.home.about', $data);
        return new Response($content);
    }
    
    /**
     * Contact page
     */
    public function contact(Request $request)
    {
        Logger::info('Contact page accessed');
        
        $data = [
            'title' => 'تماس با ما',
            'success' => null,
            'errors' => []
        ];
        
        $content = view('front.home.contact', $data);
        return new Response($content);
    }
    
    /**
     * Submit contact form
     */
    public function submitContact(Request $request)
    {
        Logger::info('Contact form submitted');
        
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
            
            $content = view('front.home.contact', $data);
            return new Response($content, 422);
        }
        
        // In a real application, you would send email or save to database here
        Logger::info('Contact form submitted successfully', $request->all());
        
        $data = [
            'title' => 'تماس با ما',
            'success' => 'پیام شما با موفقیت ارسال شد. متشکریم!',
            'errors' => []
        ];
        
        $content = view('front.home.contact', $data);
        return new Response($content);
    }
}
