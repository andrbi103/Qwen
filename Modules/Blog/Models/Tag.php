<?php
/**
 * Blog Module - Tag Model
 * Handles blog tag data operations
 */

class BlogTag extends Model {
    protected $table = 'blog_tags';
    protected $fillable = ['name', 'slug', 'description'];
    
    public function posts() {
        return $this->belongsToMany('BlogPost', 'blog_post_tags', 'tag_id', 'post_id');
    }
    
    public function scopePopular($query, $limit = 10) {
        return $query->withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->limit($limit);
    }
}
