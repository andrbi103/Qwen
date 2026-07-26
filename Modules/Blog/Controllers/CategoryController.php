<?php
/**
 * Blog Module - Category Controller
 * Handles blog category operations
 */

class BlogCategoryController extends Controller {
    
    public function index() {
        $categories = BlogCategory::withCount('posts')
            ->parent()
            ->orderBy('name')
            ->get();
        
        return view('blog.categories.index', compact('categories'));
    }
    
    public function show($slug) {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        
        $posts = BlogPost::where('category_id', $category->id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        return view('blog.categories.show', compact('category', 'posts'));
    }
    
    public function create() {
        $this->authorize('create', BlogCategory::class);
        
        $categories = BlogCategory::all();
        
        return view('blog.categories.create', compact('categories'));
    }
    
    public function store(Request $request) {
        $this->authorize('create', BlogCategory::class);
        
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'slug' => 'required|unique:blog_categories,slug',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:blog_categories,id'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $category = BlogCategory::create($validated->validated());
        
        Event::dispatch('blog.category.created', ['category' => $category]);
        
        return redirect()->route('blog.categories.index')
            ->with('success', __('Category created successfully'));
    }
    
    public function edit($id) {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('update', $category);
        
        $categories = BlogCategory::all();
        
        return view('blog.categories.edit', compact('category', 'categories'));
    }
    
    public function update(Request $request, $id) {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('update', $category);
        
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'slug' => 'required|unique:blog_categories,slug,' . $id,
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:blog_categories,id|not_in:' . $id
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $category->update($validated->validated());
        
        Event::dispatch('blog.category.updated', ['category' => $category]);
        
        return redirect()->route('blog.categories.index')
            ->with('success', __('Category updated successfully'));
    }
    
    public function destroy($id) {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('delete', $category);
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', __('Cannot delete category with subcategories'));
        }
        
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return redirect()->back()
                ->with('error', __('Cannot delete category with posts'));
        }
        
        $category->delete();
        
        Event::dispatch('blog.category.deleted', ['category' => $category]);
        
        return redirect()->route('blog.categories.index')
            ->with('success', __('Category deleted successfully'));
    }
}
