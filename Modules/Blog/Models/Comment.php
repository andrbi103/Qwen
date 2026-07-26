<?php
/**
 * Blog Module - Comment Model
 * Handles blog comment data operations
 */

class BlogComment extends Model {
    protected $table = 'blog_comments';
    protected $fillable = ['post_id', 'user_id', 'parent_id', 'content', 'status', 'ip_address'];
    
    public function post() {
        return $this->belongsTo('BlogPost', 'post_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function parent() {
        return $this->belongsTo('BlogComment', 'parent_id');
    }
    
    public function replies() {
        return $this->hasMany('BlogComment', 'parent_id');
    }
    
    public function scopeApproved($query) {
        return $query->where('status', 'approved');
    }
    
    public function scopePending($query) {
        return $query->where('status', 'pending');
    }
    
    public function isReply() {
        return $this->parent_id !== null;
    }
}
