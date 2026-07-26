<?php
/**
 * Admin Dashboard Controller
 * Handles admin panel functionality
 */

namespace OmniCMS\App\Controllers\Admin;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\DependencyInjection\Container;
use OmniCMS\Core\Database\Model;

class DashboardController extends \OmniCMS\App\Controllers\Controller
{
    protected $container;
    protected $userModel;
    protected $postModel;
    
    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->userModel = new Model('users');
        $this->postModel = new Model('posts');
    }
    
    /**
     * Admin Dashboard
     */
    public function index(Request $request)
    {
        // Check admin permission
        if (!is_admin()) {
            return redirect('/')->with('error', __('messages.access_denied'));
        }
        
        // Get statistics
        $stats = [
            'total_users' => $this->userModel->count(),
            'total_posts' => $this->postModel->count(),
            'active_modules' => count($GLOBALS['active_modules'] ?? []),
            'active_plugins' => count($GLOBALS['active_plugins'] ?? [])
        ];
        
        // Get recent users
        $recentUsers = $this->userModel->orderBy('created_at', 'DESC')->limit(5)->get();
        
        // Get recent posts
        $recentPosts = $this->postModel->orderBy('created_at', 'DESC')->limit(5)->get();
        
        $data = [
            'title' => __('messages.admin_dashboard'),
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentPosts' => $recentPosts,
            'modules' => $GLOBALS['active_modules'] ?? [],
            'plugins' => $GLOBALS['active_plugins'] ?? []
        ];
        
        return $this->view('admin.dashboard.index', $data);
    }
    
    /**
     * System Settings
     */
    public function settings(Request $request)
    {
        if (!is_admin()) {
            return redirect('/')->with('error', __('messages.access_denied'));
        }
        
        if ($request->isPost()) {
            // Handle settings update
            $settings = $request->post('settings');
            
            // Save settings to database
            $settingsModel = new Model('settings');
            foreach ($settings as $key => $value) {
                $existing = $settingsModel->where('key', $key)->first();
                if ($existing) {
                    $settingsModel->where('id', $existing['id'])->update(['value' => $value]);
                } else {
                    $settingsModel->insert(['key' => $key, 'value' => $value]);
                }
            }
            
            event('settings.updated', ['settings' => $settings]);
            
            return redirect()->back()->with('success', __('messages.settings_saved'));
        }
        
        // Load current settings
        $settingsModel = new Model('settings');
        $settings = $settingsModel->all();
        $settingsData = [];
        foreach ($settings as $setting) {
            $settingsData[$setting['key']] = $setting['value'];
        }
        
        $data = [
            'title' => __('messages.system_settings'),
            'settings' => $settingsData
        ];
        
        return $this->view('admin.settings.index', $data);
    }
    
    /**
     * Module Management
     */
    public function modules(Request $request)
    {
        if (!is_admin()) {
            return redirect('/')->with('error', __('messages.access_denied'));
        }
        
        $modulesPath = MODULES_PATH;
        $allModules = [];
        
        if (is_dir($modulesPath)) {
            $dirs = scandir($modulesPath);
            foreach ($dirs as $dir) {
                if ($dir === '.' || $dir === '..') continue;
                
                $moduleInit = $modulesPath . DS . $dir . DS . '__init__.php';
                if (file_exists($moduleInit)) {
                    $moduleConfig = require_once $moduleInit;
                    $moduleConfig['folder'] = $dir;
                    $allModules[] = $moduleConfig;
                }
            }
        }
        
        $data = [
            'title' => __('messages.module_management'),
            'modules' => $allModules
        ];
        
        return $this->view('admin.modules.index', $data);
    }
    
    /**
     * Toggle Module
     */
    public function toggleModule(Request $request)
    {
        if (!is_admin()) {
            return response()->json(['error' => __('messages.access_denied')], 403);
        }
        
        $moduleName = $request->post('module');
        $action = $request->post('action'); // enable or disable
        
        $moduleInit = MODULES_PATH . DS . $moduleName . DS . '__init__.php';
        if (!file_exists($moduleInit)) {
            return response()->json(['error' => __('messages.module_not_found')], 404);
        }
        
        // In a real scenario, update database or config file
        // For now, just return success
        event('module.toggled', ['module' => $moduleName, 'action' => $action]);
        
        return response()->json([
            'success' => true,
            'message' => sprintf(__('messages.module_' . $action), $moduleName)
        ]);
    }
    
    /**
     * User Management
     */
    public function users(Request $request)
    {
        if (!is_admin()) {
            return redirect('/')->with('error', __('messages.access_denied'));
        }
        
        $page = $request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $users = $this->userModel->limit($perPage)->offset($offset)->get();
        $totalUsers = $this->userModel->count();
        
        $data = [
            'title' => __('messages.user_management'),
            'users' => $users,
            'pagination' => [
                'current' => $page,
                'total' => ceil($totalUsers / $perPage),
                'count' => $totalUsers
            ]
        ];
        
        return $this->view('admin.users.index', $data);
    }
    
    /**
     * System Health Check
     */
    public function health(Request $request)
    {
        if (!is_admin()) {
            return response()->json(['error' => __('messages.access_denied')], 403);
        }
        
        $health = [
            'database' => false,
            'cache' => false,
            'storage' => false,
            'modules' => 0,
            'plugins' => 0
        ];
        
        // Check database connection
        try {
            $db = \OmniCMS\Core\Database\Connection::getInstance();
            $db->query('SELECT 1');
            $health['database'] = true;
        } catch (\Exception $e) {
            $health['database'] = false;
        }
        
        // Check storage directories
        $health['storage'] = is_writable(STORAGE_PATH);
        
        // Count modules and plugins
        $health['modules'] = count($GLOBALS['active_modules'] ?? []);
        $health['plugins'] = count($GLOBALS['active_plugins'] ?? []);
        
        return response()->json($health);
    }
}
