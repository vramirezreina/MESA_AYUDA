<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Relationships\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'tipo_ticket',
        'prioridad',
        'descripcion',
        'archivo',
        'user_id',
        'assigned_to',
        'estado',
        'comentario'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
     
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

public function statusHistories()
{
    return $this->hasMany(TicketStatusHistory::class, 'ticket_id');
}


}
