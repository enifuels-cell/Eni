<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckAdmin extends Command
{
    protected $signature = 'admin:check';
    protected $description = 'Check and display admin user credentials';

    public function handle()
    {
        $this->info('=== Checking Admin Users ===');

        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->error('No admin users found!');

            if ($this->confirm('Would you like to create an admin user?')) {
                $this->createAdmin();
            }
            return 0;
        }

        $this->info("Found {$admins->count()} admin user(s):");

        foreach ($admins as $admin) {
            $this->info("\n--- Admin User ---");
            $this->info("ID: {$admin->id}");
            $this->info("Name: {$admin->name}");
            $this->info("Email: {$admin->email}");
            $this->info("Role: {$admin->role}");
            $this->info("Created: {$admin->created_at}");
        }

        if ($this->confirm('\nWould you like to reset the password for an admin user?')) {
            $email = $this->ask('Enter the admin email');
            $admin = User::where('email', $email)->where('role', 'admin')->first();

            if (!$admin) {
                $this->error('Admin user not found!');
                return 1;
            }

            $password = $this->secret('Enter new password (leave empty for "password")');
            if (empty($password)) {
                $password = 'password';
            }

            $admin->password = Hash::make($password);
            $admin->save();

            $this->info("\n✅ Password updated successfully!");
            $this->info("Email: {$admin->email}");
            $this->info("Password: {$password}");
        }

        return 0;
    }

    private function createAdmin()
    {
        $name = $this->ask('Enter admin name', 'Admin User');
        $email = $this->ask('Enter admin email', 'admin@eni.com');
        $password = $this->secret('Enter password (leave empty for "password")');

        if (empty($password)) {
            $password = 'password';
        }

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info("\n✅ Admin user created successfully!");
        $this->info("Email: {$admin->email}");
        $this->info("Password: {$password}");
    }
}
