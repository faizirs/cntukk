<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void{
        \App\Models\User::query()->delete();
    
    
        User::create([
            'id' => 1,
            'name' => 'Admin',
            'username' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        User::create([
            'id' => 2,
            'name' => 'Siswa',
            'username' => '123',
            'role' => 'siswa',
            'password' => Hash::make('user123'),
        ]);
        
    }
}