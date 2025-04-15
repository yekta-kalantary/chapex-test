<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
    ];

    protected $casts = [
        'category' => 'integer',
        'price' => 'integer',
    ];

    public function category() {
        return $this->belongsTo(Category::class , 'category');
    }
}
