<?php

namespace Modules\Core\Controllers\Admin;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Log\Logger;

class SettingsController
{
    public function index(Request $request)
    {
        Logger::info('Admin settings page accessed');
        
        $data = [
            'title' => 'تنظیمات سیستم',
            'settings' => [
                'site_name' => 'سیستم مدیریت محتوا',
                'site_url' => 'http://localhost:6500',
                'admin_email' => 'admin@example.com',
                'language' => 'fa',
                'timezone' => 'Asia/Tehran',
                'maintenance_mode' => false
            ]
        ];
        
        return view('admin.settings.index', $data);
    }

    public function update(Request $request)
    {
        Logger::info('Settings updated', $request->all());
        return redirect('/admin/settings')->with('success', 'تنظیمات با موفقیت ذخیره شد');
    }
}
