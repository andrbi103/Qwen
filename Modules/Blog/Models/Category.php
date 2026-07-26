<?php
/**
 * Blog Module - Category Model
 * Handles blog category data operations
 */

class BlogCategory extends Model {
    protected $table = 'blog_categories';
    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'meta_title', 'meta_description'];
    
    public function parent() {
        return $this->belongsTo('BlogCategory', 'parent_id');
    }
    
    public function children() {
        return $this->hasMany('BlogCategory', 'parent_id');
    }
    
    public function posts() {
        return $this->hasMany('BlogPost', 'category_id');
    }
    
    public function scopeParent($query) {
        return $query->whereNull('parent_id');
    }
    
    public function getFullSlugAttribute() {
        if ($this->parent) {
            return $this->parent->full_slug . '/' . $this->slug;
        }
        return $this->slug;
    }
}
