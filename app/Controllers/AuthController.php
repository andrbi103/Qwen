<?php
/**
 * Authentication Controller
 * Handles user login, registration, and logout
 */

namespace OmniCMS\App\Controllers;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\DependencyInjection\Container;
use OmniCMS\Core\Security\Csrf;
use OmniCMS\Core\Database\Model;

class AuthController extends Controller
{
    protected $container;
    protected $userModel;
    
    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->userModel = new Model('users');
    }
    
    /**
     * Show login form
     */
    public function showLogin(Request $request)
    {
        if (is_logged_in()) {
            return redirect('/dashboard');
        }
        
        $data = [
            'title' => __('messages.login'),
            'csrf_token' => Csrf::generateToken()
        ];
        
        return $this->view('auth.login', $data);
    }
    
    /**
     * Process login
     */
    public function login(Request $request)
    {
        if (!$request->isPost()) {
            return redirect('/login');
        }
        
        // Verify CSRF token
        if (!Csrf::validateToken($request->post('csrf_token'))) {
            return redirect()->back()->with('error', __('messages.invalid_csrf'));
        }
        
        $email = $request->post('email');
        $password = $request->post('password');
        $remember = $request->post('remember');
        
        // Validate input
        $validator = $this->container->get('validator');
        $validator->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);
        
        if (!empty($validator->errors())) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        // Find user by email
        $user = $this->userModel->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Check if user is active
            if (!$user['is_active']) {
                return redirect()->back()->with('error', __('messages.account_inactive'));
            }
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            // Remember me functionality
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                // Save token to database for persistent login
                // setcookie('remember_token', $token, time() + (86400 * 30), '/');
            }
            
            // Log successful login
            event('user.logged_in', ['user_id' => $user['id']]);
            
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'seller':
                    return redirect('/seller/dashboard');
                case 'customer':
                    return redirect('/customer/dashboard');
                default:
                    return redirect('/dashboard');
            }
        } else {
            return redirect()->back()->with('error', __('messages.invalid_credentials'))->withInput();
        }
    }
    
    /**
     * Show registration form
     */
    public function showRegister(Request $request)
    {
        if (is_logged_in()) {
            return redirect('/dashboard');
        }
        
        $data = [
            'title' => __('messages.register'),
            'csrf_token' => Csrf::generateToken()
        ];
        
        return $this->view('auth.register', $data);
    }
    
    /**
     * Process registration
     */
    public function register(Request $request)
    {
        if (!$request->isPost()) {
            return redirect('/register');
        }
        
        // Verify CSRF token
        if (!Csrf::validateToken($request->post('csrf_token'))) {
            return redirect()->back()->with('error', __('messages.invalid_csrf'));
        }
        
        $username = $request->post('username');
        $email = $request->post('email');
        $password = $request->post('password');
        $password_confirm = $request->post('password_confirm');
        
        // Validate input
        $validator = $this->container->get('validator');
        $validator->validate([
            'username' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);
        
        if (!empty($validator->errors())) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        // Check if passwords match
        if ($password !== $password_confirm) {
            return redirect()->back()->with('error', __('messages.password_mismatch'))->withInput();
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user
        $userId = $this->userModel->insert([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user',
            'is_active' => 1
        ]);
        
        if ($userId) {
            // Log successful registration
            event('user.registered', ['user_id' => $userId]);
            
            return redirect('/login')->with('success', __('messages.registration_success'));
        } else {
            return redirect()->back()->with('error', __('messages.registration_failed'))->withInput();
        }
    }
    
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        // Log logout event
        if ($userId) {
            event('user.logged_out', ['user_id' => $userId]);
        }
        
        // Destroy session
        session_destroy();
        
        return redirect('/')->with('success', __('messages.logout_success'));
    }
    
    /**
     * Show forgot password form
     */
    public function showForgotPassword(Request $request)
    {
        $data = [
            'title' => __('messages.forgot_password'),
            'csrf_token' => Csrf::generateToken()
        ];
        
        return $this->view('auth.forgot-password', $data);
    }
    
    /**
     * Process forgot password
     */
    public function forgotPassword(Request $request)
    {
        if (!$request->isPost()) {
            return redirect('/forgot-password');
        }
        
        $email = $request->post('email');
        
        // Find user
        $user = $this->userModel->where('email', $email)->first();
        
        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save token to database
            // Send email with reset link
            // ...
            
            return redirect('/login')->with('success', __('messages.reset_link_sent'));
        } else {
            // Don't reveal if email exists or not (security)
            return redirect('/forgot-password')->with('success', __('messages.reset_link_sent'));
        }
    }
}
