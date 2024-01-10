@inject('jobType', 'App\Models\Hr\JobType')


<div class="container-fluid  my-2 ">
    <div class="row my-3 d-flex">
        <div class="col-md-12 col d-flex flex-row-reverse">
            <button class="btn btn-primary  mx-2 d-flex align-items-center" wire:click="search">بحث</button>
            <input  type="text" wire:model.lazy="search" class="form-control "
                placeholder="{{ __('names.search') }}">
        </div>
    </div>

    @include('admin.jobs.nav', ['class' => 'job_type'])

    <div class="row section my-2 ">
        <div class="d-flex justify-content-between">
            @if (havePermissionTo('jobTypes.create'))
                <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                    wire:click="create()" data-bs-target="#JobTypeModal">
                    <i class='bx bx-plus-circle bx-sm'></i>
                    {{ __('message.create', ['model' => __('names.job-type')]) }}

                </button>
            @endif
            <button class="btn btn-primary light mx-2 d-flex align-items-center" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter" aria-expanded="false" aria-controls="filter">
            <i class='bx bx-filter-alt bx-sm'></i>
            {{ __('names.filter') }}
        </button>
        </div>
         
        <div class="row collapse" wire:ignore.self id="filter" >
        <div class="col">
            <x-input-label value="نوع الوظيفة"></x-input-label>
            <select class="form-select" wire:model="jobType">
                <option value="">
                    {{ __('message.select', ['Model' => 'نوع الوظيفة']) }}
                </option>
                @foreach($jobs as  $job)
                <option value="{{$job->id}}">{{$job->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <x-input-label value="المسميات الوظيفية"></x-input-label>
            <select class="form-select" wire:model="jobName">
                <option value="">
                    {{ __('message.select', ['Model' => 'المسميات الوظيفية']) }}
                </option>
                @foreach ($jobNames as $jobName)
                        <option value="{{$jobName->id}}">{{$jobName->name}}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="col">
            <x-input-label value="عدد الموظفين"></x-input-label>
            <select class="form-select" wire:model="count">
                <option value=".">
                    {{ __('message.select', ['Model' => 'عدد الموظفيين']) }}
                </option>
                @for ($i=0 ; $i < 10 ; $i++)
                        <option value="@if($i==0){{'0'}}@else {{$i}}@endif">{{$i}}</option>
                @endfor
            </select>
        </div> --}}
        <div class="col">
            <x-input-label :value="__('names.order-desc')"></x-input-label>
            <select wire:model="orderDesc" class="form-select">
                <option value="1">{{ __('names.desc') }}</option>
                <option value="0">{{ __('names.asc') }}</option>
            </select>
        </div>
        <div class="col">
            <x-input-label :value="__('names.per-page')"></x-input-label>
            <select wire:model="perPage" class="form-select">
                <option>5</option>
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
        </div>
    </div>
        <x-table :responsive="true">
            <thead>
                <th>
                    {{ __('names.job-type') }}
                </th>
                <th>
                    {{ __('message.count', ['model' => __('names.employees')]) }}
                </th>
                <th>
                    {{ __('names.job-names') }}
                </th>
                <th>
                    {{ __('names.status') }}
                </th>
                <th>
                    {{ __('names.setting') }}
                </th>
            </thead>
            <tbody>
                @forelse($jobTypes as $key => $jobType)
                    <tr>
                        <td>{{ $jobType->name }}</td>
                        <td><a href="{{ route('admin.job_types.show', $jobType->id) }}">{{ $jobType->employees_count }}</a></td>
                        <td class="flex-table-data" style="width: 350px">
                                @if (isset($jobType->jobNames))
                                <ul style="width: 350px;" class="d-flex flex-wrap list-unstyled text-end">
                                    @foreach ($jobType->jobNames as $index => $jobName)
                                        @if ($index % 2 == 0)
                                            <li class="w-50 mw-50 p-1 px-2 text-nowrap overflow-hidden text-truncate text-decoration-underline"><a href="{{ route('admin.job_names.show', [$jobName->id,'job_name' ]) }}">{{ $jobName->name }} ({{ $jobName->employees_count }})</a></li>

                                        @else
                                            <li class="w-50 mw-50 p-1 px-2 text-nowrap overflow-hidden text-truncate text-decoration-underline"><a href="{{ route('admin.job_names.show', [$jobName->id,'job_name' ]) }}">{{ $jobName->name }} ({{ $jobName->employees_count }})</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td>
                            @if ($jobType->active==1)
                                <span class="status active">
                                    {{ __('names.active') }}
                                </span>
                            @else
                                <span class="status stopped">
                                    {{ __('names.in-active') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class=" limit-2">
                                @if (havePermissionTo('jobTypes.edit'))
                                    <a data-bs-toggle="modal" data-bs-target="#JobTypeModal"
                                        wire:click.prevent="edit({{ $jobType->id }})" href="#" class="px-1">
                                        <i class='bx bxs-edit bx-sm text-gray'></i>
                                    </a>
                                @endif

                                @if (havePermissionTo('jobTypes.delete'))
                                    <a href="#" class="px-1" wire:click.prevent="delete({{ $jobType->id }})">
                                        <i class='bx bx-trash bx-sm text-danger'></i>
                                    </a>
                                @endif
                            </div>
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

    <div class="d-flex justify-content-center">
        {{ $jobTypes->links() }}
    </div>
    @if (havePermissionTo('jobTypes.create'))
        <livewire:job-type.modal-form modal_id="JobTypeModal" />
    @endif
</div>
