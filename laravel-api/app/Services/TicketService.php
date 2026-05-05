<?php 

namespace App\Services;

use App\Repositories\Interfaces\TicketRepositoryInterface;
use App\Services\Interfaces\AnalyzeTicketInterface;

class TicketService
{
    protected $ticketRepository;
    protected $analyzeTicketService;

    public function __construct(TicketRepositoryInterface $ticketRepository, AnalyzeTicketInterface $analyzeTicketService)
    {
        $this->ticketRepository = $ticketRepository;
        $this->analyzeTicketService = $analyzeTicketService;
    }

    /**
     * Get all tickets with optional filters.
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTickets(array $filters = [])
    {
        return $this->ticketRepository->getAllTickets($filters);
    }

    /**
     * Get a ticket by ID.
     * @param int $id
     * @return \App\Models\Ticket
     */
    public function getTicketById($id)
    {
        return $this->ticketRepository->getTicketById($id);
    }

    /**
     * Create a new ticket.
     * @param array $data
     * @return \App\Models\Ticket
     */
    public function createTicket(array $data)
    {
        $ticket = $this->ticketRepository->createTicket($data);

        // check for Escalation
        if ($data['priority'] === 'high') {
            $ticket->update(['is_escalated' => true]);
        }

        // execute analysis using ai
        $analysisData = $this->analyzeTicketService->analyzeIssue($ticket->description);

        // save the analysis to the ticket
        $ticket->analysis()->create([
            'ticket_id' => $ticket->id,
            'summary' => $analysisData['summary'],
            'suggested_next_actions' => $analysisData['suggested_next_actions'],
            'source' => $analysisData['source'] ?? 'AI Analysis',
        ]);

        return $ticket->load('analysis');
    }

    /**
     * Update an existing ticket.
     * @param int $id
     * @param array $data
     * @return \App\Models\Ticket
     */
    public function updateTicket($id, array $data)
    {
        return $this->ticketRepository->updateTicket($id, $data);
    }

    /**
    * Delete a ticket.
    * @param int $id
    * @return bool
    */
    public function deleteTicket($id)
    {
        return $this->ticketRepository->deleteTicket($id);
    }
}