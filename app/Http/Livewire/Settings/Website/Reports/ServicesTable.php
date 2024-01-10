<?php

namespace App\Http\Livewire\Settings\Website\Reports;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\CMS\Service;
use App\Models\CMS\ServiceRequest;
use App\Services\CMS\ServiceRequestService;
use Livewire\Component;

class ServicesTable extends BasicTable
{
    public function render()
    {
        $service = new ServiceRequestService();
//        dd(ServiceRequest::with('service')->get());
        return view('livewire.settings.website.reports.services-table', [
            'servicesRequests' => $service->search($this->search)
//                ->confirmed()
                    ->with(['status:id,name,color','service:id,name'])
                ->when($this->start_date, function ($query) {
                    $query->where('created_at', '>=', $this->start_date);
                })
                ->when($this->end_date, function ($query) {
                    $query->where('created_at', '<=', $this->end_date);
                })
//                ->when( !empty($this->status_id)   ,function ($query){
//                    $query->where('status_id','>=',$this->status_id);
//                })
//                ->select(['id','title'])
                ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
                ->paginate($this->perPage)
        ]);
    }
}
