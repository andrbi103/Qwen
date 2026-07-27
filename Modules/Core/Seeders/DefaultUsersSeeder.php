<?php
/**
 * Default Users Seeder
 * Creates admin and user accounts
 */

namespace Modules\Core\Seeders;

use Modules\Core\Models\User;
use OmniCMS\Core\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run seeder
     */
    public function run(): void
    {
        // Create admin user
        if (!User::findByUsername('admin')) {
            $admin = new User();
            $admin->username = 'admin';
            $admin->email = 'admin@example.com';
            $admin->password = password_hash('admin123', PASSWORD_DEFAULT);
            $admin->first_name = 'System';
            $admin->last_name = 'Administrator';
            $admin->role = User::ROLE_SUPER_ADMIN;
            $admin->status = User::STATUS_ACTIVE;
            $admin->save();
            
            $this->command->info('Admin user created: username=admin, password=admin123');
        }

        // Create regular user
        if (!User::findByUsername('user')) {
            $user = new User();
            $user->username = 'user';
            $user->email = 'user@example.com';
            $user->password = password_hash('user123', PASSWORD_DEFAULT);
            $user->first_name = 'Regular';
            $user->last_name = 'User';
            $user->role = User::ROLE_USER;
            $user->status = User::STATUS_ACTIVE;
            $user->save();
            
            $this->command->info('Regular user created: username=user, password=user123');
        }
    }
}
