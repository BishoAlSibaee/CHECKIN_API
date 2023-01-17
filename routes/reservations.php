<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reservations;

Route::post('/addclient',[Reservations::class , 'addClient']);

Route::post('/addreservation',[Reservations::class , 'addReservation']);  

Route::post('/addRoomReservation',[Reservations::class , 'addRoomReservation']); 

Route::post('/addSuiteReservation',[Reservations::class , 'addSuiteReservation']);

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

Route::post('/addCheckoutOrder',[Reservations::class,'addCheckoutOrder']);

Route::post('/putRoomOnDNDMode',[Reservations::class , 'putRoomOnDNDMode']);

Route::post('/finishserviceorder',[Reservations::class , 'finishServiceOrder']);

Route::post('cancelServiceOrder',[Reservations::class,'cancelServiceOrder']);

Route::post('/settemperaturesetpoint',[Reservations::class,'setTemperatureSetPoint']);

Route::post('/setsetpointinterval',[Reservations::class,'setSetPointInterval']);

Route::post('/setdoorswarninginterval',[Reservations::class,'setDoorsWarningInterval']);

Route::post('/setwelcomemessage',[Reservations::class,'setWelcomeMessage']);

Route::post('/setCheckinModeDuration',[Reservations::class,'setCheckinModeDuration']);

Route::post('/setCheckoutModeDuration',[Reservations::class,'setCheckoutModeDuration']);

Route::post('/setLogo' , [Reservations::class , 'setLogo']);

Route::post('/getDoorOpensByRoom',[Reservations::class , 'getDoorOpensByRoom']);  

Route::post('/getDoorOpensByUser',[Reservations::class , 'getDoorOpensByUser']);

Route::post('/getServiceOrdersByRoom',[Reservations::class , 'getServiceOrdersByRoom']); 

Route::post('/getServiceOrdersByReservation',[Reservations::class , 'getServiceOrdersByReservation']);  

Route::post('/getServiceOrdersByOrderType',[Reservations::class , 'getServiceOrdersByOrderType']);  

Route::post('/getServiceOrdersByClient',[Reservations::class , 'getServiceOrdersByClient']);  

Route::post('/searchReservationsByDate',[Reservations::class , 'searchReservationsByDate']);  

Route::post('/searchReservationsByRoomId',[Reservations::class , 'searchReservationsByRoomId']);  

Route::post('/searchReservationsByClientId',[Reservations::class , 'searchReservationsByClientId']);  

Route::post('/getOpenReservations',[Reservations::class , 'getOpenReservations']); 

Route::post('/getClosedReservations',[Reservations::class , 'getClosedReservations']);   