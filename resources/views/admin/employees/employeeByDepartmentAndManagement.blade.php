<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="employees"></x-breadcrumb>
    @endsection
        <div class="row container-fluid mb-3 d-flex justify-content-end">
            <a class="btn-primary btn text-center d-flex justify-content-center" style="width: 100px" href="{{ URL::previous() }}
                ">  الرجوع</a>
        </div>
        <div>
            <div class="row">
                <div class="col-md-7">
                    {{-- Search bars --}}
{{--                    <input type="search" class="form-control" wire:model.debounce.400ms="search"--}}
{{--                           placeholder="{{ __('names.search') }}" />--}}
                </div>
                <div class="col-md-5">
                    {{-- Buttons  --}}
{{--                    @if (havePermissionTo('employees.create'))--}}
{{--                        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">--}}
{{--                            <i class="bx bx-plus-circle bx-sm"></i> {{ __('names.create') }}--}}
{{--                        </a>--}}
{{--                    @endif--}}




                </div>

            </div>
            {{-- table --}}
            <div class="table-container mt-2" style="min-height: 500px">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="w-100 pt-3 carousal_container">
                            <div class="scroller scroller-left float-left mt-2"><i class="bx bxs-chevron-left"></i></div>
                            <div class="scroller scroller-right float-right mt-2"><i class="bx bxs-chevron-right"></i></div>
                            <div class="wrapper">
                                <nav class="nav nav-tabs list mt-2" id="myTab" role="tablist">



{{--                                    <a class="nav-item nav-link {{ $branchId == 0 ? 'active' : '' }}" aria-current="page"--}}
{{--                                       href="{{ route('admin.employees.index') }}">--}}
{{--                                        {{ __('names.all') }}--}}
{{--                                    </a>--}}
{{--/--}}
{{--                                    @foreach ($branches as $key => $branch)--}}
{{--                                        <a class="nav-item nav-link {{ $branchId == $key ? 'active' : '' }}" aria-current="page"--}}
{{--                                           href="{{ route('admin.employees.index', ['branch_id' => $key]) }}">--}}
{{--                                            {{ $branch }}--}}
{{--                                        </a>--}}
{{--                                    @endforeach--}}
                                </nav>
                            </div>

                        </div>
                    </div>

                </div>

                <section class="section">
{{--                    <div class="d-flex flex-row-reverse">--}}
{{--                        <button class="btn btn-primary light mx-2 d-flex align-items-center mb-2" type="button"--}}
{{--                                data-bs-toggle="collapse" data-bs-target="#filter" aria-expanded="false" aria-controls="filter">--}}
{{--                            <i class='bx bx-filter-alt bx-sm'></i>--}}
{{--                            {{ __('names.filter') }}--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="row collapse mb-2" id="filter" wire:ignore>--}}
{{--                        <div class="col">--}}
{{--                            <x-input-label :value="__('names.date-start')"></x-input-label>--}}
{{--                            <input type="date" wire:model="start_date" class="form-control" />--}}
{{--                        </div>--}}
{{--                        <div class="col">--}}
{{--                            <x-input-label :value="__('names.date-end')"></x-input-label>--}}
{{--                            <input type="date" wire:model="end_date" class="form-control" />--}}
{{--                        </div>--}}
{{--                        <div class="col">--}}
{{--                            <x-input-label :value="__('names.type')"></x-input-label>--}}
{{--                            <select wire:model="type" class="form-select">--}}
{{--                                <option value="all">{{ __('names.all') }}</option>--}}
{{--                                <option value="main">{{ __('names.main') }}</option>--}}
{{--                                <option value="sub">{{ __('names.sub') }}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col">--}}
{{--                            <x-input-label :value="__('names.order-by')"></x-input-label>--}}
{{--                            <select wire:model="orderBy" class="form-select">--}}
{{--                                <option value="name">{{ __('names.job-type') }}</option>--}}
{{--                                <option value="employees_count">{{ __('names.employees-count') }}</option>--}}
{{--                                <option value="created_at">{{ __('names.created-at') }}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="col">--}}
{{--                            <x-input-label :value="__('names.order-desc')"></x-input-label>--}}
{{--                            <select wire:model="orderDesc" class="form-select">--}}
{{--                                <option value="1">{{ __('names.desc') }}</option>--}}
{{--                                <option value="0">{{ __('names.asc') }}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col">--}}
{{--                            <x-input-label :value="__('names.per-page')"></x-input-label>--}}
{{--                            <select wire:model="perPage" class="form-select">--}}
{{--                                <option>5</option>--}}
{{--                                <option>10</option>--}}
{{--                                <option>25</option>--}}
{{--                                <option>50</option>--}}
{{--                                <option>100</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <x-table responsive="true">
                        <thead>
                        <th>#</th>
                        <th>
                            {{ __('names.name') }}
                        </th>
                        <th>
                            {{ __('names.job-name') }}
                        </th>
                        <th>
                            {{ __('names.code') }}
                        </th>
                        <th>
                            {{ __('names.branch') }}
                        </th>
                        <th>
                            {{ __('names.phone') }}
                        </th>
                        <th>
                            {{ __('names.email') }}
                        </th>
                        <th>
                            {{ __('names.status') }}
                        </th>
                        <th>

                        </th>
                        </thead>
                        <tbody>
                        @forelse ($employees as $key=>$employee)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $employee->first_name . ' ' . $employee->second_name . ' ' . $employee->last_name }}
                                </td>
                                <td>
                                    {{ $employee->employmentData?->jobName?->name }}
                                </td>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->branchName() }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>
                                    <p class="{{ $employee->draft == 1 ? 'text-danger' : 'text-success' }}">
                                        {{ $employee->draft == 0 ? __('names.active') : __('names.in-active') }}
                                    </p>

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
                                                       href="{{ route('admin.custom.create', ['employee_id' => $employee->id, 'step' => 6]) }}">
                                                        <i class="bx bx-show"></i> {{ __('names.view') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (havePermissionTo('employees.edit'))
                                                @if (havePermissionTo('employees.personal-information'))
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.custom.create', ['employee_id' => $employee->id, 'step' => '1']) }}">
                                                            <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                            {{ __('names.personal-information') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (havePermissionTo('employees.academic-info'))
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.custom.create', ['employee_id' => $employee->id, 'step' => '2']) }}">
                                                            <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                            {{ __('names.academic-info') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (havePermissionTo('employees.employment-info'))
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.custom.create', ['employee_id' => $employee->id, 'step' => '3']) }}">
                                                            <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                            {{ __('names.employment-info') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (havePermissionTo('employees.employee-finances'))
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.custom.create', ['employee_id' => $employee->id, 'step' => '4']) }}">
                                                            <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                            {{ __('names.employee-finances') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (havePermissionTo('employees.attendance'))
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.custom.create', ['employee_id' => $employee->id, 'step' => '5']) }}">
                                                            <i class="bx bxs-edit-alt bx-sm"></i> {{ __('names.edit') }}
                                                            {{ __('names.attendance') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger"
                                                   wire:click="delete('{{ $employee->id }}')">
                                                    <i class="bx bxs-trash "></i>
                                                    {{ __('names.delete') }}</a></li>
                                        </ul>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="">
                                        <img class="" style="height: 100%"
                                             src="{{ asset('assets/images/empty.png') }}" alt="">
                                    </div>
                                    {{ __('message.empty', ['model' => __('names.employees')]) }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </x-table>
                    <div class="d-flex justify-content-center">
                        {{ $employees->render() }}

                    </div>

                </section>
            </div>
        </div>


</x-admin-app>
