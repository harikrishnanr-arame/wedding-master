<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    protected $fillable = [
        'user_id',
        'user_template_id',
        'amount',
        'currency',
        'payment_provider',
        'status',
        'paid_at',
        'is_active'
    ];

    public function user() {
    
        return $this->belongsTo(User::class);
    }
}
