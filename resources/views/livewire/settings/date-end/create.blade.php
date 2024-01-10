<div class="row">
    <div class="col-lg-6">
        <h4>اعدادات تنبيهات تواريخ الانتهاء</h4>

        <div class="section row ">

            <div class="form-group mb-2 col-md-6 @error('renge_count') is-invalid @enderror">
                <x-input-label :value="__('مدة التنبيه قبل تاريخ الانتهاء')" :required="true"></x-input-label>
                <select class="form-select" wire:model="renge_count">
                   @foreach ($RengeDates as $RengeDate)
                    <option value="{{$RengeDate->count}}" @if($RengeDate->count == $range->renge_count ) selected @endif > {{$RengeDate->name}}</option>
                   @endforeach
                </select>
                @error('renge_count')
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
