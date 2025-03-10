<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'tipo_tikect',
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
}
