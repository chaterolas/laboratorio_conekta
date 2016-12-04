<?php
 
class UserSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
 
        User::create([
            'email' => 'first@user.com',
            'password' => Hash::make('first_password')
        ]);
 
        User::create([
            'email' => 'second@user.com',
            'password' => Hash::make('second_password')
        ]);
    }
 
}