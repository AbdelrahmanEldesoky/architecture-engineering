<div>
    <form action="#" wire:submit.prevent.save="save">
        @csrf
        <div class="row mt-4">
            <div class="col-md-12 mb-2">
                <h4>
                    {{ __('names.attendance') }}
                </h4>
            </div>

            <div class="col-md-4 form-group mb-2">
                <x-input-label :value="__('names.branch') . ' ' . __('names.main')"></x-input-label>
                <select class="form-select" wire:model.lazy="employeeBranchId" disabled readonly>
                    <option>
                        {{ __('names.select') }}
                    </option>
                    @foreach ($mainBranches as $key => $branch)
                        <option value="{{ $key }}">
                            {{ $branch }}
                        </option>
                    @endforeach
                </select>

                <br />

                <div class="d-block">
                    {{-- <div class="form-check form-switch">
                        <input wire:model.lazy="ableToChange" class="form-check-input" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" checked="">
                    </div> --}}
                    <select class="form-select  d-inline" wire:model.lazy="shift_id"
                        {{ $ableToChange ? '' : 'disabled' }}>
                        <option value="">
                            {{ __('names.select') }}
                        </option>
                        @foreach ($shifts as $key => $shift)
                            <option value="{{ $key }}">
                                {{ $shift }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-2 my-5 text-end">
                  <h4>
                    {{ __('حساب وقت اضافي') }}
                  </h4>
                </div>
              
                <div class="col-md-3 mb-2 my-5 text-start">
                  <h4>
                    {{ __('دور الموظف') }}
                  </h4>
                </div>
              </div>

              <div class="row  ">
                <div class="col-md-3 offset-md-5 ">
                  <select class="form-select" wire:model.lazy="has_overtime">
                    <option value="0" @if($has_overtime==0) selected @endif>غير نشط</option>
                    <option value="1" @if($has_overtime==1) selected @endif>نشط</option>
                  </select>
                </div>
              
                <div class="col-md-3 ">
                    <select required class="form-control form-select" wire:model.lazy="auth.role_id">
                      <option value="">
                        {{ __('names.select', ['model' => __('names.role')]) }}
                      </option>
                      @foreach ($roles as $key => $role)
                        <option value="{{ $key }}">
                          {{ $role }}
                        </option>
                      @endforeach
                    </select>
                </div>
              </div>

            <div class="col-md-4 mb-2">

            </div>
            
            <hr class="my-5">
            <div class="col-md-12  mb-2 mt-3 d-flex justify-content-center ">
                <section class="section ">
                    <div class="row justify-content-center">
                            <h5 class='text-center'>
                                {{ __('names.login') }}
                            </h5>
                        <div class="col-md-5">
                            <x-input-label :value="__('names.username')"></x-input-label><span style="color: red"> * </span>
                            <input  type="text" autocomplete="off" class="form-control"
                                wire:model.lazy="auth.username" />
                        </div>

                        <div class="col-md-5">
                            <x-input-label :value="__('names.password')"></x-input-label><span style="color: red"> * </span>
                            <i class="bx bx-sm bx-low-vision" style="float:left" wire:click="changeTextType"></i>
                            <input  type="{{ $inputType }}" autocomplete="off" class="form-control"
                                wire:model.lazy="auth.password" />
                        </div>
                    </div>
                </section>
            </div>
            <hr class=" my-5">

            <div class="col-md-12">
                <div class="row justify-content-end  ">
                    <div class="col-1">
                        <a href="{{ route('admin.custom.create', ['employee_id' => $employee_id, 'step' => 4]) }}"
                            class="btn btn-outline-{{ $color }} w-100">
                            {{ __('names.prev') }}
                        </a>
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn-{{ $color }} w-100">
                            {{ __('names.next') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
