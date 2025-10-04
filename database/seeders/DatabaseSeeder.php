<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Task::factory()->create([
            'title' => 'Test task',
            'description' => 'Test desc',
            'status' => true
        ]);
    }
}
