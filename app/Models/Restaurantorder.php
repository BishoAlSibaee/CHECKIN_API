<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurantorder extends Model
{
    use HasFactory;
    public $table = 'restaurantorders';

    public function restaurantorderitems() {
      return $this->hasMany('App/Models/Restaurantorderitem');
    }
}
