<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'haseeb@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);
    }
}
