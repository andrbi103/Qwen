<?php

namespace Modules\Core\Controllers\Admin;

use App\Core\Http\Request;
use App\Core\Logging\Logger;

class DashboardController
{
    public function index(Request $request)
    {
        Logger::info('Admin dashboard accessed');
        
        $data = [
            'title' => 'پنل مدیریت',
            'stats' => [
                'modules' => 5,
                'plugins' => 12,
                'users' => 150,
                'settings' => 25
            ]
        ];
        
        return view('admin.dashboard.index', $data);
    }
}
