<?php
/**
 * Blog Post Model - مدل پست وبلاگ
 */

namespace Modules\Blog\Models;

use OmniCMS\Core\Database\Model;

class Post extends Model
{
    protected static $table = 'posts';
    protected static $primaryKey = 'id';
    
    /**
     * Get published posts
     */
    public static function published()
    {
        $table = static::getTable();
        $sql = "SELECT * FROM {$table} WHERE status = 'published' AND published_at <= NOW() ORDER BY published_at DESC";
        $stmt = self::getConnection()->query($sql);
        return static::hydrateCollection($stmt->fetchAll());
    }
    
    /**
     * Get post by slug
     */
    public static function bySlug($slug)
    {
        $table = static::getTable();
        $sql = "SELECT * FROM {$table} WHERE slug = :slug LIMIT 1";
        $stmt = self::getConnection()->query($sql, ['slug' => $slug]);
        $data = $stmt->fetch();
        
        if ($data) {
            return new static($data);
        }
        
        return null;
    }
    
    /**
     * Get posts by category
     */
    public static function byCategory($categoryId, $limit = 10)
    {
        $table = static::getTable();
        $sql = "SELECT p.* FROM {$table} p
                INNER JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id = :category_id 
                AND p.status = 'published'
                ORDER BY p.published_at DESC
                LIMIT :limit";
        $stmt = self::getConnection()->query($sql, [
            'category_id' => $categoryId,
            'limit' => $limit
        ]);
        return static::hydrateCollection($stmt->fetchAll());
    }
    
    /**
     * Get posts by tag
     */
    public static function byTag($tagId, $limit = 10)
    {
        $table = static::getTable();
        $sql = "SELECT p.* FROM {$table} p
                INNER JOIN post_tag pt ON p.id = pt.post_id
                WHERE pt.tag_id = :tag_id 
                AND p.status = 'published'
                ORDER BY p.published_at DESC
                LIMIT :limit";
        $stmt = self::getConnection()->query($sql, [
            'tag_id' => $tagId,
            'limit' => $limit
        ]);
        return static::hydrateCollection($stmt->fetchAll());
    }
    
    /**
     * Get recent posts
     */
    public static function recent($limit = 5)
    {
        return static::published(array_slice(static::published(), 0, $limit));
    }
    
    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $table = static::getTable();
        $sql = "UPDATE {$table} SET views = views + 1 WHERE id = :id";
        self::getConnection()->query($sql, ['id' => $this->attributes['id']]);
        return true;
    }
    
    /**
     * Get comments for post
     */
    public function comments()
    {
        return Comment::where('post_id', '=', $this->attributes['id']);
    }
    
    /**
     * Get author
     */
    public function author()
    {
        // Assuming users table exists
        try {
            return \OmniCMS\Core\Database\Model::find($this->attributes['author_id']);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get categories
     */
    public function categories()
    {
        return Category::all(); // Simplified - should use join
    }
    
    /**
     * Get tags
     */
    public function tags()
    {
        return Tag::all(); // Simplified - should use join
    }
    
    /**
     * Check if post is published
     */
    public function isPublished()
    {
        return $this->attributes['status'] === 'published';
    }
    
    /**
     * Publish post
     */
    public function publish()
    {
        return $this->update([
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Draft post
     */
    public function draft()
    {
        return $this->update(['status' => 'draft']);
    }
}
