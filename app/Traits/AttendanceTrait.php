<?php

namespace App\Traits;

use App\Http\Livewire\Managements\Forms\Management;
use App\Models\Hr\Branch;
use App\Models\Hr\Shift;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;


trait AttendanceTrait {
     public function getEmpShift($employee) {

        $branch = $this->getEmpBranch($employee);

        if(! $branch instanceof Branch) {
            return "no having workAt 1";
        }

        if( empty($employee->shift)) {
            if( empty($branch->shift)) {
                return 0;
            } else {
                return $branch->shift;
            }
        } else {
            return $employee->shift;
        }

        return 0;
    }

    public function getEmpBranch($employee) {

        if(empty($employee->workAt)) {
            return "no having workAt";
        }

        if($employee->workAt->workable instanceof Branch) {
           return Branch::whereId($employee->workAt->workable->id)->select('latitude','longitude', 'shift_id', 'id')->with("shift")->first();
        } else if ($employee->workAt->workable instanceof Management) {
            return Branch::whereId($employee->workAt->workable->branch_id)->select('latitude','longitude', 'shift_id',
            'id')->with("shift")->first();
        } else  {
            return Branch::whereId($employee->workAt->workable->management->branch_id)->select('latitude','longitude',
            'shift_id', 'id')->with("shift")->first();
        }

        return 0;
    }


    public function getStartBefore($actualTime) {
        $canStartBefore = Setting::where('key','shift_start')->select('value')->first();
        if($canStartBefore == null) {
            Setting::create([
                'type' => 'shift_start',
                'key' => 'shift_start',
                'value' => '30',
                'group' => 'setting',
            ]);
            $canStartBefore = 30;
        } else {
            $canStartBefore = $canStartBefore->value;
        }

        return Date("h:i A", strtotime("- ".$canStartBefore ." minutes", strtotime($actualTime)));
    }

    public function getStartAfter($actualTime) {
        $canStartBefore = Setting::where('key','shift_start')->select('value')->first();
        if($canStartBefore == null) {
            Setting::create([
                'type' => 'shift_start',
                'key' => 'shift_start',
                'value' => '30',
                'group' => 'setting',
            ]);
            $canStartBefore = 30;
        } else {
            $canStartBefore = $canStartBefore->value;
        }

        return Date("h:i A", strtotime("+ ".$canStartBefore ." minutes", strtotime($actualTime)));
    }


    public function getEndBefore($actualTime) {
        $canStartAfter = Setting::where('key','shift_end')->select('value')->first();
        if($canStartAfter == null) {
            Setting::create([
                'type' => 'shift_end', // that can end attend after end shift after value mintues
                'key' => 'shift_end',
                'value' => '30',
                'group' => 'setting',
            ]);
            $canStartAfter = 30;
        }else {
            $canStartAfter = $canStartAfter->value;
        }

        return Date("h:i A", strtotime("- ".$canStartAfter ." minutes", strtotime($actualTime)));
    }


    public function getEndAfter($actualTime) {
        $canStartAfter = Setting::where('key','shift_end')->select('value')->first();
        if($canStartAfter == null) {
            Setting::create([
                'type' => 'shift_end', // that can end attend after end shift after value mintues
                'key' => 'shift_end',
                'value' => '30',
                'group' => 'setting',
            ]);
            $canStartAfter = 30;
        }else {
            $canStartAfter = $canStartAfter->value;
        }

        return Date("h:i A", strtotime("+ ".$canStartAfter ." minutes", strtotime($actualTime)));
    }

    public function getLatAndLong($employee) {
        // first of all check if employee branch have a coordinate for location else get location of branch
        $branch = $this->getEmpBranch($employee);
        $shift = $this->getEmpShift($employee);

        if (!$shift instanceof Shift) {
             return $this->errorResponse("Error In Shift", 500);
        }
        $lat = 0;
        $long = 0;
        if($shift->type != 'general') {
            $lat = $shift->latitude;
            $long = $shift->longitude;
        } else {
            $lat = $branch->latitude;
            $long = $branch->longitude;
        }

        return ['lat' => $lat, 'long' => $long];
    }

    public function getTimeBetweenTwoLocations($dist, $origin) {
    //    curl request
    //

        $response = Http::get("https://maps.googleapis.com/maps/api/directions/json?origin=".$origin."&destination=".$dist."&key=AIzaSyBfjVre0paUOf4kvUNUPTNU3omF6iV-c5Q");

        if(!empty($response['status']) &&  $response['status'] == "OK") {
            return $response['routes'][0]['legs'][0]['duration']['value'];
        } else {
            return 'Error';
        }

    }
}

?>
