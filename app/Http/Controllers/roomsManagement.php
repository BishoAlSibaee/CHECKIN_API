<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Suite;
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
      if (Users::checkAuth($request->input('my_token')) == false) {
        return ['result'=>'failed','insertedRow'=>null,'error'=>'you are un authorized user'];
      }
      $bid = $request->input('building_number');
      $bname = $request->input('building_name');
      $fnum = $request->input('floors_number');
      $pars = [$bid,$bname,$fnum];
      DB::beginTransaction();
      try{
            $building = new Building();
            $building->projectId = 1;
            $building->buildingNo = $bid;
            $building->buildingName = $bname;
            $building->floorsNumber = $fnum;
            $floors = array();
            if ($building->save()) {
              for ($i=0;$i<$fnum;$i++) {
                $fl = new Floor();
                $fl->building_id = $building->id;
                $fl->floorNumber = $i+1;
                $fl->rooms = 0;
                $fl->save();
                array_push($floors,$fl);
              }
              $this->addBuildingToFirebase($building,$floors);
              DB::commit();
              return ['result'=>'success','insertedRow'=>$building,'error'=>null];
            }
      }
      catch(Exception $e) {
        DB::rollback();
        return ['result'=>'failed','insertedRow'=>null,'error'=>'error '.$e->getMessage()];
      }
      
    }

    public function addFloorRooms(Request $request) {
      $validator = validator::make($request->all(),[
        'BuildingId' => 'required|numeric',
        'FloorId'=> 'required|numeric',
        'Rooms'=> 'required|numeric',
        'start'=> 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','insertedRow'=>'','error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token')) == false) { 
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
        $building_id = $request->input('BuildingId');
        $startRoom = $request->input('start');
        $roomsNum = $request->input('Rooms');
        $floorId = $request->input('FloorId');
        $insertedRooms = array();
        $F = Floor::find($floorId);
        if ($F == null ) {
          return ['result'=>'failed','insertedRow'=>'','error'=>'this floor id '.$floorId.' is unavailable'];
        }
        $B = Building::find($building_id);
        if ($B == null) {
          return ['result'=>'failed','insertedRow'=>'','error'=>'this building id '.$building_id.' is unavailable'];
        }
        if ($F->building_id != $building_id) {
          return ['result'=>'failed','insertedRow'=>'','error'=>'building id '.$building_id.' does not match floor '.$floor.' !.. floor '.$floor.' building id is '.$F->building_id];
        }
        $rrr = Room::where('building_id',$B->id)->where('RoomNumber','=',$startRoom)->first();
        if ($rrr != null) { 
          return ['result'=>'failed','insertedRow'=>'','error'=>'room number '.$startRoom.' already taken '];
        }
        DB::beginTransaction();
        try{
              for ($i=0 ; $i < $roomsNum ; $i++) {
                $RoomNumber = $startRoom + $i ;
                $insertedRooms[$i] = roomsManagement::addRoom($RoomNumber,$B->buildingNo,$B->id,$F->floorNumber,$F->id);
                if ($insertedRooms[$i] != null) {
                  $this->addRoomToFirebase($insertedRooms[$i]);
                }
              }
              $floor = Floor::find($floorId);
              $floor->rooms = $floor->rooms + $roomsNum ;
              $floor->save();
              DB::commit();
              return ['result'=>'success','insertedRow'=>$insertedRooms,'error'=>''];
          }
          catch(Exception $e) {
            DB::rollback();
            return ['result'=>'failed','insertedRow'=>'','error'=>'error '.$e->getMessage()];
          }
    }

    public function addOneRoom(Request $request) {
      $validator = validator::make($request->all(),[
        'room_number' => 'required|numeric',
        'building_id' => 'required|numeric',
        'floor_id'=> 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','insertedRow'=>'','error'=>$validator->errors()];
      }
      $b = Building::find($request->input('building_id'));
      if ($b == null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'buiiding id '.$request->input('building_id').' is unavalable'];
      }
      $f = Floor::find($request->input('floor_id'));
      if ($f == null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'floor id '.$request->input('floor_id').' is unavalable'];
      }
      if ($f->building_id != $b->id) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'floor id '.$f->id.' is not related to building id '.$b->id];
      }
      $room = Room::where('RoomNumber',$request->input('room_number'))->first();
      if ($room != null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'room number '.$request->input('room_number').' is already exists in building number '.$room->Building];
      }
      DB::beginTransaction();
      try {
          $room = new Room();
          $room->RoomNumber = $request->input('room_number');
          $room->hotel = 1;
          $room->Building = $b->buildingNo;
          $room->building_id = $b->id;
          $room->Floor = $f->floorNumber;
          $room->floor_id = $f->id;
          $room->save();
          $f->rooms = $f->rooms +1;
          $f->save();
          DB::commit();
          $this->addRoomToFirebase($room);
          return ['result'=>'success','insertedRow'=>$room,'error'=>''];
        }
        catch(Exeption $e){
          DB::rollBack();
          return ['result'=>'failed','insertedRow'=>'','error'=>'error '.$e->getMessage()];
        }
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
        return ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token')) == false) { 
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
      $b = Building::find($request->input('building_id'));
      if ($b == null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'building id '.$request->input('building_id').' is unavailable'];
      }
      $f = Floor::where('floorNumber',$request->input('floor_number'))->where('building_id',$b->id)->first();
      if ($f != null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'floor number '.$f->floorNumber.' is already exists in building '.$b->buildingNo];
      }
      $startRoom = Room::where('building_id',$b->id)->where('RoomNumber',$request->input('start'))->first();
      if ($startRoom != null ) { 
        return ['result'=>'failed','insertedRow'=>'','error'=>'room number '.$request->input('start').' already taken '];
      }
      if ($request->input('rooms') > 99) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'rooms must be less than 100'];
      }
      DB::beginTransaction();
        try{
          $floor = new Floor() ;
          $floor->building_id = $request->input('building_id');
          $floor->floorNumber = $request->input('floor_number');
          $floor->rooms = $request->input('rooms');
          $floor->save();
          $building = Building::find($floor->building_id);
          $building->floorsNumber = $building->floorsNumber + 1 ;
          $building->save();
          for ($i=0;$i < $floor->rooms;$i++) {
            $RoomNumber = $request->input('start') + $i ;
            $room = roomsManagement::addRoom($RoomNumber,$building->buildingNo,$building->id,$floor->floorNumber,$floor->id);
            if ($room != null) {
              $this->addRoomToFirebase($room);
            }
          }
          DB::commit();
          return ['result'=>'success','insertedRow'=>$floor,'error'=>null];
        }
        catch(Exeption $e){
          DB::rollback();
          return ['result'=>'failed','insertedRow'=>'','error'=>'error '.$e->getMessage()];
        }
    }

    public function addSuite(Request $request) {
      $validator = validator::make($request->all(),[
        'suite_number' => 'required|numeric|unique:suites,SuiteNumber',
        'building_id' => 'required|numeric',
        'floor_id' => 'required|numeric',
        'room_ids' => 'required',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','insertedRow'=>null,'error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token')) == false) { 
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
      $b = Building::find($request->input('building_id'));
      if($b == null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'building id '.$request->input('building_id').'is unavailable'];
      }
      $f = Floor::find($request->input('floor_id'));
      if($f == null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'floor id '.$request->input('floor_id').'is unavailable'];
      }
      if($f->building_id != $b->id) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'floor id '.$f->id.' does not exists in building id '.$b->id];
      }
      $Ids = explode( "-", $request->input('room_ids'));
      if ($Ids == null || count($Ids) == 0) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'rooms ids is unavailable'];
      }
      $suite = $this->addSuiteToDBs($b,$f,count($Ids),$request->input('room_ids'),$request->input('suite_number'));
      if ($suite != null) {
        for ($i=0;$i<count($Ids);$i++) {
          $room = Room::find($Ids[$i]);
          if ($room != null) {
            $this->convertRoomToSuiteRoom($suite,$room);
          }
        }
        return ['result'=>'success','insertedRow'=>$suite,'error'=>''];
      }
      return ['result'=>'failed','insertedRow'=>'','error'=>'unable to save suite in DB '];
      
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
      if (Users::checkAuth($request->input('my_token')) == false) {
        return ['result'=>'failed','delete'=>'','error'=>'you are un authorized user'];
       }
       $building = Building::find($request->input('building_id'));
       if ($building == null) {
        return ['result'=>'failed','delete'=>null,'error'=>'building id '.$request->input('building_id').' is unavailable'];
       }
       DB::beginTransaction();
       try {
        $rooms = Room::where('building_id','=',$building->id)->get();
        if ($rooms != null && count($rooms) >0) {
          for ($i=0;$i<count($rooms);$i++) {
            $rooms[$i]->delete();
          }
        }
        $suites = Suite::where('BuildingId',$building->id)->get();
        if ($suites != null && count($suites) > 0) {
          foreach($suites as $suite) {
            $suite->delete();
          }
        }
        $floors = Floor::where('building_id','=',$building->id)->get();
        for ($i=0;$i<count($floors);$i++) {
          $floors[$i]->delete();
        }
        $building->delete();
        DB::commit();
        $response = Http::delete($this->firebaseUrl.'/'.$this->projectName.'/B'.$building->buildingNo.'.json');
        return ['result'=>'success','delete'=>'building deleted','error'=>''];
       }
       catch(Exception $e) {
        DB::rollBack();
         return ['result'=>'failed','delete'=>'','error'=>$e->getMessage()];
       }
      
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
      if (Users::checkAuth($request->input('my_token')) == false) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
      $floor = Floor::find($request->input('floor_id'));
      if ($floor == null) {
        return ['result'=>'failed','delete'=>null,'error'=>'floor id '.$request->input('floor_id').' is unavailable'];
      }
      DB::beginTransaction();
      try {
        $rooms = Room::where('floor_id','=',$floor->id)->get();
        if ($rooms != null && count($rooms) >0) {
          foreach($rooms as $room) {
            $room->delete();
          }
        }
        $suites = Suite::where('FloorId',$floor->id)->get();
        if ($suites != null && count($suites) > 0) {
          foreach($suites as $suite) {
            $suite->delete();
          }
        }
        $building = Building::find($floor->building_id);
        $building->floorsNumber = $building->floorsNumber -1 ;
        $building->save();
        $floor->delete();
        DB::commit();
        $response = Http::delete($this->firebaseUrl.'/'.$this->projectName.'/B'.$building->buildingNo.'/F'.$floor->floorNumber.'.json');
        return ['result'=>'success','delete'=>'floor deleted','error'=>''];
      }
      catch(Exception $e) {
        DB::rollBack();
        return ['result'=>'failed','delete'=>'','error'=>$e->getMessage()];
      }
      
    }

    public function deleteRoom(Request $request) {
      $validator = validator::make($request->all(),[
        'room_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','delete'=>'','error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token')) == false) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
      $room = Room::find($request->input('room_id'));
      if ($room == null) {
        return ['result'=>'failed','delete'=>'delete failed ','error'=>'room id is unavailable'];
      }
       DB::beginTransaction();
       try {
        $floor = Floor::find($room->floor_id);
        $floor->rooms = $floor->rooms -1;
        $floor->save();
        $room->delete();
        DB::commit();
        $response = Http::delete($this->firebaseUrl.'/'.$this->projectName.'/B'.$building->buildingNo.'/F'.$floor->floorNumber.'/R'.$room->RoomNumber.'.json');
        return ['result'=>'success','delete'=>'room deleted','error'=>''];
       }
       catch(Exception $e) {
        DB::rollBack();
        return ['result'=>'failed','insertedRow'=>'','error'=>$e->getMessage()];
       }
    }

    public function deleteRoomType(Request $request) {
      $validator = validator::make($request->all(),[
        'room_type_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','delete'=>'','error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token'))) {
        $room = Roomtype::find($request->input('room_type_id'));
        if ($room != null) {
          $room->delete();
          return ['result'=>'success','delete'=>'room type deleted','error'=>''];
        }
        else {
          return ['result'=>'failed','delete'=>'delete failed ','error'=>'room type id is unavailable'];
        }
      }
      else {
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
    }

    public function deleteSuite(Request $request) {
      $validator = validator::make($request->all(),[
        'suite_id' => 'required|numeric',
        'my_token'=> 'required'
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','delete'=>'','error'=>$validator->errors()];
      }
      if (Users::checkAuth($request->input('my_token')) == false) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'you are un authorized user'];
      }
      $suite = Suite::find($request->input('suite_id'));
      if ($suite == null) {
        return ['result'=>'failed','insertedRow'=>'','error'=>'suite id '.$request->input('suite_id').' is unavailable'];
      }
      DB::beginTransaction();
       try {
        $rooms = Room::where('SuiteId',$suite->id)->get();
        if ($rooms != null && count($rooms) > 0) {
          foreach($rooms as $room) {
            $room->SuiteId = 0 ;
            $room->SuiteStatus = 0 ;
            $room->SuiteNumber = 0 ;
            $room->save();
            $roomArr = [
              'SuiteId' => 0,
              'SuiteStatus' => 0,
              'SuiteNumber' => 0
            ];
            $resp = Http::patch($this->firebaseUrl.'/'.$this->projectName.'/B'.$room->Building.'/F'.$room->Floor.'/R'.$room->RoomNumber.'.json',$roomArr);
          }
        }
        $suite->delete();
        DB::commit();
        $response = Http::delete($this->firebaseUrl.'/'.$this->projectName.'/B'.$suite->Building.'/F'.$suite->Floor.'/S'.$suite->SuiteNumber.'.json');
        return ['result'=>'success','delete'=>'suite deleted','error'=>''];
       }
       catch(Exception $e) {
        DB::rollBack();
        return ['result'=>'failed','insertedRow'=>'','error'=>$e->getMessage()];
       }
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
      if ($room->save()) {
        return $room;
      }
      return null ;
    }

    static function deleteFloor($id) {
      $floor = Floor::find($id);
      $res = $floor->delete();
      return $res ;
    }

    public function addRoomToFirebase(Room $room) {
      $arrRoom = [
        'id' => $room->id,
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
        'DND'=>0,
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
      $response = Http::retry(3,100)->put($this->firebaseUrl.'/'.$this->projectName.'/B'.$room->Building.'/F'.$room->Floor.'/R'.$room->RoomNumber.'.json',$arrRoom);
      return $response->successful();
    }

    function addBuildingToFirebase(Building $b ,array $floors) {
        $bArr = $this->putFloorsOfBuilding($floors);
        $resp = Http::retry(3,100)->put($this->firebaseUrl.'/'.$this->projectName.'/B'.$b->buildingNo.'.json',$bArr);
        return $resp->successful();
    }

    function addFloorToFirebase(Floor $f) {
        $building = Building::find($f->building_id);
        if ($building != null) {
          $fArr = $this->copyFloorToArray($f);
          $response = Http::retry(3,100)->put($this->firebaseUrl.'/'.$this->projectName.'/B'.$building->buildingNo.'/F'.$f->floorNumber.'.json',$fArr);
          return $response->successful();
        }
        return null;
    }

    function addSuiteToDBs(Building $b,Floor $f,String $rooms,String $roomsIds,int $suiteNumber) {
      $suite = new Suite();
      $suite->SuiteNumber = $suiteNumber;
      $suite->BuildingId = $b->id;
      $suite->FloorId = $f->id;
      $suite->Rooms = $rooms;
      $suite->RoomsId = $roomsIds;
      $suite->Building = $b->buildingNo;
      $suite->Floor = $f->floorNumber;
      $suite->Status = 0;
      $suite->Hotel = 1;
      if ($suite->save()) {
        $arrRoom = [
          'id' => $suite->id,
          'SuiteNumber' => $suite->SuiteNumber,
          'Rooms'=> $suite->Rooms,
          'RoomsId'=> $suite->RoomsId,
          'Hotel'=> 1,
          'Building'=> $suite->Building,
          'BuildingId'=> $suite->BuildingId,
          'FloorId'=> $suite->FloorId,
          'Floor'=> $suite->Floor,
          'Status'=> $suite->Status
        ];
        $response = Http::retry(3,100)->put($this->firebaseUrl.'/'.$this->projectName.'/B'.$suite->Building.'/F'.$suite->Floor.'/S'.$suite->SuiteNumber.'.json',$arrRoom);
        return $suite;
      }
      return false ;
    }

    function copyBuildingToArray(Building $b) {
      return $bArr = [
        'id' => $b->id,
        'projectId' => $b->projectId,
        'buildingNo' => $b->buildingNo,
        'buildingName' => $b->buildingName,
        'floorsNumber' => $b->floorsNumber
      ];
    }

    function copyFloorToArray(Floor $f) {
      return $fArr = [
        'id' => $f->id ,
        'building_id' => $f->building_id,
        'floorNumber' => $f->floorNumber,
        'rooms' => $f->rooms
      ];
    }

    function putFloorsOfBuilding(array $floors) {
      $arr = array();
      $i=0;
      foreach($floors as $fl) {
        $i++;
        $arr['F'.($i)] = '';
      }
      return $arr;
    }

    function convertRoomToSuiteRoom(Suite $suite,Room $room) {
        $room->SuiteStatus = 1;
        $room->SuiteNumber = $suite->SuiteNumber;
        $room->SuiteId = $suite->id;
        if ($room->save()) {
          $arrRoom = [
            'SuiteStatus'=> 1,
            'SuiteNumber'=> $suite->SuiteNumber,
            'SuiteId'=> $suite->id
          ];
          $response = Http::retry(3,100)->patch($this->firebaseUrl.'/'.$this->projectName.'/B'.$room->Building.'/F'.$room->Floor.'/R'.$room->RoomNumber.'.json',$arrRoom);
          return $response->successful();
        }
        else {
          return false;
        }
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

    public function getFloorRooms(Request $request) {
      $validator = validator::make($request->all(),[
        'floor_id' => 'required|numeric',
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','rooms'=>'','error'=>$validator->errors()];
      }
      $floor = Floor::find($request->input('floor_id'));
      if ($floor == null) {
        return ['result'=>'failed','rooms'=>'','error'=>'floor id '.$request->input('floor_id').' is unavailable'];
      }
      $rooms = Room::where('floor_id',$request->input('floor_id'))->get();
      if ($rooms == null || count($rooms) == 0) {
        return ['result'=>'failed','rooms'=>'','error'=>'no rooms registered in floor number '.$floor->floorNumber];
      }
      return ['result' => 'success','rooms'=>$rooms,'error'=>''];
    }

    public function getBuildingFloors(Request $request) {
      $validator = validator::make($request->all(),[
        'building_id' => 'required|numeric',
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','floors'=>'','error'=>$validator->errors()];
      }
      $building = Building::find($request->input('building_id'));
      if ($building == null) {
        return ['result'=>'failed','floors'=>'','error'=>'building id '.$request->input('building_id').' is unavailable'];
      }
      $floors = Floor::where('building_id',$building->id)->get();
      if ($floors == null || count($floors) == 0) {
        return ['result'=>'failed','floors'=>'','error'=>'no floors registered in building number '.$building->buildingNo];
      }
      return ['result' => 'success','floors'=>$floors,'error'=>''];
    }

    public function getBuildingRooms(Request $request) {
      $validator = validator::make($request->all(),[
        'building_id' => 'required|numeric',
      ]);
      if ($validator->fails())  {
        return ['result'=>'failed','rooms'=>'','error'=>$validator->errors()];
      }
      $building = Building::find($request->input('building_id'));
      if ($building == null) {
        return ['result'=>'failed','rooms'=>'','error'=>'building id '.$request->input('building_id').' is unavailable'];
      }
      $rooms = Room::where('building_id',$building->id)->get();
      if ($rooms == null || count($rooms) == 0) {
        return ['result'=>'failed','rooms'=>'','error'=>'no floors registered in building number '.$building->buildingNo];
      }
      return ['result' => 'success','rooms'=>$rooms,'error'=>''];
    }

}
