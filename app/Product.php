<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'price', 'amount', 'weight', 'height', 'description', 'color_id',
    ];

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
