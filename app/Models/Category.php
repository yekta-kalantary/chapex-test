<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Category extends Model
{
    use Sushi;

    protected array $rows = [
        [
            'id' => 1,
            'name' => 'Cloth',
        ],
        [
            'id' => 2,
            'name' => 'Jewelry',
        ],
        [
            'id' => 3,
            'name' => 'Lifestyle',
        ],
    ];
}
