<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncyclopediaReference extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'url', 'pdf_path', 'position'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(EncyclopediaEntry::class, 'entry_id');
    }
}
