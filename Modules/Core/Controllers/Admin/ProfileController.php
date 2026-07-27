<?php

namespace Modules\Core\Controllers\Admin;

use App\Core\Http\Request;
use App\Core\Logging\Logger;

class ProfileController
{
    public function index(Request $request)
    {
        Logger::info('Admin profile page accessed');
        
        $data = [
            'title' => 'پروفایل کاربری',
            'user' => [
                'name' => 'مدیر سیستم',
                'email' => 'admin@example.com',
                'role' => 'Administrator',
                'created_at' => '2024-01-01'
            ]
        ];
        
        return view('admin.profile.index', $data);
    }

    public function update(Request $request)
    {
        Logger::info('Profile updated', $request->all());
        return redirect('/admin/profile')->with('success', 'پروفایل با موفقیت به‌روزرسانی شد');
    }
}
