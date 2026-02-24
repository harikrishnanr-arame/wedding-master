<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTemplate extends Model
{
    protected $fillable = ['user_id', 'template_id', 'title', 'html_file'];

    protected $casts = ['fields' => 'array',];

    public function content()
    {
        return $this->hasOne(UserTemplateContent::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}

