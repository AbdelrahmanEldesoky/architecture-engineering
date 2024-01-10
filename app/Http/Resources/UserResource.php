<?php

namespace App\Http\Resources;

use App\Http\Resources\MainResource;
use App\Models\Employee\EmployeeInfo;

class UserResource extends MainResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'employee_id'          => $this->employee->id,
            'name'                 => $this->name,
            // 'name'                 => $this->name[$this->changeLang()],
            'email'             => $this->email,
            'phone'                => $this->phone,
            'image'                => !empty($this->employee->info) ? asset('/storage/' . $this->employee->attachments?->where('type', 'personal_photo')?->last()?->path): null,
            'lang'               => $this->lang,
            'active'               => $this->active,
//            'last_active'          => $this->last_active,

            // relations :
           'employee'             => new EmployeeResource($this->whenLoaded('employee')),
            'country'              => new CountryResource($this->whenLoaded('country')),

        ];
    }
}
