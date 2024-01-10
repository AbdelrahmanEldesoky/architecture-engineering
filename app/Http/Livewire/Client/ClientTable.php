<?php

namespace App\Http\Livewire\Client;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Client;
use App\Models\Status;
use App\Services\ClientService;
use Livewire\Component;

class ClientTable extends BasicTable
{

    protected $listeners = ['confirmDelete'];
    public $selected = [];

    public function search(){
        $service = new ClientService();
        $clients = $service->search($this->search)
                ->with('broker','status','branch')
                ->when($this->start_date,function ($query){
                    $query->where('created_at','>=',$this->start_date);
                })
                ->when($this->end_date,function ($query){
                    $query->where('created_at','<=',$this->end_date);
                })
                ->when( !empty($this->status_id)   ,function ($query){
                    $query->where('status_id','>=',$this->status_id);
                })
                ->select(['id','name','phone','email','card_id','branch_id','broker_id','status_id'])
                ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
                ->paginate($this->perPage);

        return $clients;    
    }
    public function render()
    {
        $clients = $this->search();
        $statuses = Status::where('type','client')->get(['id','name']);

        return view('livewire.client.client-table',[
            'clients' => $clients,
            'statuses' => $statuses
         ]);
    }

    public function edit()
    {
        $this->emitTo('client.client-edit','clientEditModal');
    }


    public function deleteSelected()
    {
    // Perform the deletion action
    $clients = Client::withCount('projects')->whereIn('id', $this->selected)->get();

    foreach ($clients as $client) {
        if ($client->projects_count > 0) {
            $this->dispatchBrowserEvent('toastr', [
                'type' => 'error',
                'message' => __('message.still-has', ['model' => __('names.client'), 'relation' => __('names.projects')])
            ]);
        } else {
            $client->delete();
        }
    }

    $this->dispatchBrowserEvent('toastr', [
        'type' => 'success',
        'message' => __('message.deleted', ['model' => __('names.client')])
    ]);

    // Clear the selected array
    $this->selected = [];

    // Refresh the component to reflect the updated data
    $this->emit('$refresh');
    }

}