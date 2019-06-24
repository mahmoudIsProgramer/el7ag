<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = \App\Admin::create([
            'firstName' =>'super',
            'lastName' =>'admin',
            'email'=>'admin@admin.com',
            'password'=>bcrypt('123456'),
            'user_token'=>\Illuminate\Support\Str::random(60)
        ]);
        $admin->attachRole('super_admin');
    }
}
