<div>
    <h3 class="d-print-none">
        {{ __('names.employees-report') }}
    </h3>
    <div class="row d-print-none">
        <div class="col-md-3 form-group mb-4">
            <x-input-label :value="__('names.from')" class="mb-2"></x-input-label>
            <input type="date" wire:model.lazy="fromDate" class="form-control"/>
        </div>
        <div class="col-md-3 form-group mb-4">
            <x-input-label :value="__('names.to')" class="mb-2"></x-input-label>
            <input type="date" class="form-control" wire:model.lazy="toDate"/>
        </div>

        <div class="col-md-6 float-left">
            <button class="btn btn-primary mx-2 light  d-inline-flex align-items-center mt-4" type="button"
                    data-bs-toggle="collapse" data-bs-target="#filterWithBranch" aria-expanded="false"
                    aria-controls="filter">
                <i class='bx bx-filter-alt bx-sm'></i>
                {{ __('names.filter') }}
            </button>
            <button class="btn btn-primary  d-inline-flex align-items-center mt-4" onclick="window.print()">
                {{ __('names.print') }}
            </button>
        </div>
    </div>
    <div wire:ignore.self class="row d-print-none  collapse" id="filterWithBranch">
        <div class="col-md-3 form-group mb-4">
            <x-input-label :value="__('names.branch')" class="mb-2"></x-input-label>
            <select class="form-select" wire:model.lazy="branchId">
                <option value="">
                    {{ __('message.select', ['Model' => __('names.branch')]) }}
                </option>
                @foreach ($branches as $key => $branch)
                    <option value="{{ $key }}">
                        {{ $branch }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 form-group mb-4">
            <x-input-label :value="__('names.management')" class="mb-2"></x-input-label>
            <select wire:model.lazy="managementId" class="form-select">
                <option value="">
                    {{ __('message.select', ['Model' => __('names.management')]) }}
                </option>
                @foreach ($managements as $key => $management)
                    <option value="{{ $key }}">
                        {{ $management }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 form-group mb-4">
            <x-input-label :value="__('names.department')" class="mb-2"></x-input-label>
            <select wire:model.lazy="departmentId" class="form-select">
                <option value="">
                    {{ __('message.select', ['Model' => __('names.department')]) }}
                </option>
                @foreach ($departments as $key => $department)
                    <option value="{{ $key }}">
                        {{ $department }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 form-group mb-4">
            <x-input-label :value="__('names.employee')" class="mb-2"></x-input-label>

            <select wire:model.lazy="employeeId" class="form-select">
                <option value="">
                {{ __('message.select', ['Model' => __('names.employee')]) }}
                @foreach (App\Models\Employee\Employee::all() as $emp)
                    <option value="{{ $emp->id }}">
                        {{ $emp->first_name . ' ' . $emp->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-container page mt-4 d-print-block">
        <div class="d-none d-print-block">
            <p style="text-align:center">
                <b>
                    {{ __('names.attendance-between-two-dates', ['date1' => $fromDate, 'date2' => $toDate]) }}
                </b>
                <br>
                <small>
                    <b>
                        {{ __('names.printed-in') }} {{ now()->timezone($timezone)->format('d-m-Y h:i A') }}
                    </b>
                </small>
            </p>
        </div>
        <table class="table table-bordered" style="font-size:13px">
            <thead>
            <th>
                {{ __('names.status') }}
            </th>
            <th>
                {{ __('names.branch') }} - {{ __('names.management') }} - {{ __('names.department') }}
            </th>
            <th>
                {{ __('names.employee') }}
            </th>
            <th>
                {{ __('names.attend-in') }}
            </th>
            <th>
                {{ __('names.attend-out') }}
            </th>

            <th>
                {{ __('names.work-hours-details') }}
            </th>
            {{-- <th>
            {{ __('names.setting') }}
        </th> --}}
            </thead>
            <tbody>
            @forelse ($employeesGroups as $date => $employees_list)
                <tr>
                    <th colspan="2" style="background-color: #F7F7F7">

                        عدد الموظفين

                        ({{ count($employees_list) }})
                    </th>
                    <th colspan="1" style="background-color: #F7F7F7">
                        عدد
                        الحضور

                        ( {{ count($employees_list->where(fn($query) => count($query->reports) > 0)) }} )
                    </th>
                    <th colspan="2" style="background-color: #F7F7F7">
                        عدد
                        الغياب

                        ( {{ count($employees_list->where(fn($query) => count($query->reports) == 0)) }} )
                    </th>
                    <th colspan="1" style="background-color: #F7F7F7">
                        ليوم {{ $date }}
                    </th>
                </tr>
                @forelse ($employees_list as $employee)
                    @php
                        unset($report) ;
                        $report = $employee->reports->first() ?? null ;
                    @endphp
                    <tr>
                        <td style="vertical-align: middle;">
                            @if (false)
                                <img src="{{ asset('assets/images/confirmed.svg') }}" alt=""
                                     style="">
                            @elseif($report && empty($report?->check_in))
                                <img src="{{ asset('assets/images/cancel.svg') }}" alt="" style="">
                            @elseif($report && ! empty($report?->check_in))
                                <img src="{{ asset('assets/images/confirmed.svg') }}" alt=""
                                    style="">
                                <!-- <img src="{{ asset('assets/images/cancel.svg') }}" alt="" style=""> -->
                            @else
                                <img src="{{ asset('assets/images/cancel.svg') }}" alt="" style="">
                                <!-- <img src="{{ asset('assets/images/normal.svg') }}" alt="" style=""> -->
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            @if ($employee?->workAt?->workable_type == 'branches')
                                {{ $employee?->workAt?->workable?->name }}
                            @elseif ($employee?->workAt?->workable_type == 'managements')
                                {{ $employee?->workAt?->workable?->branch?->name }}
                            @else
                                {{ $employee?->workAt?->workable?->management?->branch?->name }}
                            @endif

                            -

                            @if ($employee?->workAt?->workable_type == 'branches')
                                -
                            @elseif ($employee?->workAt?->workable_type == 'managements')
                                {{ $employee?->workAt?->workable?->name }}
                            @else
                                {{ $employee?->workAt?->workable?->management?->name }}
                            @endif

                            -

                            @if ($employee?->workAt?->workable_type == 'branches')
                                -
                            @elseif ($employee?->workAt?->workable_type == 'managements')
                                -
                            @else
                                {{ $employee?->workAt?->workable->name }}
                            @endif
                        </td>

                        <td style="vertical-align: middle;">
                            {{ $employee?->first_name . ' ' . $employee?->second_name . ' ' . $employee?->last_name }}
                        </td>

                        <td>
                            <table>

                                    <tr>
                                        <td style="text-align:center ; vertical-align: middle;">
                                            {{-- {{ \Carbon\Carbon::parse($attend->created_at)->timezone($timezone)->format('d/m/Y') }}--}}

                                            {{  $report?->check_in ? \Carbon\Carbon::parse($report->check_in)->timezone($timezone)->format('h:i A') : '-' }}
                                        </td>
                                    </tr>


                            </table>
                        </td>

                        <td>
                            <table>
                                        <tr>
                                            <td style="text-align:center ; vertical-align: middle;">
                                                {{--  {{ \Carbon\Carbon::parse($att?->created_at)->timezone($timezone)->format('d/m/Y') }}--}}
                                                {{ $report?->check_out ? \Carbon\Carbon::parse($report?->check_out)->timezone($timezone)->format('h:i A') : '-'}}
                                            </td>
                                        </tr>
                            </table>
                        </td>


                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        اساسية
                                    </td>
                                    <td>
                                        متاخر
                                    </td>
                                    <td>
                                        اضافية
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        {{ $report?->work_hours == 0 ? '-' : secondsToHours($report?->work_hours * 60 * 60) }}
                                    </td>
                                    <td>
                                        {{ $report?->late_hours == 0 ? '-' : secondsToHours($report?->late_hours * 60 * 60) }}
                                    </td>
                                    <td>
                                        {{ $report?->overtime_hours == 0 ? '-' : secondsToHours($report?->overtime_hours * 60 * 60) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                         <small>
                                            ({{$report?->hourly_value}}) {{ $report?->currency }}
                                         </small>

                                         {{ $report?->total }}
                                    </td>
                                </tr>
                            </table>
                        </td>
{{--                        <td>--}}
{{--                            @if(count($employee?->attendances) >= 1)--}}
{{--                                <button class="btn btn-sm btn-danger" wire:click="delete('{{$employee?->attendances[0]->id }}')">--}}
{{--                                    <i class="bx bx-trash bx-sm"></i>--}}
{{--                                </button>--}}
{{--                                @else--}}
{{--                                ---}}
{{--                                @endif--}}
{{--                    </td>--}}
                    </tr>
                    @php
                        $report = null ;
                    @endphp
                @empty
                    <tr>
                        <td colspan="9">
                            <p class="text-danger">
                                {{ __('message.not-found', ['Model' => __('names.attendances')]) }}
                            </p>
                        </td>
                    </tr>
                @endforelse
            @empty
                <tr>
                    <td colspan="9">
                        <p class="text-danger">
                            {{ __('message.not-found', ['Model' => __('names.attendances')]) }}
                        </p>
                    </td>
                </tr>

            @endforelse


            </tbody>
        </table>
    </div>

</div>
