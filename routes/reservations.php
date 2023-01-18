<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reservations;

Route::post('/addClient',[Reservations::class , 'addClient']);

Route::post('/addReservation',[Reservations::class , 'addReservation']);  

Route::post('/addRoomReservation',[Reservations::class , 'addRoomReservation']); 

Route::post('/addSuiteReservation',[Reservations::class , 'addSuiteReservation']);

Route::post('/sendMessage',[Reservations::class , 'sendMessage']);

Route::get('/getServiceUsers',[Reservations::class , 'getServiceUsers']);

Route::post('/checkoutReservation',[Reservations::class , 'checkoutReservation']);

Route::post('/prepareRoom',[Reservations::class , 'prepareRoom']);

Route::post('/setRoomOutOfService',[Reservations::class , 'setRoomOutOfService']);

Route::post('/getRoomId',[Reservations::class,'getRoomId']);

Route::post('/poweronRoom',[Reservations::class , 'poweronRoom']);

Route::post('/powerOffRoom',[Reservations::class , 'powerOffRoom']);

Route::post('/powerByCardRoom',[Reservations::class , 'powerByCardRoom']); 

Route::post('/sendMessageToRooms',[Reservations::class , 'sendMessageToRooms']);

Route::post('/addCleanupOrder',[Reservations::class , 'addCleanupOrder']);

Route::post('/addLaundryOrder',[Reservations::class , 'addLaundryOrder']);

Route::post('/addRoomServiceOrder',[Reservations::class , 'addRoomServiceOrder']);

Route::post('/addCheckoutOrder',[Reservations::class,'addCheckoutOrder']);

Route::post('/putRoomOnDNDMode',[Reservations::class , 'putRoomOnDNDMode']);

Route::post('/finishServiceOrder',[Reservations::class , 'finishServiceOrder']);

Route::post('cancelServiceOrder',[Reservations::class,'cancelServiceOrder']);

Route::post('/setTemperatureSetPoint',[Reservations::class,'setTemperatureSetPoint']);

Route::post('/setSetPointInterval',[Reservations::class,'setSetPointInterval']);

Route::post('/setDoorsWarningInterval',[Reservations::class,'setDoorsWarningInterval']);

Route::post('/setWelcomeMessage',[Reservations::class,'setWelcomeMessage']);

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

Route::post('/getOpenCleanupOrders',[Reservations::class , 'getOpenCleanupOrders']); 

Route::post('/getOpenCleanupOrdersCount',[Reservations::class , 'getOpenCleanupOrdersCount']); 

Route::post('/getOpenLaundryOrders',[Reservations::class , 'getOpenLaundryOrders']);  

Route::post('/getOpenLaundryOrdersCount',[Reservations::class , 'getOpenLaundryOrdersCount']);  

Route::post('/getOpenRoomServiceOrders',[Reservations::class , 'getOpenRoomServiceOrders']); 

Route::post('/getOpenRoomServiceOrdersCount',[Reservations::class , 'getOpenRoomServiceOrdersCount']); 

Route::post('/getOpenCheckoutOrders',[Reservations::class , 'getOpenCheckoutOrders']);

Route::post('/getOpenCheckoutOrdersCount',[Reservations::class , 'getOpenCheckoutOrdersCount']);