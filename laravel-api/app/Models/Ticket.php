<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'priority',
        'requestor_id',
        'is_escalated',
    ];

    protected $casts = [
        'is_escalated' => 'boolean',
    ];

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function analysis()
    {
        return $this->hasOne(TicketAnalysis::class)->latestOfMany();
    }

    public function getAnalysis(){
        return $this->analysis()->first();
    }
}
