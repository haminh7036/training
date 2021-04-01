<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Insert users
        for ($i = 0 ; $i < 50 ; $i++) {
            UserModel::create([
                'name' => Str::random(15),
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
                'remember_token' => '',
                'verify_email' => '',
                'is_active' => 1,
                'is_delete' => 0,
                'group_role' => 'Reviewer'
            ]);
        }

        for ($i = 0 ; $i < 50 ; $i++) {
            UserModel::create([
                'name' => Str::random(15),
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
                'remember_token' => '',
                'verify_email' => '',
                'is_active' => 1,
                'is_delete' => 0,
                'group_role' => 'Editor'
            ]);
        }
    }
}
