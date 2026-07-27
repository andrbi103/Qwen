<?php

namespace Modules\Core\Controllers\Admin;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Log\Logger;

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
