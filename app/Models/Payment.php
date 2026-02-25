<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const STATUS_PAID = 'paid';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';

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

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
