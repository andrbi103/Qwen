<?php
/**
 * Blog Module - Comment Controller
 * Handles blog comment operations
 */

class BlogCommentController extends Controller {
    
    public function store(Request $request, $postId) {
        $post = BlogPost::findOrFail($postId);
        
        $validated = Validator::make($request->all(), [
            'content' => 'required|min:10|max:2000',
            'parent_id' => 'nullable|exists:blog_comments,id'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }
        
        $data = $validated->validated();
        $data['post_id'] = $post->id;
        $data['user_id'] = Auth::id() ?: null;
        $data['ip_address'] = $request->ip();
        $data['status'] = Auth::check() ? 'approved' : 'pending';
        
        // Check if replying to a comment
        if (!empty($data['parent_id'])) {
            $parentComment = BlogComment::findOrFail($data['parent_id']);
            if ($parentComment->post_id != $post->id) {
                return redirect()->back()
                    ->with('error', __('Invalid parent comment'));
            }
        }
        
        $comment = BlogComment::create($data);
        
        Event::dispatch('blog.comment.created', ['comment' => $comment, 'post' => $post]);
        
        $message = Auth::check() 
            ? __('Comment submitted successfully')
            : __('Comment submitted successfully and is awaiting approval');
        
        return redirect()->route('blog.posts.show', ['slug' => $post->slug])
            ->with('success', $message);
    }
    
    public function approve($id) {
        $comment = BlogComment::findOrFail($id);
        $this->authorize('approve', $comment);
        
        $comment->update(['status' => 'approved']);
        
        Event::dispatch('blog.comment.approved', ['comment' => $comment]);
        
        return redirect()->back()
            ->with('success', __('Comment approved successfully'));
    }
    
    public function reject($id) {
        $comment = BlogComment::findOrFail($id);
        $this->authorize('reject', $comment);
        
        $comment->update(['status' => 'rejected']);
        
        Event::dispatch('blog.comment.rejected', ['comment' => $comment]);
        
        return redirect()->back()
            ->with('success', __('Comment rejected successfully'));
    }
    
    public function destroy($id) {
        $comment = BlogComment::findOrFail($id);
        $this->authorize('delete', $comment);
        
        $post = $comment->post;
        $comment->delete();
        
        Event::dispatch('blog.comment.deleted', ['comment' => $comment, 'post' => $post]);
        
        return redirect()->back()
            ->with('success', __('Comment deleted successfully'));
    }
}
