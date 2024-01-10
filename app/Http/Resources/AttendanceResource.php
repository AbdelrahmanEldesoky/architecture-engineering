<?php

namespace App\Http\Resources;

use App\Http\Resources\MainResource;
use Carbon\Carbon;
class AttendanceResource extends MainResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $timezone = timezone($request->ip());

        if($timezone == "") {
            $timezone = "Africa/Cairo";
        }
        $hourlyValue = $this->employee?->finance?->hourly_value ?? 0;
        $workHours = ! empty($this->check_out) ? abs((strtotime($this->check_out) -
        strtotime($this->check_in)) /3600 ) : 0;
        $day = Carbon::now()->format('D');
        $shiftDay=$this?->shift?->days()?->where("day_name" , $day)?->first();
        $sum=$this->report?->total;
        if($shiftDay)
        {

            if($this->report != null)
            {
               $totalPerHour= $this->report->total /Carbon::parse($shiftDay->start_in)->diffInHours($shiftDay->end_in);
               $sum=$totalPerHour*Carbon::parse($shiftDay->start_in)->diffInHours(Carbon::now($timezone));
            }
            if($this->check_out != null )
            {
                $sum= $this->report->total ;
            }

        }





        return [
            'check_in' => $this->check_in != null ? Carbon::parse($this->check_in)->timezone($timezone)->format('h:i A') : null,
            'check_out'=> $this->check_out != null ? Carbon::parse($this->check_out)->timezone($timezone)->format('h:i A') : null,
            'overtime' => 0,//what this is ??????
            'fees' => $this->report != null ?$sum  . ' ' . __('names.'.$this->employee?->finance?->currency?->code):'0',
            'hourly_value' => round($hourlyValue, 2) . ' ' . __('names.'.$this->employee?->finance?->currency?->code),
            'date' => $this->created_at != null ? Carbon::parse($this->created_at)->timezone($timezone)->format('d-m-Y') : null,
        ];
    }
}
