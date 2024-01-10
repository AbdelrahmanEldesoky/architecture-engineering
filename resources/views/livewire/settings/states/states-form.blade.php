<div class="row">
    <div class="col-lg-8">
        <h3>{{ __('message.add',['model'=>__('محاظفة')]) }}</h3>

        <div class="section row">
            <div class="form-group col-md-6 mb-2 @error('country_id') is-invalid @enderror">
                <x-input-label :value="__('names.country')" :required="true"></x-input-label>
                <x-select :options="$countries" model="country_id" placeholder="country" id="branch"
                               class="country-select "
                               name="country_id">
                </x-select>
                @error('country_id')
                <div class="message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group col-md-6 mb-2   @error('name') is-invalid @enderror ">
                <x-input-label value="المحافظة"  :required="true"></x-input-label>
                <x-text-input wire:model.lazy="name" :required="false" name="name"
                              placeholder="{{ __('المحافظة') }}"></x-text-input>
                @error('name')
                <div class="message">{{ $message }}</div>
                @enderror
            </div>

            <br>
            <button type="button" class="btn btn-primary w-100"
                    wire:click.prevent="save()">
                {{ __('names.'.$button) }}
            </button>
        </div>

    </div>
    <div class="col-lg-4">

    </div>
</div>
