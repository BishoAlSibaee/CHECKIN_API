<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users;


Route::post('/loginreception',[Users::class,'loginReception']);

Route::post('/adduser',[Users::class,'addUser']);

Route::post('/updatereceptionpassword',[Users::class,'updateReceptionPassword']);
