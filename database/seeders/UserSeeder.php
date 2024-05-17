<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $users = [
                [
                    'role_id'=>1,
                    'username'=>"Admin",
                    'email'=>'admin@gmail.com',
                    'password'=>"admin123"
                ],
                [
                    'role_id'=>2,
                    'username'=>"Client",
                    'email'=>'client@gmail.com',
                    'password'=>"client123"
                ],
            ];

            foreach($users as $user){
                User::create([
                    'role_id'=>$user['role_id'],
                    'username'=>$user['username'],
                    'email'=>$user['email'],
                    'password'=>$user['password']
                ]);
            }

    }
    }

