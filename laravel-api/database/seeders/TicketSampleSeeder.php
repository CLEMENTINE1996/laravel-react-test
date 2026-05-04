<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSampleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::firstWhere('email', 'manager@example.com');
        $assignee = User::firstWhere('email', 'assignee@example.com');

        if (! $manager || ! $assignee) {
            return;
        }

        Ticket::updateOrCreate([
            'title' => 'Unable to access ticket dashboard',
        ], [
            'description' => 'The manager cannot access the ticket dashboard from the admin panel.',
            'category' => 'bug',
            'status' => 'open',
            'priority' => 'high',
            'requestor_id' => $manager->id,
            'assignee_id' => $assignee->id,
        ]);

        Ticket::updateOrCreate([
            'title' => 'Add export to CSV feature',
        ], [
            'description' => 'Users need the ability to export ticket lists to a CSV file.',
            'category' => 'feature_request',
            'status' => 'in_progress',
            'priority' => 'medium',
            'requestor_id' => $manager->id,
            'assignee_id' => $assignee->id,
        ]);
    }
}
