<?php

namespace Database\Seeders;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chat;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\Comment;
use App\Models\Saved_Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(4)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        Comment::factory(10)->create();

        Scholarship::factory(20)->create();
        Saved_Scholarship::factory(5)->create();
        Chat::factory(30)->create();
    }
}
