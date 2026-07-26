<?php
/**
 * Forum Module - Topic Controller
 * Handles forum topic operations
 */

class ForumTopicController extends Controller {
    
    public function index() {
        $topics = ForumTopic::with(['forum', 'user', 'lastPost.user'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $stats = [
            'total_topics' => ForumTopic::count(),
            'total_posts' => ForumPost::count(),
            'total_users' => User::count(),
            'online_users' => 0 // Implement online tracking
        ];
        
        return view('forum.topics.index', compact('topics', 'stats'));
    }
    
    public function show($slug) {
        $topic = ForumTopic::with(['forum', 'user', 'posts.user', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        $topic->incrementViewCount();
        
        $posts = $topic->posts()->with('user', 'likes')
            ->orderBy('created_at', 'asc')
            ->paginate(15);
        
        $relatedTopics = ForumTopic::where('forum_id', $topic->forum_id)
            ->where('id', '!=', $topic->id)
            ->limit(5)
            ->get();
        
        Event::dispatch('forum.topic.viewed', ['topic' => $topic]);
        
        return view('forum.topics.show', compact('topic', 'posts', 'relatedTopics'));
    }
    
    public function create($forumId = null) {
        $this->authorize('create', ForumTopic::class);
        
        $forums = $forumId 
            ? [ForumForum::findOrFail($forumId)]
            : ForumForum::all();
        
        $tags = ForumTag::all();
        
        return view('forum.topics.create', compact('forums', 'tags'));
    }
    
    public function store(Request $request) {
        $this->authorize('create', ForumTopic::class);
        
        $validated = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'content' => 'required|min:10',
            'forum_id' => 'required|exists:forum_forums,id',
            'tags' => 'array'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $data = $validated->validated();
        $data['slug'] = $this->generateSlug($data['title']);
        $data['user_id'] = Auth::id();
        
        $topic = ForumTopic::create($data);
        
        // Create first post
        ForumPost::create([
            'topic_id' => $topic->id,
            'user_id' => Auth::id(),
            'content' => $data['content']
        ]);
        
        // Attach tags
        if (!empty($data['tags'])) {
            $topic->tags()->sync($data['tags']);
        }
        
        Event::dispatch('forum.topic.created', ['topic' => $topic]);
        
        return redirect()->route('forum.topics.show', ['slug' => $topic->slug])
            ->with('success', __('Topic created successfully'));
    }
    
    public function edit($id) {
        $topic = ForumTopic::findOrFail($id);
        $this->authorize('update', $topic);
        
        $forums = ForumForum::all();
        $tags = ForumTag::all();
        
        return view('forum.topics.edit', compact('topic', 'forums', 'tags'));
    }
    
    public function update(Request $request, $id) {
        $topic = ForumTopic::findOrFail($id);
        $this->authorize('update', $topic);
        
        $validated = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'content' => 'required|min:10',
            'forum_id' => 'required|exists:forum_forums,id',
            'tags' => 'array'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $data = $validated->validated();
        $data['slug'] = $this->generateSlug($data['title'], $topic->id);
        
        $topic->update($data);
        
        // Update first post content
        $firstPost = $topic->posts()->oldest()->first();
        if ($firstPost) {
            $firstPost->edit($data['content'], Auth::id());
        }
        
        // Sync tags
        if (isset($data['tags'])) {
            $topic->tags()->sync($data['tags']);
        }
        
        Event::dispatch('forum.topic.updated', ['topic' => $topic]);
        
        return redirect()->route('forum.topics.show', ['slug' => $topic->slug])
            ->with('success', __('Topic updated successfully'));
    }
    
    public function destroy($id) {
        $topic = ForumTopic::findOrFail($id);
        $this->authorize('delete', $topic);
        
        $forum = $topic->forum;
        $topic->delete();
        
        Event::dispatch('forum.topic.deleted', ['topic' => $topic, 'forum' => $forum]);
        
        return redirect()->route('forum.forums.show', ['slug' => $forum->slug])
            ->with('success', __('Topic deleted successfully'));
    }
    
    public function pin($id) {
        $topic = ForumTopic::findOrFail($id);
        $this->authorize('pin', $topic);
        
        $topic->update(['is_pinned' => !$topic->is_pinned]);
        
        Event::dispatch('forum.topic.pinned', ['topic' => $topic]);
        
        return redirect()->back()
            ->with('success', $topic->is_pinned ? __('Topic pinned') : __('Topic unpinned'));
    }
    
    public function lock($id) {
        $topic = ForumTopic::findOrFail($id);
        $this->authorize('lock', $topic);
        
        $topic->update(['is_locked' => !$topic->is_locked]);
        
        Event::dispatch('forum.topic.locked', ['topic' => $topic]);
        
        return redirect()->back()
            ->with('success', $topic->is_locked ? __('Topic locked') : __('Topic unlocked'));
    }
    
    public function solve($id, $postId = null) {
        $topic = ForumTopic::findOrFail($id);
        $this->authorize('solve', $topic);
        
        $topic->markAsSolved($postId);
        
        return redirect()->back()
            ->with('success', __('Topic marked as solved'));
    }
    
    private function generateSlug($title, $excludeId = null) {
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
        $originalSlug = $slug;
        $count = 1;
        
        while (true) {
            $query = ForumTopic::where('slug', $slug);
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
