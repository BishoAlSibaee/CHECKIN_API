<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilityuser extends Model
{
    use HasFactory;
    public $table = 'facilityusers';

    public function facility() {
      return $this->belongsTo('App/Models/Facility');
    }
}
