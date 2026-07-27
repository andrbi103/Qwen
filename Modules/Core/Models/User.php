<?php
/**
 * User Model
 * Base user model for authentication and authorization
 */

namespace Modules\Core\Models;

use OmniCMS\Core\Database\Model;

class User extends Model
{
    /**
     * Table name
     */
    protected string $table = 'users';

    /**
     * Primary key
     */
    protected string $primaryKey = 'id';

    /**
     * Fillable attributes
     */
    protected array $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'role',
        'status',
        'avatar',
        'last_login_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Hidden attributes
     */
    protected array $hidden = ['password'];

    /**
     * Casts
     */
    protected array $casts = [
        'id' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime'
    ];

    /**
     * Default role
     */
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';

    /**
     * Default status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;

    /**
     * Find user by username
     */
    public static function findByUsername(string $username): ?self
    {
        return self::where('username', $username)->first();
    }

    /**
     * Find user by email
     */
    public static function findByEmail(string $email): ?self
    {
        return self::where('email', $email)->first();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Set password with hashing
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Update last login time
     */
    public function updateLastLogin(): void
    {
        $this->last_login_at = date('Y-m-d H:i:s');
        $this->save();
    }
}
