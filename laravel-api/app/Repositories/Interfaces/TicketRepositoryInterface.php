<?php

namespace App\Repositories\Interfaces;

interface TicketRepositoryInterface
{
    /**
     * Get all tickets.
     * @return Collection - A collection of ticket model instances.
     * @param array $filters 
     */
    public function getAllTickets(array $filters);

    /**
     * Get a ticket by ID.
     * @param int $id - The ID of the ticket to retrieve.
      * @return Ticket - The ticket model instance.
      * @throws ModelNotFoundException - If no ticket is found with the given ID.
     */
    public function getTicketById($id);

    /**
     * Create a new ticket.
     * @param array $data - The data for the new ticket.
      * @return Ticket - The created ticket model instance.
     */
    public function createTicket(array $data);

    /**
     * Update an existing ticket.
     * @param int $id - The ID of the ticket to update.
     * @param array $data - The updated data for the ticket.
      * @return Ticket - The updated ticket model instance.
      * @throws ModelNotFoundException - If no ticket is found with the given ID.
     */
    public function updateTicket($id, array $data);

    /**
     * Delete a ticket.
     * @param int $id - The ID of the ticket to delete.
      * @return bool - True if deletion was successful, false otherwise.
      * @throws ModelNotFoundException - If no ticket is found with the given ID.
     */
    public function deleteTicket($id);
}