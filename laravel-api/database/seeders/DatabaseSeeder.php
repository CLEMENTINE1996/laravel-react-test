<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TicketSampleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'manager@example.com',
        ], [
            'name' => 'Ticket Manager',
            'password' => 'password',
        ]);

        User::updateOrCreate([
            'email' => 'assignee@example.com',
        ], [
            'name' => 'Ticket Assignee',
            'password' => 'password',
        ]);

        $this->call(TicketSampleSeeder::class);
    }
}
