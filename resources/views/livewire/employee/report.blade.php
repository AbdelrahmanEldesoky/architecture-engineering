@inject('branch', 'App\Models\Hr\Branch')
@inject('department', 'App\Models\Hr\Department')
@inject('management', 'App\Models\Hr\Management')

<div>
    <h3 class="d-print-none">
        {{ __('names.employees-attendance') }}
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

            <button class="btn btn-primary mx-2  d-inline-flex align-items-center mt-4" onclick="exportExcel()">
                Execl
            </button>
            <button class="btn btn-primary mx-2  d-inline-flex align-items-center mt-4"
                    onclick="javascript:demoFromHTML()">
                Pdf
            </button>
        </div>
        @if($this->fromDate == $this->toDate)
                <?php
                $dateString = $this->fromDate; // Assuming $this->fromDate is a string in the format 'Y-m-d'
                $dateTime = DateTime::createFromFormat('Y-m-d', $dateString);
                $formattedDate = $dateTime->format('d-m-Y');
                ?>
            <div class="d-flex flex-wrap gap-2">
                <div class="btn btn-primary light">عدد الموظفين: {{$count_employees}}</div>
                <div class="status active">عدد الحضور: {{$employee_present_count}}</div>
                <div class="status stopped">عدد الغياب: {{$employee_absence_count}}</div>
                <div class="status" style="min-width: 190px">تاريخ اليوم: {{$formattedDate}}</div>
            </div>
        @endif
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
                <x-input-label :value="__('names.department')" class="mb-2"></x-input-label>
                <select wire:model.lazy="departmentId" class="form-select">
                    <option value=".">
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
            @if($employeeId == null)
                <table class="table table-hover table-borderless table-responsive-md" id="customers">
                    @php
                        $ranges = Carbon\CarbonPeriod::create($this->fromDate, $this->toDate)->toArray();
                    @endphp


                    @php
                        $LateHours=0;
                        $OvertimeHours=0;
                        $WorkHours=0;
                        $total=0;
                        $currency ='';
                    @endphp
                    <thead>
                    <tr>
                        <th></th>
                        <th>الاسم</th>
                        <th style="width: 150px">التاريخ</th>
                        <th>الفرع</th>
                        <th>الادارة</th>
                        <th>الحضور الرسمي</th>
                        <th>الانصراف الرسمي</th>
                        <th>الحضور الفعلي</th>
                        <th>الانصراف الفعلي</th>
                        <th>ساعات التأخير</th>
                        <th>ساعات اضافية</th>
                        <th>اجمالي ساعات اليوم</th>
                        <th>الاجر اليومي</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($i=0 ; $i< count($employees[$ranges[0]->format('Y-m-d')])-1;$i++)
                        @if($i%15==0)
                            <div class="html2pdf__page-break"></div>

                        @endif
                        @foreach($ranges as $range)
                            @php
                                unset($report) ;
                                $report = $employees[$range->format('Y-m-d')][$i]->reports->first() ?? null ;

                            @endphp
                            <tr>
                                <td>
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

                                <td class="text-align-start">{{ $employees[$range->format('Y-m-d')][$i]->name }}</td>
                                <td>
                                    {{$range->format('Y-m-d')}}
                                </td>
                                <td>
                                    @if ($employees[$range->format('Y-m-d')][$i]->workAt?->workable_type == 'branches')
                                        {{ $employees[$range->format('Y-m-d')][$i]->employee?->workAt?->workable?->name }}
                                    @elseif($employees[$range->format('Y-m-d')][$i]->workAt?->workable_type == 'managements')
                                        @foreach ($managements_all as $index_management)
                                            @if ($index_management->id == $employees[$range->format('Y-m-d')][$i]->workAt?->workable_id)
                                                {{ $index_management->branch?->name }}
                                            @endif
                                        @endforeach
                                    @elseif($employees[$range->format('Y-m-d')][$i]->workAt?->workable_type == 'departments')
                                        @foreach ($departments_all as $index_department)
                                            @if ($index_department->id == $employees[$range->format('Y-m-d')][$i]->workAt?->workable_id)
                                                {{ $index_department->management?->branch?->name }}
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($employees[$range->format('Y-m-d')][$i]->employee?->workAt?->workable_type == 'managements')
                                        @foreach ($managements_all as $index_management)
                                            @if ($index_management->id == $employees[$range->format('Y-m-d')][$i]->employee?->workAt?->workable_id)
                                                {{ $index_management->name }}
                                            @endif
                                        @endforeach
                                    @elseif($employees[$range->format('Y-m-d')][$i]->workAt?->workable_type == 'departments')
                                        @foreach ($departments_all as $index_department)
                                            @if ($index_department->id == $employees[$range->format('Y-m-d')][$i]->workAt?->workable_id)
                                                {{ $index_department->management?->name }}
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $report?->check_out ? \Carbon\Carbon::parse($report?->start_in)->timezone($timezone)->format('h:i A') : '-'}}</td>
                                <td>{{ $report?->check_out ? \Carbon\Carbon::parse($report?->end_in)->timezone($timezone)->format('h:i A') : '-' }}</td>
                                <td>{{  $report?->check_in ? \Carbon\Carbon::parse($report->check_in)->timezone($timezone)->format('h:i A') : '-' }}</td>
                                <td>{{ $report?->check_out ? \Carbon\Carbon::parse($report?->check_out)->timezone($timezone)->format('h:i A') : '-'}}</td>
                                <td @if($report?->late_hours != 0)
                                        class="text-danger"
                                    @endif>
                                    {{ $report?->late_hours == 0 ? '-' : secondsToHours($report?->late_hours * 60 * 60) }}

                                </td>
                                <td @if($report?->overtime_hours != 0)
                                        class="text-success"
                                    @endif>
                                    {{ $report?->overtime_hours == 0 ? '-' : secondsToHours($report?->overtime_hours * 60 * 60) }}
                                </td>
                                <td>{{ $report?->work_hours == 0 ? '-' : secondsToHours($report?->work_hours * 60 * 60) }}</td>
                                <td>{{ $report?->total }} {{ $report?->currency }}</td>

                                @php
                                    $LateHours+=$report?->late_hours;
                                    $OvertimeHours+=$report?->overtime_hours;
                                    $WorkHours+=$report?->work_hours;
                                    $total+=$report?->total;
                                    $currency =$report?->currency

                                @endphp
                            </tr>
                        @endforeach

                        @if($fromDate != $toDate)
                            <tr style="background: #B4C2D2 ;border-radius: 20px">
                                <td>الاجمالي</td>
                                <td style="width: 150px">{{$employees[$range->format('Y-m-d')][$i]->name}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{secondsToHours($LateHours*60*60)}}</td>
                                <td>{{secondsToHours($OvertimeHours*60*60)}}</td>
                                <td>{{secondsToHours($WorkHours*60*60) }}</td>
                                {{--                            @if ($totalSallry_SAR !=0 && $totalSallry_EGP == 0)--}}
                                {{--                                <td>{{$totalSallry_SAR}} SAR</td>--}}
                                {{--                            @elseif ($totalSallry_SAR ==0 && $totalSallry_EGP != 0)--}}
                                {{--                                <td>{{$totalSallry_EGP}} EGP</td>--}}
                                {{--                            @elseif ($totalSallry_SAR !=0 && $totalSallry_EGP != 0)--}}
                                {{--                                <td>{{$totalSallry_EGP}} EGP | {{$totalSallry_SAR}} SAR</td>--}}
                                {{--                            @else--}}
                                {{--                                <td>0</td>
                                @endif


                                --}}
                                <td>{{$total}} {{$currency}}</td>
                                @php
                                    $LateHours=0;
                                    $OvertimeHours=0;
                                    $WorkHours=0;
                                    $total=0;
                                    $currency='';

                                @endphp
                            </tr>
                        @endif

                    @endfor

                    </tbody>


                </table>
            @endif
            <table class="table table-hover table-borderless table-responsive-md" id="customers">
                @php
                    $LateHours=0;
                    $OvertimeHours=0;
                    $WorkHours=0;
                    $total=0;
                    $currency ='';
                @endphp

                @if($employeeId != null)
                    <thead>
                    <tr>
                        @if($fromDate == $toDate)
                            <th></th>
                        @else
                            <th style="width: 6%"></th>
                        @endif
                        <th>الاسم</th>
                        <th>الفرع</th>
                        <th>الادارة</th>
                        <th>الحضور الرسمي</th>
                        <th>الانصراف الرسمي</th>
                        <th>الحضور الفعلي</th>
                        <th>الانصراف الفعلي</th>
                        <th>ساعات التأخير</th>
                        <th>ساعات اضافية</th>
                        <th>اجمالي ساعات اليوم</th>
                        <th>الاجر اليومي</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $date => $employees_list)
                        @foreach ($employees_list as $item)
                            @php
                                unset($report) ;
                                $report = $item->reports->first() ?? null ;
                            @endphp
                            <tr>
                                <td>       @if (false)
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
                                <td class="text-align-start">{{ $item->name }}</td>
                                <td>
                                    @if ($item->workAt?->workable_type == 'branches')
                                        {{ $item->employee?->workAt?->workable?->name }}
                                    @elseif($item->workAt?->workable_type == 'managements')
                                        @foreach ($managements_all as $index_management)
                                            @if ($index_management->id == $item->workAt?->workable_id)
                                                {{ $index_management->branch?->name }}
                                            @endif
                                        @endforeach
                                    @elseif($item->workAt?->workable_type == 'departments')
                                        @foreach ($departments_all as $index_department)
                                            @if ($index_department->id == $item->workAt?->workable_id)
                                                {{ $index_department->management?->branch?->name }}
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($item->employee?->workAt?->workable_type == 'managements')
                                        @foreach ($managements_all as $index_management)
                                            @if ($index_management->id == $item->employee?->workAt?->workable_id)
                                                {{ $index_management->name }}
                                            @endif
                                        @endforeach
                                    @elseif($item->workAt?->workable_type == 'departments')
                                        @foreach ($departments_all as $index_department)
                                            @if ($index_department->id == $item->workAt?->workable_id)
                                                {{ $index_department->management?->name }}
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $report?->check_out ? \Carbon\Carbon::parse($report?->start_in)->timezone($timezone)->format('h:i A') : '-'}}</td>
                                <td>{{ $report?->check_out ? \Carbon\Carbon::parse($report?->end_in)->timezone($timezone)->format('h:i A') : '-' }}</td>
                                <td>
                                    {{  $report?->check_in ? \Carbon\Carbon::parse($report->check_in)->timezone($timezone)->format('h:i A') : '-' }}</td>
                                <td>{{ $report?->check_out ? \Carbon\Carbon::parse($report?->check_out)->timezone($timezone)->format('h:i A') : '-'}}</td>
                                <td @if($report?->late_hours != 0)
                                        class="text-danger"
                                    @endif>
                                    {{ $report?->late_hours == 0 ? '-' : secondsToHours($report?->late_hours * 60 * 60) }}
                                </td>
                                <td @if($report?->overtime_hours != 0)
                                        class="text-success"
                                    @endif>
                                    {{ $report?->overtime_hours == 0 ? '-' : secondsToHours($report?->overtime_hours * 60 * 60) }}
                                </td>
                                <td>{{ $report?->work_hours == 0 ? '-' : secondsToHours($report?->work_hours * 60 * 60) }}</td>
                                <td>{{ $report?->total }} {{ $report?->currency }}</td>


                                @php
                                    $LateHours+=$report?->late_hours;
                                    $OvertimeHours+=$report?->overtime_hours;
                                    $WorkHours+=$report?->work_hours;
                                    $total+=$report?->total;
                                    $currency =$report?->currency

                                @endphp
                            </tr>

                        @endforeach
                    @endforeach
                    </tbody>
                    <thead>
                    <tr></tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>الاجمالي</td>
                        <td>{{$name}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{secondsToHours($LateHours*60*60)}}</td>
                        <td>{{secondsToHours($OvertimeHours*60*60)}}</td>
                        <td>{{secondsToHours($WorkHours*60*60) }}</td>
                        {{--                            @if ($totalSallry_SAR !=0 && $totalSallry_EGP == 0)--}}
                        {{--                                <td>{{$totalSallry_SAR}} SAR</td>--}}
                        {{--                            @elseif ($totalSallry_SAR ==0 && $totalSallry_EGP != 0)--}}
                        {{--                                <td>{{$totalSallry_EGP}} EGP</td>--}}
                        {{--                            @elseif ($totalSallry_SAR !=0 && $totalSallry_EGP != 0)--}}
                        {{--                                <td>{{$totalSallry_EGP}} EGP | {{$totalSallry_SAR}} SAR</td>--}}
                        {{--                            @else--}}
                        {{--                                <td>0</td>
                        @endif


                        --}}
                        <td>{{$total}} {{$currency}}</td>

                    </tr>
                    @php
                        $LateHours=0;
                        $OvertimeHours=0;
                        $WorkHours=0;
                        $total=0;
                        $currency='';

                    @endphp
                    </tbody>
                @endif
            </table>
        </div>

    </div>

