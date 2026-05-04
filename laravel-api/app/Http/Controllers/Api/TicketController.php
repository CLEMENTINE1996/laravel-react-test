<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the tickets.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $tickets = $this->ticketService->getAllTickets();
            return response()->json($tickets);
        } catch (Throwable $exception) {
            Log::error('TicketController: index error', ['exception' => $exception]);
            return response()->json(['message' => 'Unable to retrieve tickets.'], 500);
        }
    }

    /**
     * Display the specified ticket.
     * @param int $id - The ID of the ticket to retrieve.
     * @return JsonResponse 
     */
    public function show($id): JsonResponse
    {
        try {
            $ticket = $this->ticketService->getTicketById($id);
            if (!$ticket) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }
            return response()->json($ticket);
        } catch (Throwable $exception) {
            Log::error('TicketController: show error', ['id' => $id, 'exception' => $exception]);
            return response()->json(['message' => 'Unable to retrieve the ticket.'], 500);
        }
    }

    /**
     * Store a newly created ticket in storage.
     * @param Request $request - The incoming request containing ticket data.
     * @return JsonResponse - The created ticket or validation errors.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'summary' => 'nullable|string',
                'suggested_next_actions' => 'nullable|string',
                'category' => 'required|in:bug,feature_request,documentation,complain,suggestion',
                'status' => 'required|in:open,in_progress,closed',
                'priority' => 'required|in:low,medium,high',
                'requestor_id' => 'required|exists:users,id',
                'assignee_id' => 'nullable|exists:users,id',
            ]);

            $ticket = $this->ticketService->createTicket($data);
            return response()->json($ticket, 201);
        } catch (Throwable $exception) {
            Log::error('TicketController: store error', ['data' => $request->all(), 'exception' => $exception]);
            return response()->json(['message' => 'Unable to create the ticket.'], 500);
        }
    }

    /**
     * Update the specified ticket in storage.
     * @param Request $request - The incoming request containing updated ticket data.
     * @param int $id - The ID of the ticket to update.
     * @return JsonResponse - The updated ticket or validation errors.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'summary' => 'nullable|string',
                'suggested_next_actions' => 'nullable|string',
                'category' => 'sometimes|required|in:bug,feature_request,documentation,complain,suggestion',
                'status' => 'sometimes|required|in:open,in_progress,closed',
                'priority' => 'sometimes|required|in:low,medium,high',
                'requestor_id' => 'sometimes|required|exists:users,id',
                'assignee_id' => 'nullable|exists:users,id',
            ]);

            $ticket = $this->ticketService->updateTicket($id, $data);
            if (!$ticket) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }
            return response()->json($ticket);
        } catch (Throwable $exception) {
            Log::error('TicketController: update error', ['id' => $id, 'data' => $request->all(), 'exception' => $exception]);
            return response()->json(['message' => 'Unable to update the ticket.'], 500);
        }
    }

    /**
     * Remove the specified ticket from storage.
     * @param int $id - The ID of the ticket to delete.
     * @return JsonResponse - Success message or error if not found.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deleted = $this->ticketService->deleteTicket($id);
            if (!$deleted) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }
            return response()->json(['message' => 'Ticket deleted successfully']);
        } catch (Throwable $exception) {
            Log::error('TicketController: destroy error', ['id' => $id, 'exception' => $exception]);
            return response()->json(['message' => 'Unable to delete the ticket.'], 500);
        }
    }

}

