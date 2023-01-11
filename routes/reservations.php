<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reservations;

Route::post('/addclient',[Reservations::class , 'addClient']);

Route::post('/addreservation',[Reservations::class , 'addReservation']);

Route::post('/sendmessage',[Reservations::class , 'sendMessage']);

Route::get('/getserviceusers',[Reservations::class , 'getServiceUsers']);

Route::post('/checkoutreservation',[Reservations::class , 'checkoutReservation']);

Route::post('/prepareroom',[Reservations::class , 'prepareRoom']);

Route::post('/setroomoutofservice',[Reservations::class , 'setRoomOutOfService']);

Route::post('/getroomid',[Reservations::class,'getRoomId']);

Route::post('/poweronroom',[Reservations::class , 'poweronRoom']);

Route::post('/poweroffroom',[Reservations::class , 'powerOffRoom']);

Route::post('/sendmessagetorooms',[Reservations::class , 'sendMessageToRooms']);

Route::post('/addcleanuporder',[Reservations::class , 'addCleanupOrder']);

Route::post('/addlaundryorder',[Reservations::class , 'addLaundryOrder']);

Route::post('/addroomserviceorder',[Reservations::class , 'addRoomServiceOrder']);

Route::post('/finishserviceorder',[Reservations::class , 'finishServiceOrder']);

Route::post('/settemperaturesetpoint',[Reservations::class,'setTemperatureSetPoint']);