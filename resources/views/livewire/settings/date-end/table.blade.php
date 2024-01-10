@inject('jobType', 'App\Models\Hr\JobType')


<div class="container-fluid  my-2 ">
    <div class="row my-3 d-flex">
        <div class="col-md-12 col d-flex flex-row-reverse">
            {{-- <button class="btn btn-primary light mx-2 d-flex align-items-center" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter" aria-expanded="false" aria-controls="filter">
            <i class='bx bx-filter-alt bx-sm'></i>
            {{ __('names.filter') }}
        </button> --}}
            <button class="btn btn-primary  mx-2 d-flex align-items-center" wire:click="search">بحث</button>
            <input  type="text" wire:model.lazy="search" class="form-control "
                placeholder="{{ __('names.search') }}">
        </div>
        
    </div>


    <div class="d-flex justify-content-between mb-2">
        <h5 class="mb-2">تنبيهات تواريخ الانتهاء</h5>
        
        <a href="{{ route('admin.settings.dashboard.date_end.store') }}" class="btn btn-primary">
            {{ __('اعدادات تورايخ الانتهاء') }}
        </a>

    </div>


    <div class="row section my-2 ">
        <x-table :responsive="true">
            <thead>
                <th>
                    {{ __('اسم الموظف') }}
                </th>
                <th>
                    {{ __('الفرع') }}
                </th>
                <th>
                    {{ __('اسم الورق') }}
                </th>
                <th>
                    {{ __('تاريخ الانتهاء') }}
                </th>
                <th>
                    {{ __('تاريخ التنبيه') }}
                </th>
                <th>
                    {{ __('الحالة') }}
                </th>
                <th>
                    {{ __('الاعدادات') }}
                </th>
            </thead>
            <tbody>
                @forelse($employeeInfos as $key => $employeeInfo)
                    <tr>
                        <td>{{ $employeeInfo?->employee?->first_name }} {{ $employeeInfo?->employee?->second_name }} {{ $employeeInfo?->employee?->last_name }}</td>
                        <td>
                            {{$employeeInfo->employee?->scopeBranchName()}}
                        </td>
                        <td>
                            <div class="my-1">
                                @if( $employeeInfo->end_id_number != null && $employeeInfo->end_id_number <= $date_in_count_days)
                                        <span>
                                           بطاقة الهوية                               
                                        </span>
                                @endif
                            </div>
                                <div class="my-1">
                            @if($employeeInfo->end_medical_insurance != null && $employeeInfo->end_medical_insurance < $date_in_count_days)
                                        <span>
                                         التأمين الصحي                               
                                        </span>
                            @endif
                            </div>
                                <div class=" my-1">
                                @if( $employeeInfo->end_saudi_authority != null && $employeeInfo->end_saudi_authority < $date_in_count_days )
                                    <span>
                                        الهيئة السعودية                                
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="my-1">
                                @if( $employeeInfo->end_id_number != null && $employeeInfo->end_id_number <= $date_in_count_days)
                                        <span>
                                            {{$employeeInfo->end_id_number}}                             
                                        </span>
                                @endif
                            </div>
                                <div class="my-1">
                            @if($employeeInfo->end_medical_insurance != null && $employeeInfo->end_medical_insurance < $date_in_count_days)
                                        <span>
                                         {{$employeeInfo->end_medical_insurance}}                               
                                        </span>
                            @endif
                            </div>
                                <div class=" my-1">
                                @if( $employeeInfo->end_saudi_authority != null && $employeeInfo->end_saudi_authority < $date_in_count_days )
                                    <span>
                                        {{$employeeInfo->end_saudi_authority}}                               
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="my-1">
                                @if( $employeeInfo->end_id_number != null && $employeeInfo->end_id_number <= $date_in_count_days)
                                        <span>
                                            {{ Carbon\Carbon::parse($employeeInfo->end_id_number)->subDays($renge->renge_count)->format('Y-m-d') }}                             
                                        </span>
                                @endif
                            </div>
                                <div class="my-1">
                            @if($employeeInfo->end_medical_insurance != null && $employeeInfo->end_medical_insurance < $date_in_count_days)
                                        <span>
                                            {{ Carbon\Carbon::parse($employeeInfo->end_medical_insurance)->subDays($renge->renge_count)->format('Y-m-d') }}        
                                        </span>
                            @endif
                            </div>
                                <div class=" my-1">
                                @if( $employeeInfo->end_saudi_authority != null && $employeeInfo->end_saudi_authority < $date_in_count_days )
                                    <span>
                                        {{Carbon\Carbon::parse($employeeInfo->end_saudi_authority)->subDays($renge->renge_count)->format('Y-m-d')}}                               
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="my-1">
                            @if( ( $employeeInfo->end_id_number != null && $employeeInfo->end_id_number <= $date_in_count_days))
                                @if ($employeeInfo->end_id_number < $date_today)
                        
                                    <span class="status stopped">
                                        {{ __('منتهي') }}                                
                                    </span>
                                @else
                                    <span class="status active">
                                        {{ __('names.active') }}
                                    </span>
                                
                                @endif
                            @endif
                        </div>
                            <div class="my-1">
                        @if($employeeInfo->end_medical_insurance != null && $employeeInfo->end_medical_insurance < $date_in_count_days)
                            @if ($employeeInfo->end_medical_insurance < $date_today)
                           
                                    <span class="status stopped">
                                        {{ __('منتهي') }}                                
                                    </span>
                                @else
                                    <span class="status active">
                                        {{ __('names.active') }}
                                    </span>
                                
                                @endif
                            @endif
                        </div>
                            <div class=" my-1">
                            @if( $employeeInfo->end_saudi_authority != null && $employeeInfo->end_saudi_authority < $date_in_count_days)
                            @if ($employeeInfo->end_saudi_authority < $date_today )
                        
                                <span class="status stopped">
                                    {{ __('منتهي') }}                                
                                </span>
                            @else
                                <span class="status active">
                                    {{ __('names.active') }}
                                </span>
                            
                            @endif
                            @endif
                        </div>
                        </td>
                        <td> 
                                <div class="btn-group">
                                    <button type="button" class="btn btn-link" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bx bx-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="z-index: 999; text-align: right;">
                                        @if (havePermissionTo('employees.view'))
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.custom.create', ['employee_id' => $employeeInfo->employee?->id, 'step' => 6]) }}">
                                                    <i class="bx bx-show"></i> {{ __('names.view') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (havePermissionTo('employees.edit'))
                                            @if (havePermissionTo('employees.personal-information'))
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.custom.create', ['employee_id' => $employeeInfo->employee?->id, 'step' => '1']) }}">
                                                        <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                        {{ __('names.personal-information') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (havePermissionTo('employees.academic-info'))
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.custom.create', ['employee_id' => $employeeInfo->employee?->id, 'step' => '2']) }}">
                                                        <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                        {{ __('names.academic-info') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (havePermissionTo('employees.employment-info'))
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.custom.create', ['employee_id' => $employeeInfo->employee?->id, 'step' => '3']) }}">
                                                        <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                        {{ __('names.employment-info') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (havePermissionTo('employees.employee-finances'))
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.custom.create', ['employee_id' => $employeeInfo->employee?->id, 'step' => '4']) }}">
                                                        <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                        {{ __('names.employee-finances') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (havePermissionTo('employees.attendance'))
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.custom.create', ['employee_id' => $employeeInfo->employee?->id, 'step' => '5']) }}">
                                                        <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                        {{ __('names.attendance') }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
        
                                @if (havePermissionTo('clients.delete'))
                    
                                <a href="#" class="px-1" wire:click.prevent="delete({{ $employeeInfo?->employee?->id }})">
                                        <i class='bx bx-trash bx-sm text-danger'></i>
                                </a>
                                @endif
                         
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="">
                                <img class="" style="height: 100%" src="{{ asset('assets/images/empty.png') }}"
                                    alt="">
                            </div>
                            {{ __('message.empty', ['model' => __('names.job-types')]) }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>

    @if (havePermissionTo('jobTypes.create'))
        <livewire:job-type.modal-form modal_id="JobTypeModal" />
    @endif
</div>
