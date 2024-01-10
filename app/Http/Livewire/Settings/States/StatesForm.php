<?php

namespace App\Http\Livewire\Settings\States;

use App\Http\Livewire\Basic\BasicForm;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Livewire\Component;

class StatesForm extends BasicForm
{
    public $name, $state_id, $country_id;
    public $city, $states, $countries;

    protected $rules = [
        'country_id' => 'required|numeric|exists:countries,id',
        'name' => 'required|string',
    ];

    public function mount($id = null)
    {

        $this->countries = Country::pluck('name','id')->toArray();

    }

    public function render()
    {
        return view('livewire.settings.states.states-form');
    }

    public function updatedCountryId($value)
    {
        $this->states = State::where('country_id',$value)->pluck('name','id')->toArray();
    }

    public function save()
    {
        $validated = $this->validate();

        $country = Country::findOrFail($validated['country_id']);
        $validated['country_code'] = $country->iso2;

        State::create([
            'name'=> $validated['name'],
            'country_id'=> $validated['country_id'],
            'country_code'=> $validated['country_code']
        ]);

        return redirect()->route('admin.settings.dashboard.state.index')
            ->with('success', __('message.updated', ['model' => __('محافظة')]));
    }

}
