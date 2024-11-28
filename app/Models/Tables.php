<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tables extends Model
{
    use HasFactory;

    protected $table = 'myrt_tables';

    protected $guarded = [];

    public function tableManagements()
    {
        return $this->hasMany(TableManagement::class, 'table_id');
    }

}
