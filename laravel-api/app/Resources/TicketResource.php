<?php

namespace App\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Retrieve the latest analysis for the ticket
        $analysis = $this->getAnalysis();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'is_escalated' => $this->is_escalated,
            'requestor_id' => $this->requestor_id,
            'category' => $this->category,
            'analysis' => [ 
                'summary' => $analysis->summary ?? 'No summary available',
                'suggested_next_actions' => $analysis->suggested_next_actions ?? 'No suggestions available',
            ],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}