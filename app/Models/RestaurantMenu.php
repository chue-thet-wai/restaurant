<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantMenu extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'myrt_restaurant_menu';

    protected $guarded = [];

    public function category() {
        
        return $this->belongsTo(Category::class);
    }
}