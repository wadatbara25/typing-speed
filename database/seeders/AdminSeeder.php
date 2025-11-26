<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminSeeder extends Seeder
{
   
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', '12345678');

        $adminData = [
            'name' => 'مدير النظام',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ];

     
        User::updateOrCreate(['email' => $email], $adminData);

        $this->command->info("✅ تم إنشاء أو تحديث حساب المدير بنجاح: {$email}");
    }
}
