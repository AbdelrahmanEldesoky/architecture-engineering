<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hasRole extends Model
{
    use HasFactory;
    protected $table="model_has_roles";

    public function model()
    {
        return $this->morphTo();
    }
}
