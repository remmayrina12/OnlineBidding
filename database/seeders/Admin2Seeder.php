<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Admin2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin2',
            'email' => 'Admin2@gmail.com',
            'password' => bcrypt('admin'),
            'role' => 'admin2',
        ]);
    }
}
