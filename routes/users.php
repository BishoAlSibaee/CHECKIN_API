<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users;


Route::post('/login',[Users::class,'login']);

Route::post('/addUser',[Users::class,'addUser']);

Route::post('/deleteUser',[Users::class,'deleteUser']);

Route::post('/updatePassword',[Users::class,'updatePassword']);
