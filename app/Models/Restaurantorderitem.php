<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurantorderitem extends Model
{
    use HasFactory;
    public $table = 'restaurantorderitems';

    public function restaurantorder () {
      return $this->belongsTo('App/Models/Restaurantorder');
    }
}
