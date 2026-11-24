<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmLocation extends Model
{
    protected $fillable = [
        'name', 'place_name', 'location_type', 'description', 'latitude', 'longitude',
        'photo_path', 'photo_alt', 'source_url', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_published' => 'boolean',
        ];
    }
}
