<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserModel::create([
            'name' => 'Default',
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'remember_token' => '',
            'verify_email' => '',
            'is_active' => 1,
            'is_delete' => 0,
            'group_role' => 'Admin'
        ]);
    }
}
