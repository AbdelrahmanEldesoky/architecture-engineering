<div wire:ignore.self class="modal fade" id="clientEditModal" tabindex="-1" aria-labelledby="clientEditModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-body">     
                <div class="left-align" style="position: absolute; top:5px; left:5px">
                    <a href="#" data-bs-dismiss="modal">
                        <i class='bx bx-x-circle bx-md'></i>
                    </a>
                </div>
                <div class="row my-3 d-flex justify-content-center align-items-center">
                <h5 class="modal-title text-center w-100" id="clientEditModalLabel">{{  __('بحث عن عميل') }}</h5>
                </div>
                <form method="POST" action="#"  enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mb-2">
                        <label  class=" col-form-label text-md-right">{{ __('نوع العميل') }}</label>
                        <div class="radio-control mt-2">
                            <input type="radio" name="type" wire:model="type" value="individual"/>
                            {{ __('names.individual') }}
                            <input type="radio" name="type" wire:model="type" value="company" class="mr-4"/>
                            {{ __('names.company') }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="name" class=" col-form-label text-md-right">{{ __('اسم العميل') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   wire:model.lazy="name" autocomplete="name" placeholder="اسم العميل">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class=" col-form-label text-md-right">{{ __('رقم الجوال') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   wire:model.lazy="phone" autocomplete="phone" placeholder="رقم الجوال">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row my-3">
                        <div class="col-md-6 @error('branch_id') is-invalid @enderror">
                            <x-input-label :value="__('Branch')" ></x-input-label>
                            <x-select :options="$branches" model="branch_id" id="branch" class="country-select"
                                      name="branch_id" >
                            </x-select>
                            @error('branch_id')
                            <div class="message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="broker_id">{{ __('اسم الوسيط') }}</label>
                            <input type="text" class="form-control @error('broker_id') is-invalid @enderror"
                                   wire:model.lazy="broker_id" autocomplete="broker_id" placeholder="اسم الوسيط">
                            @error('broker_id')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                   

                    <div class="row my-3 d-flex justify-content-center align-items-center">
                        <button class="btn btn-primary col-md-4" wire:click="search">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>