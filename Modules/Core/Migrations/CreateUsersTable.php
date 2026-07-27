<?php
/**
 * Create Users Table Migration
 */

namespace Modules\Core\Migrations;

use OmniCMS\Core\Database\Migration;
use OmniCMS\Core\Database\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Table name
     */
    protected string $table = 'users';

    /**
     * Run migration
     */
    public function up(): void
    {
        $this->schema->create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user');
            $table->tinyInteger('status')->default(1);
            $table->string('avatar', 255)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('username');
            $table->index('email');
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse migration
     */
    public function down(): void
    {
        $this->schema->dropIfExists($this->table);
    }
}
