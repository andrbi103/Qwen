<?php

namespace Modules\Core\Controllers\Admin;

use App\Core\Http\Request;
use App\Core\Logging\Logger;

class ModulesController
{
    public function index(Request $request)
    {
        Logger::info('Admin modules page accessed');
        
        $data = [
            'title' => 'مدیریت ماژول‌ها',
            'modules' => [
                ['name' => 'Core', 'version' => '1.0.0', 'status' => 'active', 'description' => 'ماژول اصلی سیستم'],
                ['name' => 'Blog', 'version' => '1.2.0', 'status' => 'active', 'description' => 'ماژول وبلاگ'],
                ['name' => 'Shop', 'version' => '0.9.0', 'status' => 'inactive', 'description' => 'ماژول فروشگاه'],
                ['name' => 'Forum', 'version' => '1.0.0', 'status' => 'inactive', 'description' => 'ماژول انجمن']
            ]
        ];
        
        return view('admin.modules.index', $data);
    }

    public function activate($moduleName)
    {
        Logger::info("Module activated: $moduleName");
        return redirect('/admin/modules');
    }

    public function deactivate($moduleName)
    {
        Logger::info("Module deactivated: $moduleName");
        return redirect('/admin/modules');
    }
}
