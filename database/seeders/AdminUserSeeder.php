<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the primary portfolio owner account without overwriting existing password.
     */
    public function run(): void
    {
        $admin = [
            'name' => 'Pani Padika',
            'email' => 'panipadika123@gmail.com',
            'email_verified_at' => now(),
        ];

        $user = User::firstOrNew(['email' => $admin['email']]);

        if (! $user->exists) {
            $user->fill($admin);
            $user->password = Hash::make((string) env('ADMIN_INITIAL_PASSWORD', 'password'));
            $user->save();
        } else {
            // Update profile metadata only, never reset existing password
            $user->name = $admin['name'];
            $user->email_verified_at = $user->email_verified_at ?? $admin['email_verified_at'];
            $user->save();
        }
    }
}
