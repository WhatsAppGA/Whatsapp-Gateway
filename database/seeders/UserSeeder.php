<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
      
       $user = [
           'id' => 1,
        'username' => 'admin',
        'email' => 'ilmansunannudin2@gmail.com',
        'email_verified_at' => now(),
        'limit_device' => 100,
        'active_subscription' => 'lifetime',
        'password' => bcrypt(123456),
        'api_key' => Str::random(15),
        'chunk_blast' =>100
    ];

    User::create($user);
    }
}
