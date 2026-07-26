<?php
/**
 * Forum Module - Topic Model
 * Handles forum topic data operations
 */

class ForumTopic extends Model {
    protected $table = 'forum_topics';
    protected $fillable = [
        'title', 'slug', 'content', 'forum_id', 'user_id', 
        'is_pinned', 'is_locked', 'is_solved', 'views_count'
    ];
    
    public function forum() {
        return $this->belongsTo('ForumForum', 'forum_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function posts() {
        return $this->hasMany('ForumPost', 'topic_id');
    }
    
    public function tags() {
        return $this->belongsToMany('ForumTag', 'forum_topic_tags', 'topic_id', 'tag_id');
    }
    
    public function lastPost() {
        return $this->hasOne('ForumPost', 'topic_id')->latest();
    }
    
    public function scopePinned($query) {
        return $query->where('is_pinned', true);
    }
    
    public function scopeLocked($query) {
        return $query->where('is_locked', true);
    }
    
    public function scopeSolved($query) {
        return $query->where('is_solved', true);
    }
    
    public function scopeUnanswered($query) {
        return $query->whereDoesntHave('posts', function($q) {
            $q->where('user_id', '!=', DB::raw('forum_topics.user_id'));
        });
    }
    
    public function isLocked() {
        return $this->is_locked;
    }
    
    public function isPinned() {
        return $this->is_pinned;
    }
    
    public function isSolved() {
        return $this->is_solved;
    }
    
    public function canReply($userId) {
        if ($this->is_locked) {
            return false;
        }
        
        $user = User::find($userId);
        if (!$user) {
            return false;
        }
        
        // Check if user is banned in this forum
        $ban = ForumBan::where('forum_id', $this->forum_id)
            ->where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('ip_address', request()->ip());
            })
            ->first();
        
        return !$ban;
    }
    
    public function markAsSolved($postId = null) {
        if ($postId) {
            // Mark specific post as the solution
            $this->posts()->update(['is_solution' => false]);
            ForumPost::where('id', $postId)->update(['is_solution' => true]);
        }
        
        $this->update(['is_solved' => true]);
        
        Event::dispatch('forum.topic.solved', ['topic' => $this, 'post_id' => $postId]);
    }
    
    public function incrementViewCount() {
        $this->increment('views_count');
    }
}
