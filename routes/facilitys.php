<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facilitys;

Route::post('addfacility',[Facilitys::class , 'addFacility']);

Route::post('addfacilitytype',[Facilitys::class , 'addFacilityType']);

Route::get('getfacilitys',[Facilitys::class , 'getFacilitys']);

Route::get('getfacilitytypes',[Facilitys::class , 'getFacilityTypes']);
