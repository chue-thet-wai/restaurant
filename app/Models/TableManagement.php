<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableManagement extends Model
{
    use HasFactory;

    protected $table = 'myrt_table_management';

    protected $guarded = [];

}
