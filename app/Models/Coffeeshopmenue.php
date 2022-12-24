<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coffeeshopmenue extends Model
{
    use HasFactory;
    public $table = 'coffeeshopmenues';

    public function coffeeshopitems() {
      return $this->hasMany('App/Models/Coffeeshopitems');
    }
}
