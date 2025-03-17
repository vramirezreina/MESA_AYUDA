<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'status', 'changed_at'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

}
