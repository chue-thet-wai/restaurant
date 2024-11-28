<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchOpeningTime extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'myrt_branch_opening_time';

    protected $guarded = [];
}
