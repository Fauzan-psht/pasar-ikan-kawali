<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'event_id', 'payload_redacted', 'received_at', 'processed_at',
    ];

    protected $casts = [
        'payload_redacted' => 'array',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}