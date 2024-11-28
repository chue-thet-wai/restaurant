<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlots extends Model
{
    use HasFactory;

    protected $table = 'myrt_time_slots';

    protected $guarded = [];

}
