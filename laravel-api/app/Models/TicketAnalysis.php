<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketAnalysis extends Model
{
    use HasFactory;

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
