<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minibarorder extends Model
{
    use HasFactory;
    public $table = 'minibarorders' ;

    public function minibarorderitems() {
      return $this->hasMany('App/Models/Minibarorderitem');
    }
}
