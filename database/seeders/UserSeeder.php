<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('users')->insert([
        [    'name'     => 'mohamad',
            'email'    => "mohamad@gmail.com",
            'role'    => "1",
            'password' => bcrypt('123456'),
        ],
        [
            'name'     => 'takwa',
            'email'    => "takwa@gmail.com",
            'role'    => "1",
            'password' => bcrypt('takwa@23599'),
        ]
        ]
    ); 
    }
}
