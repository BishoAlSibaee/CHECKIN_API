<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reservations;

Route::post('/addclient',[Reservations::class , 'addClient']);
