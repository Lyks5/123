<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAdminUser extends Command
{
    protected $signature = 'user:fix-admin {email}';
    protected $description = 'Fix admin status for user';

    public function handle()
    {
        $email = $this->argument('email');
        
        DB::table('users')
            ->where('email', $email)
            ->update(['is_admin' => 1]);

        $this->info("Admin status fixed for user {$email}");
        return 0;
    }
}