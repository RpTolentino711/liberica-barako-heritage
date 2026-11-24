<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncyclopediaEntry extends Model
{
    protected $fillable = ['topic_id', 'title', 'slug', 'summary', 'body', 'image_path', 'source_label', 'source_url', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(EncyclopediaTopic::class, 'topic_id');
    }
}
