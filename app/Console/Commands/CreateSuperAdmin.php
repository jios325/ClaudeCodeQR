<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user for QR Code Generator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = 'admin';
        $password = 'qrcode123';

        // Create super admin user
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'username' => $username,
            'password' => Hash::make($password),
            'user_type' => 'super_admin',
        ]);

        $this->info('Super admin user created successfully!');
        $this->info('Username: ' . $username);
        $this->info('Password: ' . $password);
        
        return Command::SUCCESS;
    }
}
