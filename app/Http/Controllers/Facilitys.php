<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Facilitytype;
use Validator;

class Facilitys extends Controller
{
    //

    public function addFacility(Request $request) {
      $validator = Validator::make($request->all(),[
        'type_id' => 'required|numeric',
        'type_name' => 'required',
        'name' => 'required|max:100|min:2',
        'control' => 'numeric',
        'photo' => 'required',
        'my_token' => 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        try{
          $facility = new Facility();
          $facility->Hotel = 1 ;
          $facility->TypeId = $request->input('type_id');
          $facility->TypeName = $request->input('type_name');
          $facility->Name = $request->input('name');
          $facility->Control = $request->input('control');
          $facility->photo = $request->input('photo');
          $facility->save();
          $result = ['result'=>'success','insertedRow'=>$facility,'error'=>null];
        }
        catch(Exeption $e){
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function addFacilityType(Request $request) {
      $validator = Validator::make($request->all(),[
        'name' => 'required|max:50|unique:facilitytypes,Name',
        'my_token' => 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        try{
          $facilityType = new Facilitytype();
          $facilityType->Name = $request->input('name');
          $facilityType->save();
          $result = ['result'=>'success','insertedRow'=>$facilityType,'error'=>null];
        }
        catch(Exeption $e){
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function addRestaurantMenu(Request $request) {
      $validator = Validator::make($request->all(),[
        'english_name' => 'required|max:50',
        'arabic_name' => 'required|max:50',
        'facility_id' => 'required|numeric',
        'my_token' => 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {

      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result;
    }


    // get functions

    public function getFacilityTypes () {
      return Facilitytype::all();
    }

    public function getFacilitys () {
      return Facility::all();
    }
}
