<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GalleryItem extends Model {
    protected $fillable = ['title','caption','image_path','image_credit','is_published','sort_order'];
    protected function casts(): array { return ['is_published' => 'boolean']; }

}
