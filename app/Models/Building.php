<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    public $table='buildings';

    public function floors() {
      return $this->hasMany('App/Models/Floor');
    }
}
