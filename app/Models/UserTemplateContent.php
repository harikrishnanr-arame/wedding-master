<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTemplateContent extends Model
{
    protected $fillable = [
        'user_template_id',
        'content_json'
    ];

    public function userTemplate()
    {
        return $this->belongsTo(UserTemplate::class);
    }
}

