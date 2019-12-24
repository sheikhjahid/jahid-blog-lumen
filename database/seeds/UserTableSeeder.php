<?php

use Illuminate\Database\Seeder;
use App\User;
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
        User::create([
            'name' => 'Sheikh Jahid',
            'email' => 'jahid@itobuz.com',
            'password' => Hash::make('123456')
        ]);
    }
}
