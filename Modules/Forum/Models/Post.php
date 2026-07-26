<?php
/**
 * Forum Module - Post Model
 * Handles forum post data operations
 */

class ForumPost extends Model {
    protected $table = 'forum_posts';
    protected $fillable = ['topic_id', 'user_id', 'parent_id', 'content', 'is_solution', 'edited_at', 'edited_by'];
    
    public function topic() {
        return $this->belongsTo('ForumTopic', 'topic_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function parent() {
        return $this->belongsTo('ForumPost', 'parent_id');
    }
    
    public function replies() {
        return $this->hasMany('ForumPost', 'parent_id');
    }
    
    public function likes() {
        return $this->hasMany('ForumLike', 'post_id');
    }
    
    public function scopeSolution($query) {
        return $query->where('is_solution', true);
    }
    
    public function isSolution() {
        return $this->is_solution;
    }
    
    public function isEdited() {
        return $this->edited_at !== null;
    }
    
    public function getLikesCountAttribute() {
        return $this->likes()->count();
    }
    
    public function like($userId) {
        if (!$this->likes()->where('user_id', $userId)->exists()) {
            ForumLike::create([
                'post_id' => $this->id,
                'user_id' => $userId
            ]);
            
            Event::dispatch('forum.post.liked', ['post' => $this, 'user_id' => $userId]);
        }
        
        return $this;
    }
    
    public function unlike($userId) {
        $like = $this->likes()->where('user_id', $userId)->first();
        
        if ($like) {
            $like->delete();
            
            Event::dispatch('forum.post.unliked', ['post' => $this, 'user_id' => $userId]);
        }
        
        return $this;
    }
    
    public function isLikedBy($userId) {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    
    public function markAsSolution() {
        // Remove solution from other posts in the topic
        ForumPost::where('topic_id', $this->topic_id)
            ->update(['is_solution' => false]);
        
        $this->update(['is_solution' => true]);
        
        $this->topic->markAsSolved($this->id);
        
        return $this;
    }
    
    public function edit($content, $userId) {
        $this->update([
            'content' => $content,
            'edited_at' => date('Y-m-d H:i:s'),
            'edited_by' => $userId
        ]);
        
        Event::dispatch('forum.post.edited', ['post' => $this]);
        
        return $this;
    }
}
