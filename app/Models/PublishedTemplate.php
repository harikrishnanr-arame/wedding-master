<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedTemplate extends Model
{
    protected $table = 'published_templates';

    protected $fillable = ['user_id', 'template_id', 'route', 'content_json'];

    // Relationship to UserTemplate
     public function template() {
        return $this->belongsTo(UserTemplate::class, 'template_id');
    }
}