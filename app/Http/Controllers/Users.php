<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Serviceemployee;
use Illuminate\Support\Facades\Http;

class Users extends Controller
{
    //
    public $firebaseUrl = 'https://hotelservices-ebe66.firebaseio.com';
    public $projectName = 'Test';

    public function login(Request $request) {
        $validator = Validator::make($request->all(),[
          'job_number' => 'required',
          'password' => 'required',
          'department' => 'required:max:40|in:Service,Cleanup,Laundry,RoomService,Restaurant,Reception'
        ]);
        if ($validator->fails()) {
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
          return $result ;
        }
        $employee = Serviceemployee::where('jobNumber',$request->input('job_number'))->where('department',$request->input('department'))->first();
        if ($employee == null) { 
          return ['result'=>'failed','user'=>null,'error'=>'no such user'];
        }
        if (password_verify($request->input('password'),$employee->password)) {
          $myToken = Users::makeToken();
          $employee->mytoken = $myToken;
          $employee->logedin = 1 ;
          if($employee->save()) {
            $this->modifyUserMyTokenInFirebase($employee);
            $this->setLogedinUserInFirebase($employee,1);
            return ['result'=>'success','my_token'=>$employee->mytoken,'error'=>''];
          }
          else {
            return ['result'=>'failed','user'=>'','error'=>'unable to verify user'];
          }
        }
        else {
          return ['result'=>'failed','user'=>'','error'=>'invailed password'];
        }
    }

    public function addUser(Request $request) {
      $users = Serviceemployee::all();
      $firstStatus = false ;
      if (count($users) > 0) {
        $validator = Validator::make($request->all(),[
          'name' => 'required|max:100',
          'job_number' => 'required|unique:serviceemployees,jobNumber|numeric|digits_between:3,10',
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
          'job_number' => 'required|unique:serviceemployees,jobNumber|numeric|digits_between:3,10',
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
          $serviceemployee->token = '';
          $serviceemployee->control = 'all';
          $serviceemployee->logedin = 0;
          try{
            $serviceemployee->save();
            $this->addUserToFIrebase($serviceemployee);
            return ['result'=>'success','user'=>$serviceemployee->jobNumber.' '.$serviceemployee->name,'error'=>''];
          }
          catch(Exception $e){
            return ['result'=>'failed','insertedRow'=>'','error'=>'unable to add user to db '.$e->getMessage()];
          }
      }
      else {
        if (Users::checkAuth($request->input('my_token')) == false) { 
          return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
        }
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
          $serviceemployee->token = '';
          $serviceemployee->control = 'all';
          $serviceemployee->logedin = 0;
          try{
            $serviceemployee->save();
            $this->addUserToFIrebase($serviceemployee);
            return ['result'=>'success','user'=>$serviceemployee->jobNumber.' '.$serviceemployee->name,'error'=>''];
          }
          catch(Exception $e){
            return ['result'=>'failed','insertedRow'=>'','error'=>'unable to add user to db '.$e->getMessage()];
          }
        }
    }

    public function deleteUser(Request $request) {
      $validator = Validator::make($request->all(),[
        'job_number' => 'required|numeric',
        'my_token' => 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','user'=>'','error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token')) == false) {
        return ['result'=>'failed','user'=>'','error'=>'you are unauthorized user'];
      }
      $jobNumber = $request->input('job_number');
      $user = Serviceemployee::where('jobNumber',$jobNumber)->first();
      if ($user == null) {
        return ['result'=>'failed','user'=>'','error'=>'no users has '.$jobNumber.' jobnumber'];
      }
      if ($user->delete()) {
        $response = Http::delete($this->firebaseUrl.'/'.$this->projectName.'ServiceUsers/'.$user->jobNumber.'.json');
        return ['result'=>'success','user'=>'user deleted','error'=>''];
      }
      return ['result'=>'failed','user'=>'','error'=>'unable to delete user in db'];
    }

    public function updatePassword(Request $request) {
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
          return ['result'=>'failed','update'=>null,'error'=>'job number is un available'];
        }
        else {
          if (password_verify($request->input('old_password'),$employee->password)) {
            if ($request->input('new_password') == $request->input('conf_passqord')) {
              $pass = password_hash($request->input('new_password'), PASSWORD_DEFAULT);
              $employee->password = $pass ;
              $employee->save();
              return ['result'=>'success','updated'=>'updated successfully','error'=>null];
            }
            else {
              return ['result'=>'failed','update'=>null,'error'=>'new password and confermation password is incompatible'];
            }
          }
          else {
            return ['result'=>'failed','update'=>null,'error'=>'old password is incorrect'];
          }
        }
      }
      else {
        return ['result'=>'failed','update'=>null,'error'=>'you are un authorized user'];
      }
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

    function addUserToFIrebase(Serviceemployee $user) {
      $arrUser = $this->convertUserToArray($user);
      $response = Http::retry(3,100)->put($this->firebaseUrl.'/'.$this->projectName.'ServiceUsers/'.$user->jobNumber.'.json',$arrUser);
      return $response->successful();
    }

    function convertUserToArray(Serviceemployee $user) {
      $arrUser = [
        'id' => $user->id,
        'projectId' => $user->projectId,
        'name' => $user->name,
        'jobNumber' => $user->jobNumber,
        'password' => $user->password,
        'department' => $user->department,
        'mobile' => $user->mobile,
        'token' => $user->token,
        'mytoken' => $user->mytoken,
        'control' => $user->control,
        'logedin' => 0
      ];
      return $arrUser;
    }

    function modifyUserMyTokenInFirebase(Serviceemployee $user) {
      $arrUser = ['mytoken' => $user->mytoken];
      $response = Http::retry(3,100)->patch($this->firebaseUrl.'/'.$this->projectName.'ServiceUsers/'.$user->jobNumber.'.json',$arrUser);
      return $response->successful();
    }

    function setLogedinUserInFirebase(Serviceemployee $user,int $status) {
      if ($status == 0 || $status == 1) {
        $arrUser = ['logedin' => $user->logedin];
        $response = Http::retry(3,100)->patch($this->firebaseUrl.'/'.$this->projectName.'ServiceUsers/'.$user->jobNumber.'.json',$arrUser);
        return $response->successful();
      }
      return null ;
    }
}
