<?php

namespace App\Http\Livewire\Settings\DateEnd;

use App\Http\Livewire\Basic\BasicForm;
use App\Models\City;
use App\Models\Country;
use App\Models\Renge;
use App\Models\RengeDate;
use App\Models\State;
use Livewire\Component;

class Form extends BasicForm
{
    public  $range_id;
    public  $range , $renge_count;
    public $RengeDates;

    protected $rules = [
        'renge_count' => 'required|string',
    ];

    public function mount($id = null)
    {
        $this->range = Renge::findOrFail(1);
        $this->renge_count = $this->range->renge_count;
        $this->RengeDates = RengeDate::get();
    }

    public function render()
    {
        return view('livewire.settings.date-end.create');
    }

    public function updatedCountryId($value)
    {
        $this->states = Renge::where('id',1)->pluck('renge_count','id')->toArray();
    }

    public function save()
    {
        $validated = $this->validate();

        $renge = Renge::findOrFail(1);
        $renge->update([
            'renge_count' => $validated['renge_count']
        ]);
        
        return redirect()->route('admin.settings.dashboard.date-end.index')
            ->with('success', __('message.updated', ['model' => __('محافظة')]));
    }

}
