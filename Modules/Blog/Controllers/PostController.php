<?php
/**
 * Blog Module - Post Controller
 * Handles blog post operations
 */

namespace Modules\Blog\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Logging\Logger;

class BlogPostController
{
    public function index(Request $request)
    {
        Logger::info('Blog posts page accessed');
        
        $data = [
            'title' => 'وبلاگ',
            'posts' => [
                ['title' => 'اولین مطلب وبلاگ', 'slug' => 'first-post', 'excerpt' => 'این اولین مطلب وبلاگ است'],
                ['title' => 'دومین مطلب وبلاگ', 'slug' => 'second-post', 'excerpt' => 'این دومین مطلب وبلاگ است']
            ]
        ];
        
        return new Response(view('blog.posts.index', $data));
    }
    
    public function show(Request $request, $slug)
    {
        Logger::info("Blog post viewed: $slug");
        
        $data = [
            'title' => 'مطلب وبلاگ',
            'post' => [
                'title' => 'عنوان مطلب',
                'slug' => $slug,
                'content' => 'محتوای کامل مطلب وبلاگ در اینجا قرار می‌گیرد.'
            ]
        ];
        
        return new Response(view('blog.posts.show', $data));
    }
    
    public function create(Request $request)
    {
        return new Response(view('blog.posts.create', ['title' => 'ایجاد مطلب جدید']));
    }
    
    public function store(Request $request)
    {
        Logger::info('New blog post created');
        return redirect('/blog')->with('success', 'مطلب با موفقیت ایجاد شد');
    }
    
    public function edit(Request $request, $id)
    {
        return new Response(view('blog.posts.edit', ['title' => 'ویرایش مطلب', 'id' => $id]));
    }
    
    public function update(Request $request, $id)
    {
        Logger::info("Blog post updated: $id");
        return redirect("/blog/post/$id")->with('success', 'مطلب با موفقیت به‌روزرسانی شد');
    }
    
    public function destroy(Request $request, $id)
    {
        Logger::info("Blog post deleted: $id");
        return redirect('/blog')->with('success', 'مطلب حذف شد');
    }
}
