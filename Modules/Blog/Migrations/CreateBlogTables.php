<?php
/**
 * Blog Module Migration - مهاجرت پایگاه داده ماژول وبلاگ
 */

namespace Modules\Blog\Migrations;

use OmniCMS\Core\Database\Migration;
use OmniCMS\Core\Database\Blueprint;

class CreateBlogTables extends Migration
{
    /**
     * Run migrations
     */
    public function up()
    {
        // Create posts table
        $this->create('posts', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->text('content');
            $table->integer('author_id');
            $table->string('status')->default('draft'); // draft, published, archived
            $table->dateTime('published_at')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
        });
        
        // Create categories table
        $this->create('categories', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Create tags table
        $this->create('tags', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        
        // Create post_category pivot table
        $this->create('post_category', function(Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('category_id');
            $table->index('post_id');
            $table->index('category_id');
        });
        
        // Create post_tag pivot table
        $this->create('post_tag', function(Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('tag_id');
            $table->index('post_id');
            $table->index('tag_id');
        });
        
        // Create comments table
        $this->create('comments', function(Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('user_id')->nullable();
            $table->string('author_name');
            $table->string('author_email');
            $table->text('content');
            $table->integer('parent_id')->nullable();
            $table->boolean('approved')->default(false);
            $table->string('status')->default('pending'); // pending, approved, spam
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Rollback migrations
     */
    public function down()
    {
        $this->drop('comments');
        $this->drop('post_tag');
        $this->drop('post_category');
        $this->drop('tags');
        $this->drop('categories');
        $this->drop('posts');
    }
}
