<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAnalysis extends Model
{
    protected $fillable = [
        'ticket_id',
        'summary',
        'suggested_next_actions',
        'source',
    ];

    protected $casts = [
        'suggested_next_actions' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
