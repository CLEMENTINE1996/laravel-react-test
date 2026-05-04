<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'priority',
        'requestor_id',
        'assignee_id',
        'is_escalated',
    ];

    protected $casts = [
        'is_escalated' => 'boolean',
    ];

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function analysis()
    {
        return $this->hasOne(TicketAnalysis::class)->latestOfMany();
    }
}
