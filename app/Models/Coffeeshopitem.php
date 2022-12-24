<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coffeeshopitem extends Model
{
    use HasFactory;
    public $table = 'coffeeshopitems';

    public function coffeeshopmenue() {
      return $this->belonsTo('App/Models/Coffeeshopmenue');
    }

}
