<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\roomsManagement;

// Add Routes
Route::post('/addbuilding',[roomsManagement::class , 'addbuilding']);

Route::post('/addfloorrooms',[roomsManagement::class , 'addFloorRooms']);

Route::post('/addoneroom',[roomsManagement::class , 'addOneRoom']);

Route::post('/addonefloor',[roomsManagement::class , 'addOneFloor']);

Route::post('/addsuite',[roomsManagement::class , 'addSuite']);

// Delete Routes
Route::delete('/deletebuilding',[roomsManagement::class , 'deleteBuilding']);

Route::delete('/deletefloorandrooms',[roomsManagement::class , 'deleteFloorAndRooms']);

Route::delete('/deleteroom',[roomsManagement::class , 'deleteRoom']);

Route::delete('/deleteroomtype',[roomsManagement::class , 'deleteRoomType']);

// Get Routes
Route::get('/getbuildings',[roomsManagement::class , 'getBuildings']);

Route::get('/getfloors',[roomsManagement::class , 'getFloors']);

Route::get('/getrooms',[roomsManagement::class , 'getRooms']);
