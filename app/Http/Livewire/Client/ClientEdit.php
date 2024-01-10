<?php

namespace App\Http\Livewire\Client;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Broker;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientEdit extends BasicTable
{


    public $name ,$type = 'individual' ,$card_id ,$card_image,$card_image_path,$company_name ,$agent_name, $register_number,$register_image ,$register_image_path
    ,$phone,$email , $branch_id , $broker_id , $letter_head;
    public $client ;

    public  function search()
    {

        $client = Client::when( !empty($this->phone)   ,function ($query){
                            $query->where('name', 'like','%'.$this->name.'%');
                        })
                        ->when( !empty($this->phone)   ,function ($query){
                                $query->where('phone',$this->phone);
                        })
                        ->when( !empty($this->branch_id)   ,function ($query){
                            $query->where('branch_id',$this->branch_id);
                        })
                        ->when( !empty($this->broker_id)   ,function ($query){
                            $query->where('broker_id',$this->broker_id);
                        })
                        ->first();
        
        // $broker_id = Broker::where('')->                
                        
        if((empty($this->phone)&&empty($this->name)&&empty($this->branch_id) && empty($this->broker_id) )|| $client ==null ){
            return redirect()->back();
        }

        return redirect()->route('admin.clients.edit',$client->id);
    }


    public function render()
    {
        $service = new ClientService();
        $clients = $service->getClients();
        $branches = $service->getBranches();
        $brokers = Broker::pluck('name', 'id')->toArray();
        
        return view('livewire.client.client-edit',compact('clients','branches','brokers'));
    }


}
