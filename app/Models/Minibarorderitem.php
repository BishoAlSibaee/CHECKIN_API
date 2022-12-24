<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minibarorderitem extends Model
{
    use HasFactory;
    public $table = 'minibarorderitems';

    public function minibarorder() {
      return $this->belongsTo('App/Models/Minibarorder'); 
    }
}
