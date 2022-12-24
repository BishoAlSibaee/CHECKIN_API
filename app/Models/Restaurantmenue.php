<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurantmenue extends Model
{
    use HasFactory;
    public $table = 'restaurantmenues';

    public function restaurantitems() {
      return $this->hasMany('App/Models/Restaurantitem');
    }
}
