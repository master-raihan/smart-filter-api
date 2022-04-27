<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Raihanul',
            'last_name' => 'Islam',
            'email' => 'raihanul@gmail.com',
            'password' => Hash::make('1234')
        ]);
    }
}
