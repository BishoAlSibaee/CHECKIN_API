<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Client;

class Reservations extends Controller
{
    // add functions

    public static function addClient(Request $request) {

      $validator = Validator::make($request->all(),[
        'first_name' => 'required|max:50|min:2',
        'last_name' => 'required|max:50|min:2',
        'mobile' => 'required|max:50|min:10',
        'email' => 'max:50|nullable',
        'id_type' => 'required|in:ID,PASSPORT',
        'id_number' => 'required|max:20',
        'my_token' => 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        try {
          $client = new Client();
          $client->Hotel = 1 ;
          $client->FirstName = $request->input('first_name') ;
          $client->LastName = $request->input('last_name') ;
          $client->Mobile = $request->input('mobile') ;
          $client->Email = $request->input('email') ;
          $client->IdType = $request->input('id_type') ;
          $client->IdNumber = $request->input('id_number') ;
          $client->save();
          $result = ['result'=>'successs','insertedRow'=>$client,'error'=>null];
        }
        catch(Exeption $e) {
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>$e->getMessage()];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }
}
