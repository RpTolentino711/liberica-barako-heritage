<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EncyclopediaTopic extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(EncyclopediaEntry::class, 'topic_id');
    }
}
