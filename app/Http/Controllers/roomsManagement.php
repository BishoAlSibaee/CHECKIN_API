<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class roomsManagement extends Controller
{
  public $firebaseUrl = 'https://hotelservices-ebe66.firebaseio.com';
  public $projectName = 'Test';
//___________________________________________________________________
    // add functions

    public function addBuilding(Request $request) {

      $validator = validator::make($request->all(),[
        'building_number' => 'required|unique:buildings,buildingNo|numeric',
        'building_name' => 'required|unique:buildings,buildingName|max:40',
        'floors_number' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $bid = $request->input('building_number');
        $bname = $request->input('building_name');
        $fnum = $request->input('floors_number');
        $pars = [$bid,$bname,$fnum];
        DB::beginTransaction();
        try{
              $buildingId = DB::table('buildings')->insertGetId([
                  'projectId' => 1,
                  'buildingNo' => $bid,
                  'buildingName' => $bname,
                  'floorsNumber' => $fnum,
              ]);
              for ($i=0;$i<$request->input('floors_number');$i++) {
                DB::insert('insert into floors (building_id,floorNumber,rooms) values (?,?,?)',[$buildingId,($i+1),0]);
              }
              DB::commit();
              $building = Building::find($buildingId);
              $result = ['result'=>'success','insertedRow'=>$building,'error'=>null];
          }
          catch(Exception $e) {
            DB::rollback();
            $result = ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
          }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function addFloorRooms(Request $request) {
      $validator = validator::make($request->all(),[
        'Building' => 'required|numeric',
        'BuildingId' => 'required|numeric',
        'Floor' => 'required|numeric',
        'FloorId'=> 'required|numeric',
        'Rooms'=> 'required|numeric',
        'start'=> 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $building_id = $request->input('BuildingId');
        $building = $request->input('Building');
        $startRoom = $request->input('start');
        $roomsNum = $request->input('Rooms');
        $floor = $request->input('Floor');
        $floorId = $request->input('FloorId');
        $insertedRooms = array();
        $rrr = Room::where('Building','=',$building)->where('RoomNumber','=',$startRoom)->first();
        if ($rrr == null) {
          DB::beginTransaction();
          try{
                for ($i=0;$i < $roomsNum;$i++) {
                  $RoomNumber = $startRoom + $i ;
                  $insertedRooms[$i] = roomsManagement::addRoom($RoomNumber,$building,$building_id,$floor,$floorId);
                  if ($insertedRooms[$i] != null) {
                    $this->addRoomToFirebase($insertedRooms[$i]);
                  }
                }
                $floor = Floor::find($floorId);
                $floor->rooms = $floor->rooms + $roomsNum ;
                $floor->save();
                DB::commit();
                $result = ['result'=>'success','insertedRow'=>$insertedRooms,'error'=>null];
            }
            catch(Exception $e) {
              DB::rollback();
              $result = ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
            }
            return $result ;
        }
        else {
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>'room number '.$startRoom.' already taken '];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function addOneRoom(Request $request) {

      $validator = validator::make($request->all(),[
        'room_number' => 'required|unique:rooms,RoomNumber|numeric',
        'building' => 'required|numeric',
        'building_id' => 'required|numeric',
        'floor' => 'required|numeric',
        'floor_id'=> 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      try {
          $room = new Room();
          $room->RoomNumber = $request->input('room_number');
          $room->hotel = 1;
          $room->Building = $request->input('building');
          $room->building_id = $request->input('building_id');
          $room->Floor = $request->input('floor');
          $room->floor_id = $request->input('floor_id');
          $room->save();
          $this->addRoomToFirebase($room);
          $result = ['result'=>'success','insertedRow'=>$room,'error'=>null];
        }
        catch(Exeption $e){
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
        }

      return $room ;
    }

    public function addOneFloor(Request $request) {
      $validator = validator::make($request->all(),[
        'start' => 'required|numeric',
        'building_id' => 'required|numeric',
        'floor_number' => 'required|numeric',
        'rooms' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $fff = Floor::where('building_id','=',$request->input('building_id'))->where('floorNumber','=',$request->input('floor_number'))->first();
        if ($fff == null ) {
          $rrr = Room::where('Floor','=',$request->input('floor_number'))->where('RoomNumber','=',$request->input('start'))->first();
          if ($rrr == null ) {
            DB::beginTransaction();
              try{
                $floor = new Floor() ;
                $floor->building_id = $request->input('building_id');
                $floor->floorNumber = $request->input('floor_number');
                $floor->rooms = $request->input('rooms');
                $floor->save();
                for ($i=0;$i < $request->input('rooms');$i++) {
                  $RoomNumber = $request->input('start') + $i ;
                  $b = Building::find($floor->building_id);
                  $room = roomsManagement::addRoom($RoomNumber,$b->buildingNo,$b->id,$request->input('floor_number'),$floor->id);
                  if ($room != null) {
                    $this->addRoomToFirebase($room);
                  }
                }
                DB::commit();
                $result = ['result'=>'success','insertedRow'=>$floor,'error'=>null];
              }
              catch(Exeption $e){
                DB::rollback();
                $result = ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
              }
          }
          else {
            $result = ['result'=>'failed','insertedRow'=>null,'error'=>'room number '.$request->input('start').' already taken '];
          }
        }
        else {
          $result = ['result'=>'failed','insertedRow'=>null,'error'=>'floor number '.$request->input('floor_number').' already taken '];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function addSuite(Request $request) {
      $validator = validator::make($request->all(),[
        'suite_number' => 'required|numeric|uniqie:suites,SuiteNumber',
        'building_id' => 'required|numeric',
        'floor_id' => 'required|numeric',
        'rooms' => 'required',
        'rooms_ids' => 'required',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $suite = new Suite();
        $suite->SuiteNumber = $request->input('suite_number');
        $suite->BuildingId = $request->input('building_id');
        $suite->FloorId = $request->input('floor_id');
        $suite->Rooms = $request->input('rooms');
        $suite->RoomsId = $request->input('rooms_ids');
        $b = Building::find($request->input('building_id'));
        $suite->Building = $b->buildingNo;
        $f = Floor::find($request->input('floor_id'));
        $suite->Floor = $f->floorNumber;
        $suite->Status = 0;
        $suite->Hotel = 1;
        $suite->save();
        $Ids = explode( "-", $request->input('rooms_ids'));
        for ($i=0;$i<count($Ids);$i++) {
          $room = Room::find($Ids[$i]);
          $room->SuiteStatus = 1;
          $room->SuiteNumber = $request->input('suite_number');
          $room->SuiteId = $suite->id;
          $room->save();
        }
        $result = ['result'=>'success','insertedRow'=>$suite,'error'=>null];
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function deleteBuilding(Request $request) {
      $validator = validator::make($request->all(),[
        'building_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','delete'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $rooms = Room::where('building_id','=',$request->input('building_id'))->get();
        for ($i=0;$i<count($rooms);$i++) {
          $rooms[$i]->delete();
        }
        $floors = Floor::where('building_id','=',$request->input('building_id'))->get();
        for ($i=0;$i<count($floors);$i++) {
          $floors[$i]->delete();
        }
        $building = Building::find($request->input('building_id'));
        if ($building != null) {
          $building->delete();
        }
        $result = ['result'=>'success','delete'=>'building deleted','error'=>null];
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function deleteFloorAndRooms(Request $request) {
      $validator = validator::make($request->all(),[
        'floor_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','delete'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $rooms = Room::where('floor_id','=',$request->input('floor_id'))->get();
        for ($i=0;$i<count($rooms);$i++) {
          $rooms[$i]->delete();
        }
        $floor = Floor::find($request->input('floor_id'));
        if ($floor != null) {
          $building = Building::find($floor->building_id);
          $building->floorsNumber = $building->floorsNumber -1 ;
          $building->save();
          $floor->delete();
        }
        $result = ['result'=>'success','delete'=>'floor deleted','error'=>null];
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function deleteRoom(Request $request) {
      $validator = validator::make($request->all(),[
        'room_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','delete'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $room = Room::find($request->input('room_id'));
        if ($room != null) {
          $floor = Floor::find($room->floor_id);
          $floor->rooms = $floor->rooms -1;
          $floor->save();
          $room->delete();
          $result = ['result'=>'success','delete'=>'room deleted','error'=>null];
        }
        else {
          $result = ['result'=>'failed','delete'=>'delete failed ','error'=>'room id is unavailable'];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    public function deleteRoomType(Request $request) {
      $validator = validator::make($request->all(),[
        'room_type_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        $result = ['result'=>'failed','delete'=>null,'error'=>$validator->errors()];
        return $result ;
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $room = Roomtype::find($request->input('room_type_id'));
        if ($room != null) {
          $room->delete();
          $result = ['result'=>'success','delete'=>'room type deleted','error'=>null];
        }
        else {
          $result = ['result'=>'failed','delete'=>'delete failed ','error'=>'room type id is unavailable'];
        }
      }
      else {
        $result = ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      return $result ;
    }

    // ___________________________________________________________________
    static function addFloor ($building_id,$floor_number) {
      $floor = new Floor();
      $floor->building_id = $building_id;
      $floor->floorNumber = $floor_number;
      $res = $floor->save();
      return $floor ;
    }

    static function addRoom($RoomNumber,$building,$building_id,$floor,$floorId) {
      $room = new Room();
      $room->RoomNumber = $RoomNumber;
      $room->hotel = 1;
      $room->Building = $building;
      $room->building_id = $building_id;
      $room->Floor = $floor;
      $room->floor_id = $floorId;
      $room->save();

      return $room ; //Room::create(['RoomNumber'=>$RoomNumber,'hotel'=>1,'Building'=>$building,'building_id'=>$building_id,'Floor'=>$floor,'floor_id'=>$floorId]);
    }

    static function deleteFloor($id) {
      $floor = Floor::find($id);
      $res = $floor->delete();
      return $res ;
    }

    public function addRoomToFirebase(Room $room) {
      $arrRoom = [
        'RoomNumber' => $room->RoomNumber,
        'Status'=> 0,
        'hotel'=> $room->hotel,
        'Building'=> $room->Building,
        'building_id'=> $room->building_id,
        'Floor'=> $room->Floor,
        'floor_id'=> $room->floor_id,
        'RoomType'=> '',
        'SuiteStatus'=> 0,
        'SuiteNumber'=> 0,
        'SuiteId'=> 0,
        'ReservationNumber'=> 0,
        'roomStatus'=> 1,
        'Tablet'=> 0,
        'dep'=> '',
        'Cleanup'=> 0,
        'Laundry'=> 0,
        'RoomService'=> 0,
        'RoomServiceText'=> '',
        'Checkout'=> 0,
        'Restaurant'=> 0,
        'MiniBarCheck'=> 0,
        'Facility'=> '',
        'SOS'=> 0,
        'PowerSwitch'=> 0,
        'DoorSensor'=> 0,
        'MotionSensor'=> 0,
        'Thermostat'=> 0,
        'ZBGateway'=> 0,
        'CurtainSwitch'=> 0,
        'ServiceSwitch'=> 0,
        'lock'=> 0,
        'Switch1'=> 0,
        'Switch2'=> 0,
        'Switch3'=> 0,
        'Switch4'=> 0,
        'LockGateway'=> '',
        'LockName'=> '',
        'powerStatus'=> 0,
        'curtainStatus'=> 0,
        'doorStatus'=> 0,
        'DoorWarning'=> 0,
        'temp'=> 0,
        'TempSetPoint'=> 25,
        'SetPointInterval'=> 1,
        'CheckInModeTime'=> 1,
        'CheckOutModeTime'=> 1,
        'WelcomeMessage'=> 'welcome G* ',
        'Logo'=> '',
        'token'=> ''
      ];

        $response = Http::retry(3,100)->put($this->firebaseUrl.'/'.$this->projectName.'/B'.$room->Building.'/F'.$room->Floor.'/R'.$room->RoomNumber.'.json',$arrRoom)->onError(function () {
          echo 'call failed';
          $room->delete();
          $result = ['result'=>'success','insertedRow'=>null,'error'=>'error saving to firebase '.$e.getMessage()];
        });

      return $response->status();
    }

//___________________________________________________________________
    // get functions

    public function getBuildings() {
      return Building::all();
    }

    public function getFloors() {
      return Floor::all();
    }

    public function getRooms() {
      return Room::all();
    }

}
