<?php 

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * Get all tickets.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTickets()
    {
        return Ticket::all();
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