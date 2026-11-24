<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourneyStep extends Model
{
    protected $fillable = [
        'title', 'description', 'media_path', 'media_type', 'media_alt', 'sort_order', 'is_published',
        'source', 'source_url', 'creator', 'photo_url', 'license', 'license_url', 'media_note',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
