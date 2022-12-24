<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Serviceemployee;

class Users extends Controller
{
    //

    public function loginReception(Request $request) {
        $validator = Validator::make($request->all(),[
          'job_number' => 'required',
          'password' => 'required',
          'department' => 'required:max:40'
        ]);
        if ($validator->fails()) {
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
          return $result ;
        }
        $employee = Serviceemployee::where('jobNumber',$request->input('job_number'))->first();
        if ($employee) {
          if (password_verify($request->input('password'),$employee->password)) {
            $myToken = Users::makeToken();
            $employee->myToken = $myToken;
            $employee->save();
            $result = ['result'=>'success','my_token'=>$employee->myToken,'error'=>null];
            return $result ;
          }
          $result = ['result'=>'failed','user'=>null,'error'=>'invailed password'];
          return $result ;
        }
        $result = ['result'=>'failed','user'=>null,'error'=>'no such user'];
        return $result ;
    }

    public function addUser(Request $request) {
      $users = Serviceemployee::all();
      //echo count($users);
      $firstStatus = false ;
      if (count($users) > 0) {
        $validator = Validator::make($request->all(),[
          'name' => 'required|max:100',
          'job_number' => 'required|unique:serviceemployees,jobNumber',
          'password' => 'required',
          'department' => 'required|max:40',
          'mobile' => 'required',
          'my_token' => 'required'
        ]);
      }
      else {
        $firstStatus = true ;
        $validator = Validator::make($request->all(),[
          'name' => 'required|max:100',
          'job_number' => 'required|unique:serviceemployees,jobNumber',
          'password' => 'required',
          'department' => 'required|max:40',
          'mobile' => 'required',
        ]);
      }
      if ($validator->fails()) {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if ($firstStatus = true) {
          $pass = password_hash($request->input('password'), PASSWORD_DEFAULT);
          $myToken = Users::makeToken();
          $serviceemployee = new Serviceemployee();
          $serviceemployee->projectId = 1 ;
          $serviceemployee->name = $request->input('name');
          $serviceemployee->jobNumber = $request->input('job_number');
          $serviceemployee->password = $pass;
          $serviceemployee->department = $request->input('department');
          $serviceemployee->mobile = $request->input('mobile');
          $serviceemployee->mytoken = $myToken;
          $serviceemployee->save();
          $result = ['result'=>'success','user'=>$serviceemployee->jobNumber.' '.$serviceemployee->name,'error'=>null];
      }
      else {
        if (Users::checkAuth($request->input('my_token'))) {
          $pass = password_hash($request->input('password'), PASSWORD_DEFAULT);
          $myToken = Users::makeToken();
          $serviceemployee = new Serviceemployee();
          $serviceemployee->projectId = 1 ;
          $serviceemployee->name = $request->input('name');
          $serviceemployee->jobNumber = $request->input('job_number');
          $serviceemployee->password = $pass;
          $serviceemployee->department = $request->input('department');
          $serviceemployee->mobile = $request->input('mobile');
          $serviceemployee->mytoken = $myToken;
          $serviceemployee->save();
          $result = ['result'=>'success','user'=>$serviceemployee->jobNumber.' '.$serviceemployee->name,'error'=>null];
        }
        else {
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
        }
      }

      return $result ;
    }

    public function updateReceptionPassword(Request $request) {
      $validator = Validator::make($request->all(),[
        'job_number' => 'required|numeric',
        'old_password' => 'required',
        'new_password' => 'required',
        'conf_passqord' => 'required',
        'my_token' => 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','update'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))){
        $employee = Serviceemployee::where('jobNumber',$request->input('job_number'))->first();
        if ($employee == null) {
          $result = ['result'=>'failed','update'=>null,'error'=>'job number is un available'];
        }
        else {
          if (password_verify($request->input('old_password'),$employee->password)) {
            if ($request->input('new_password') == $request->input('conf_passqord')) {
              $pass = password_hash($request->input('new_password'), PASSWORD_DEFAULT);
              $employee->password = $pass ;
              $employee->save();
              $result = ['result'=>'success','updated'=>'updated successfully','error'=>null];
            }
            else {
              $result = ['result'=>'failed','update'=>null,'error'=>'new password and confermation password is incompatible'];
            }
          }
          else {
            $result = ['result'=>'failed','update'=>null,'error'=>'old password is incorrect'];
          }
        }
      }
      else {
        $result = ['result'=>'failed','update'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public static function checkAuth($token) {
      $user = Serviceemployee::where('myToken',$token)->first();
      if ($user) {
        return TRUE ;
      }
      return FALSE ;
    }

    public static function makeToken() {
      $randomNumber = rand(100,1000000);
      $myToken = password_hash($randomNumber , PASSWORD_DEFAULT);
      $myToken = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $myToken);
      return $myToken ;
    }
}
