<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description','manager_id', 'color_name'])]
class Department extends Model
{
    //relationships
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class);
    }

}
