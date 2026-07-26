<?php
/**
 * Main Application Controller
 * Handles core application logic
 */

namespace OmniCMS\App\Controllers;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\DependencyInjection\Container;

class HomeController extends Controller
{
    protected $container;
    
    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }
    
    /**
     * Home page
     */
    public function index(Request $request)
    {
        $data = [
            'title' => __('messages.welcome'),
            'description' => __('messages.welcome_description'),
            'modules' => $GLOBALS['active_modules'] ?? [],
            'plugins' => $GLOBALS['active_plugins'] ?? []
        ];
        
        return $this->view('home.index', $data);
    }
    
    /**
     * About page
     */
    public function about(Request $request)
    {
        $data = [
            'title' => __('messages.about'),
            'version' => VERSION
        ];
        
        return $this->view('home.about', $data);
    }
    
    /**
     * Contact page
     */
    public function contact(Request $request)
    {
        if ($request->isPost()) {
            // Handle contact form submission
            $name = $request->post('name');
            $email = $request->post('email');
            $message = $request->post('message');
            
            // Validate
            $validator = $this->container->get('validator');
            if ($validator->validate([
                'name' => ['required', 'min:3'],
                'email' => ['required', 'email'],
                'message' => ['required', 'min:10']
            ])) {
                // Send email or save to database
                // ...
                
                return redirect()->back()->with('success', __('messages.contact_sent'));
            } else {
                return redirect()->back()->withErrors($validator->errors());
            }
        }
        
        return $this->view('home.contact');
    }
}
