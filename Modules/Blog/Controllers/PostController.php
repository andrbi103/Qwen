<?php
/**
 * Blog Module - Post Controller
 * Handles blog post operations
 */

class BlogPostController extends Controller {
    
    public function index() {
        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        return view('blog.posts.index', compact('posts'));
    }
    
    public function show($slug) {
        $post = BlogPost::with(['author', 'category', 'tags', 'comments.replies'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        $relatedPosts = BlogPost::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->limit(3)
            ->get();
        
        Event::dispatch('blog.post.viewed', ['post' => $post]);
        
        return view('blog.posts.show', compact('post', 'relatedPosts'));
    }
    
    public function create() {
        $this->authorize('create', BlogPost::class);
        
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        
        return view('blog.posts.create', compact('categories', 'tags'));
    }
    
    public function store(Request $request) {
        $this->authorize('create', BlogPost::class);
        
        $validated = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'content' => 'required',
            'category_id' => 'required|exists:blog_categories,id',
            'tags' => 'array',
            'status' => 'in:draft,scheduled,published'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $data = $validated->validated();
        $data['slug'] = $this->generateSlug($data['title']);
        $data['author_id'] = Auth::id();
        
        if ($data['status'] === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        $post = BlogPost::create($data);
        
        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        Event::dispatch('blog.post.created', ['post' => $post]);
        
        return redirect()->route('blog.posts.show', ['slug' => $post->slug])
            ->with('success', __('Blog post created successfully'));
    }
    
    public function edit($id) {
        $post = BlogPost::findOrFail($id);
        $this->authorize('update', $post);
        
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        
        return view('blog.posts.edit', compact('post', 'categories', 'tags'));
    }
    
    public function update(Request $request, $id) {
        $post = BlogPost::findOrFail($id);
        $this->authorize('update', $post);
        
        $validated = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'content' => 'required',
            'category_id' => 'required|exists:blog_categories,id',
            'tags' => 'array',
            'status' => 'in:draft,scheduled,published'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $data = $validated->validated();
        $data['slug'] = $this->generateSlug($data['title'], $post->id);
        
        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        $post->update($data);
        
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        Event::dispatch('blog.post.updated', ['post' => $post]);
        
        return redirect()->route('blog.posts.show', ['slug' => $post->slug])
            ->with('success', __('Blog post updated successfully'));
    }
    
    public function destroy($id) {
        $post = BlogPost::findOrFail($id);
        $this->authorize('delete', $post);
        
        $post->delete();
        
        Event::dispatch('blog.post.deleted', ['post' => $post]);
        
        return redirect()->route('blog.posts.index')
            ->with('success', __('Blog post deleted successfully'));
    }
    
    private function generateSlug($title, $excludeId = null) {
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
        $originalSlug = $slug;
        $count = 1;
        
        while (true) {
            $query = BlogPost::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }
}
