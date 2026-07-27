<?php
/**
 * Blog Module - Category Controller
 */

namespace Modules\Blog\Controllers;

use OmniCMS\Core\Http\Request;

class BlogCategoryController {
    
    public function index(Request $request) {
        return new \OmniCMS\Core\Http\Response('<h1>Blog Categories</h1>');
    }
    
    public function show(Request $request, $slug) {
        return new \OmniCMS\Core\Http\Response('<h1>Category: ' . htmlspecialchars($slug) . '</h1>');
    }
    
    public function create(Request $request) {
        return new \OmniCMS\Core\Http\Response('<h1>Create Category</h1>');
    }
    
    public function store(Request $request) {
        return new \OmniCMS\Core\Http\Response('<h1>Category Created</h1>');
    }
    
    public function edit(Request $request, $id) {
        return new \OmniCMS\Core\Http\Response('<h1>Edit Category: ' . htmlspecialchars($id) . '</h1>');
    }
    
    public function update(Request $request, $id) {
        return new \OmniCMS\Core\Http\Response('<h1>Category Updated: ' . htmlspecialchars($id) . '</h1>');
    }
    
    public function destroy(Request $request, $id) {
        return new \OmniCMS\Core\Http\Response('<h1>Category Deleted: ' . htmlspecialchars($id) . '</h1>');
    }
}
