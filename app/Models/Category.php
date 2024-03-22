<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'metaTitle',
        'content',
        'slug'
    ];
    public function products(){
        return $this->hasMany('\App\Models\Product', 'category_id', 'id');
    }
}
