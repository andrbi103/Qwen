<?php
/**
 * Auth Controller
 * Handles user authentication (login, logout, register)
 */

namespace Modules\Core\Controllers\Auth;

use OmniCMS\Core\Http\Controller;
use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use Modules\Core\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin(): Response
    {
        if (is_logged_in()) {
            return redirect('/admin/dashboard');
        }
        
        return $this->view('auth.login');
    }

    /**
     * Process login
     */
    public function login(Request $request): Response
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::findByUsername($username);

        if (!$user) {
            return $this->view('auth.login', [
                'error' => 'Invalid username or password'
            ])->setStatusCode(401);
        }

        if (!$user->verifyPassword($password)) {
            return $this->view('auth.login', [
                'error' => 'Invalid username or password'
            ])->setStatusCode(401);
        }

        if (!$user->isActive()) {
            return $this->view('auth.login', [
                'error' => 'Your account is not active'
            ])->setStatusCode(403);
        }

        // Set session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['logged_in'] = true;

        // Update last login
        $user->updateLastLogin();

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/profile');
    }

    /**
     * Show registration form
     */
    public function showRegister(): Response
    {
        if (is_logged_in()) {
            return redirect('/');
        }
        
        return $this->view('auth.register');
    }

    /**
     * Process registration
     */
    public function register(Request $request): Response
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50'
        ]);

        // Check if username exists
        if (User::findByUsername($request->input('username'))) {
            return $this->view('auth.register', [
                'error' => 'Username already exists'
            ])->setStatusCode(400);
        }

        // Check if email exists
        if (User::findByEmail($request->input('email'))) {
            return $this->view('auth.register', [
                'error' => 'Email already exists'
            ])->setStatusCode(400);
        }

        // Create user
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = $request->input('password'); // Will be hashed by mutator
        $user->first_name = $request->input('first_name', '');
        $user->last_name = $request->input('last_name', '');
        $user->role = User::ROLE_USER;
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        // Auto login
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['logged_in'] = true;

        return redirect('/')->with('success', 'Registration successful! Welcome aboard.');
    }

    /**
     * Logout user
     */
    public function logout(): Response
    {
        // Clear session
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['user_role']);
        unset($_SESSION['logged_in']);

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
