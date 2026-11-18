<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model {
    protected $fillable = ['title','slug','category','summary','body','image_path','is_published','sort_order'];
    protected function casts(): array { return ['is_published' => 'boolean']; }

}
