<?php

namespace App\Http\Livewire\Settings\Shifts;

use App\Models\Hr\Shift;
use App\Models\Hr\ShiftDay;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Http\Request;

class Index extends Component
{
    protected $rules = [
        'selected' => 'required',
        'distance' => 'required|numeric',
        'offline' => 'required|numeric',
        'name' => 'required|string',
        'selected.*.start' => 'required',
        'selected.*.end' => 'required',
        'late_cost' => 'required|numeric',
        'overtime_cost' => 'required|numeric',
    ];

    public $modal_id;
    public $title_in;
    public $days;
    public $times;
    public $name;
    public $distance , $offline ,$late_cost ,$overtime_cost ;
    public $selected = [];
    public $selected_times = [];
    public $shift = [];
    public $shift_id;
    public $totalTimeZones = [];
    public $timezone;
    public $title = 'add';
    public function mount($shift_id = null, Request $request) {
        $userTimeZone = timezone($request->ip());
        if($userTimeZone == "") {
            $userTimeZone = "Africa/Cairo";
        }
        if($shift_id != null) {
            $this->shift_id = $shift_id;
            $shift = Shift::findOrFail($shift_id);
            $this->name = $shift->name;
            $this->distance = $shift->distance;
            $this->offline = $shift->offline;
            $this->late_cost = $shift->late_cost;
            $this->overtime_cost = $shift->overtime_cost;

            foreach($shift->days as $day) {

                $this->selected[$day->day_name]["checked"] = true;
                $this->selected[$day->day_name]["start"] = Carbon::parse($day->start_in)->timezone($userTimeZone)->format("H:i");
                $this->selected[$day->day_name]["early_start"] = Carbon::parse($day->early_start_in)->timezone($userTimeZone)->format("H:i");
                $this->selected[$day->day_name]["late_start"] = Carbon::parse($day->late_start_in)->timezone($userTimeZone)->format("H:i");
                $this->selected[$day->day_name]["end"] = Carbon::parse($day->end_in)->timezone($userTimeZone)->format("H:i");
            }
            $this->title = 'edit';
        }

        $this->days = weekDays();
        $this->times = hourType("no");



        foreach($this->times as $key=> $time) {
//              $this->times[$key] = Carbon::parse($key)->timezone($userTimeZone)->format("h:i A");
            $this->times[$key] = Carbon::parse($key)->format("h:i A");
        }

    }

    public function render()
    {
        return view('livewire.settings.shifts.index');
    }

    public function save(Request $request) {
        $userTimeZone = timezone($request->ip());
        if($userTimeZone == "") {
            $userTimeZone = "Africa/Cairo";
        }
        if(count($this->selected) >= 1) {

            foreach($this->selected as $key=>$day) {
                if(isset($day['checked']) && $day['checked']){
                    if( !array_key_exists("start", $day) || !array_key_exists("end", $day) ||
                        !array_key_exists("early_start",
                            $day) || !array_key_exists("late_start", $day)) {
                        $this->dispatchBrowserEvent('toastr',
                            ['type' => 'error', 'message' => __('validation.required' , ['attribute' =>
                                    __('names.from-hour')]) . ' ' . __('validation.required' , ['attribute' =>
                                    __('names.to-hour')]) . ' ' . __('validation.required' , ['attribute' =>
                                    __('names.early-from')]) . ' ' . __('validation.required' , ['attribute' =>
                                    __('names.late-to')])

                            ]);

                        return 0;
                    }

                    if($day["start"] >= $day["end"]) {
                        $this->dispatchBrowserEvent('toastr',
                            ['type' => 'error',  'message' => 'Please End date must be after start date']);

                        return 0;
                    }


                    if($day["start"]  <= $day["early_start"]) {
                        $this->dispatchBrowserEvent('toastr',
                            ['type' => 'error', 'message' => 'Please Make Early Start Before Start 1']);

                        return 0;
                    }


                    if($day["start"] >= $day["late_start"]) {
                        $this->dispatchBrowserEvent('toastr',
                            ['type' => 'error', 'message' => 'Please Make Early Start Before Start 2']);

                        return 0;
                    }

                } elseif (!isset($day['checked'])){
                    unset($this->selected[$key]);
                }
            }
            if($this->distance == null) {
                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'error', 'message' => __("message.empty", ["model" => __('names.distance')])]);

                return 0;

            }
            if($this->name == null) {
                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'error',  'message' => __("message.empty", ["model" => __('names.name')])]);

                return 0;

            }
            // create Shift ;D
            if($this->shift_id) {
                $oldShift = Shift::findOrFail($this->shift_id);
                $oldShift->update([
                    "name" => $this->name,
                    "distance" => $this->distance,
                    "offline" => $this->offline,
                    "type" => 'general',
                    'late_cost' => $this->late_cost ,
                    'overtime_cost' => $this->overtime_cost ,

                ]);

                foreach($this->selected as $key=>$day) {
                    $shiftDay = ShiftDay::where(['shift_id'=>$oldShift->id, 'day_name' => $key])->first();

                    if(isset($day["checked"]) && $day["checked"] == false) {
                        $shiftDay?->delete();
                        continue;
                    }
                    $day["start"] = Carbon::parse($day["start"],$userTimeZone)->utc()->format('h:i A');
                    $day["end"] =   Carbon::parse($day["end"],$userTimeZone)->utc()->format('h:i A');
                    $day["early_start"] = Carbon::parse($day["early_start"],$userTimeZone)->utc()->format('h:i A');
                    $day["late_start"] =Carbon::parse($day["late_start"],$userTimeZone)->utc()->format('h:i A');

                    // check if day shift update times
                    if($shiftDay == null) {
                        $shiftDay = new ShiftDay([
                            'shift_id' => $oldShift->id,
                            'day_name' => $key,
                            'start_in' => $day["start"],
                            'early_start_in' => $day["early_start"],
                            'late_start_in' => $day["late_start"],
                            'end_in' =>  $day["end"],
                        ]);
                        $shiftDay->save();
                    } else {
                        $shiftDay->start_in = $day['start'];
                        $shiftDay->end_in = $day["end"];
                        $shiftDay->early_start_in = $day["early_start"];
                        $shiftDay->late_start_in = $day["late_start"];
                        $shiftDay->save();
                    }
                }

                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'success', 'message' =>__('message.saved-success')]);
            } else {
                $newShift = Shift::create([
                    "name" => $this->name,
                    "distance" => $this->distance,
                    "offline" => $this->offline,
                    "timezone" => "UTC",
                    "type" => 'general',
                    'late_cost' => $this->late_cost ,
                    'overtime_cost' => $this->overtime_cost ,

                ]);
                $newShift->save();
                foreach($this->selected as $key=>$day) {
                    $shiftDay = ShiftDay::where(['shift_id'=>$newShift->id, 'day_name' => $key])->first();

                    if(isset($day["checked"]) && $day["checked"] == false) {
                        $shiftDay?->delete();
                        continue;
                    }
                    $day["start"] = Carbon::parse($day["start"],$userTimeZone)->utc()->format('h:i A');
                    $day["end"] =   Carbon::parse($day["end"],$userTimeZone)->utc()->format('h:i A');
                    $day["early_start"] = Carbon::parse($day["early_start"],$userTimeZone)->utc()->format('h:i A');
                    $day["late_start"] =Carbon::parse($day["late_start"],$userTimeZone)->utc()->format('h:i A');

                    if($shiftDay == null) {
                        $shiftDay = new ShiftDay([
                            'shift_id' => $newShift->id,
                            'day_name' => $key,
                            'start_in' =>   $day["start"],
                            'end_in' =>  $day["end"],
                            'early_start_in' => $day["early_start"],
                            'late_start_in' => $day["late_start"],
                        ]);
                        $shiftDay->save();
                    } else {
                        $shiftDay->start_in = $day["start"];
                        $shiftDay->end_in = $day["end"];
                        $shiftDay->early_start_in = $day["early_start_in"];
                        $shiftDay->late_start_in = $day["late_start_in"];
                        $shiftDay->save();
                    }
                }
                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'success', 'message' =>__('message.saved-success')]);
            }


        } else {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'error',  'message' => __("message.empty", ["model" => __('names.day')])]);
        }

        if($this->shift_id == null) {
            $this->reset("name","distance","selected", "timezone");
        }

        return redirect()->route('admin.settings.dashboard.shifts.index')->with('success', __('message.updated', ['model' => __('names.category')]));

    }
}
