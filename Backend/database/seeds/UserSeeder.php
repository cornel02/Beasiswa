<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
            ->insert([
                'name' => 'admin',
                'email' => 'admin',
                'password' => '$2a$12$EXxeauS9/ngV8u/sUjvDb.aKEf5MfRPP5GxnT6ZxJqh0ocmfDqFD6',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ]);
    }
}
