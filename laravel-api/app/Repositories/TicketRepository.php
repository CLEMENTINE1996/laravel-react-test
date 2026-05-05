<?php 

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * Get all tickets with optional filtering.
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTickets(array $filters = [])
    {
        $query = Ticket::query();

        $status = $filters['status'] ?? null;
        $priority = $filters['priority'] ?? null;
        $search = $filters['search'] ?? null;

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                ->orWhere('description', 'like', $searchTerm);
            });
        }

        return $query->get();
    }

    /**
     * Get a ticket by ID.
     * @param int $id
     * @return \App\Models\Ticket
     */
    public function getTicketById($id)
    {
        return Ticket::find($id);
    }

    /**
     * Create a new ticket.
     * @param array $data
     * @return \App\Models\Ticket
     */
    public function createTicket(array $data)
    {
        return Ticket::create($data);
    }

    /**
     * Update an existing ticket.
     * @param int $id
     * @param array $data
     * @return \App\Models\Ticket
     */
    public function updateTicket($id, array $data)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete a ticket.
     * @param int $id
     * @return bool
     */
    public function deleteTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return true;
    }
}