<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Services\Interfaces\AnalyzeTicketInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $analyzeServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->analyzeServiceMock = Mockery::mock(AnalyzeTicketInterface::class);
        $this->app->instance(AnalyzeTicketInterface::class, $this->analyzeServiceMock);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /* test that we can list all tickets */
    /** @test */
    public function test_it_can_list_all_tickets()
    {
        Ticket::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tickets');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /* test that we can show a specific ticket */
    /** @test */
    public function test_it_can_show_a_specific_ticket()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->getJson("/api/v1/tickets/{$ticket->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('id', $ticket->id);
    }

    /* test that it returns 404 if ticket not found */
    /** @test */
    public function test_it_returns_404_if_ticket_not_found()
    {
        $response = $this->getJson('/api/v1/tickets/999');

        $response->assertStatus(404);
    }

    /* test that we can create a ticket and triggers analysis */
    /** @test */
    public function test_it_can_create_a_ticket_and_triggers_analysis()
    {
        $user = User::factory()->create();
        
        // Prepare mock data for AI analysis
        $this->analyzeServiceMock->shouldReceive('analyzeIssue')
            ->once()
            ->with('Something is broken')
            ->andReturn([
                'summary' => 'Analyzed summary',
                'suggested_next_actions' => ['Fix it'],
                'source' => 'AI'
            ]);

        $data = [
            'title' => 'Test Ticket',
            'description' => 'Something is broken',
            'category' => 'bug',
            'status' => 'open',
            'priority' => 'high',
            'requestor_id' => $user->id,
        ];

        $response = $this->postJson('/api/v1/tickets', $data);

        $response->assertStatus(201)
                 ->assertJsonPath('title', 'Test Ticket')
                 ->assertJsonPath('is_escalated', true);

        $this->assertDatabaseHas('ticket_analyses', [
            'summary' => 'Analyzed summary'
        ]);
    }

    /* test that we can update a ticket */
    /** @test */
    public function test_it_can_update_a_ticket()
    {
        $ticket = Ticket::factory()->create(['title' => 'Old Title']);
        
        $response = $this->putJson("/api/v1/tickets/{$ticket->id}", [
            'title' => 'New Title'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('title', 'New Title');
    }

    /* test that we can delete a ticket */
    /** @test */
    public function test_it_can_delete_a_ticket()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->deleteJson("/api/v1/tickets/{$ticket->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }
}