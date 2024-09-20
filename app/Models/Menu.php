<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    
    protected $table = 'menus';
    protected $fillable = ['main_menu', 'sub_menu'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
    
}