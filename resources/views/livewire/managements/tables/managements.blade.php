<div class="container-fluid  section">
    <div class="row">
        <div>
            {{-- Showing Branches --}}
            @if (havePermissionTo('managements.create'))
                <a href="{{ route('admin.managements.create', ['branch_id' => $branch_id,'manageemnt_id'=>null,'parent_type'=>1]) }}"
                   {{--     type 1 branch    2 managemnt   3 department--}}

                   class="btn btn-primary mx-2 btn-icon">
                    <i class='bx bx-plus-circle bx-sm'></i>
                    {{ __('message.add', ['model' => __('names.management')]) }}
                </a>
            @endif
            {{--            @if (havePermissionTo('departments.create'))--}}
            {{--                <a href="{{ route('admin.departments.create', ['management_id' => ]) }}"--}}
            {{--                   class="btn btn-primary btn-icon">--}}
            {{--                    <i class="bx bx-plus-circle bx-sm"></i>--}}
            {{--                    {{ __('message.add', ['model' => __('names.department')]) }}--}}

            {{--                </a>--}}
            {{--            @endif--}}


        </div>
    </div>
    <div class="row">
        @foreach ($managements->where('type', 'main') as $mangement)
            @include('admin.Branches._shared.managemen_element', ['branch' => $mangement, 'light' => ''])
            <div class="collapse" id="childern-{{ $mangement->id }}">
            @if (!empty($mangement->childrens) || count($mangement->departments) )

                    @include('admin.Branches._shared.sub_element', [
                        'branch' => $mangement->childrens,
                        'light' => 'light',
                        'padding'=>18,
                        'branch_id'=>$branch_id
                    ])


            @endif
                @if(!empty($mangement->departments))
                    @foreach($mangement->departments as $d)

                        <button type="button"
                                class="btn btn-outline-primary {{ $light ?? '' }} w-100  collapsed my-2"
                                style="display: flex ;justify-content: space-between">


                            {{ $d->name }}

                            <div>
                                <i data-bs-toggle="collapse" data-bs-target="#show-{{ $d->id }}"
                                   aria-expanded="true"
                                   class="bx bx-sm bx-show"></i>
                            </div>


                        </button>
                        <div class="collapse mt-2 mb-2" id="show-{{ $d->id }}">
                            <x-table :responsive="true">

                                <thead>

                                <th>
                                    {{ __('names.id') }}
                                </th>
                                <th>
                                    {{ __('names.department-name') }}
                                </th>
                                <th>
                                    {{ __('names.department-type') }}
                                </th>

                                <th>
                                    {{ __('message.manager', ['model' => __('names.department')]) }}
                                </th>
                                <th>
                                    عدد الاقسام
                                </th>
                                <th>
                                    عدد الموظفين
                                </th>
                                <th>
                                    {{ __('names.setting') }}
                                </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $d->id }}</td>

                                    <td>
                                        <a href="{{ route('admin.details-department-tree', ['management_id'=>$mangement->id, 'department_id'=>$d->id,'parent_type'=>2]) }}">{{ $d->name }}</a>
                                    </td>
                                    <td>{{ __('names.' . $d->type) }}</td>
                                    <td>{{ $d->manger?->employee?->name }}</td>
                                    <td>
                                        <a href="{{route('admin.department-tree',['branch_id'=>$branch_id, 'management_id'=>$mangement->id,'parent_type'=>2])}}">
                                            {{ $d?->childrens()->count() }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{route('admin.get-employee-by-branch-and-department-and-management',['branch_id'=>-1,'management_id'=>-1,'department_id'=>$d->id])}}">

                                            {{ $d->numberOfEmps() }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class=" limit-2">
                                            @if (havePermissionTo('departments.edit'))
                                                <a href="{{ route('admin.departments.edit',  ['department'=>$d->id]) }}"
                                                   class="px-1">
                                                    <i class='bx bxs-edit bx-sm text-gray'></i>
                                                </a>
                                            @endif
                                            @if (havePermissionTo('departments.delete'))
                                                <a href="#" class="px-1"
                                                   wire:click.prevent="delete({{ $d->id}},{{2}})">
                                                    <i class='bx bx-trash bx-sm text-danger'></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </x-table>
                        </div>

                    @endforeach
                @endif

            </div>


        @endforeach

    </div>
</div>
