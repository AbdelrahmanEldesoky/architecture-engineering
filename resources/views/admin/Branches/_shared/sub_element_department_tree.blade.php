@foreach($branch as $b)

    <button type="button" class="btn btn-primary {{ $light ?? '' }} w-100  collapsed my-2"
            style="display: flex ;justify-content: space-between">

        @if (count($b->childrens) )
            <div>
                <i data-bs-toggle="collapse" data-bs-target="#childern-{{ $b->id }}" aria-expanded="true"
                   class="bx bx-sm bx-plus-circle"></i>
                {{ $b->name }}
            </div>

        @else
            {{ $b->name }}
        @endif

        <div>
            <i data-bs-toggle="collapse" data-bs-target="#show-{{ $b->id }}" aria-expanded="true"
               class="bx bx-sm bx-show"></i>
        </div>


    </button>
    <div class="collapse mt-2 mb-2" id="show-{{ $b->id }}">
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
            {{--            <th>--}}
            {{--                 عدد الاقسام--}}
            {{--            </th>--}}
            <th>
                عدد الموظفين
            </th>
            <th>
                {{ __('names.setting') }}
            </th>
            </thead>
            <tbody>
            <tr>
                <td>{{ $b->id }}</td>
                {{--                <td><a href="{{ route('admin.departments.index', ['branch_id'=>$branch_id, 'management_id'=>$b->id,'parent_type'=>2]) }}">{{ $b->name }}</a></td>--}}
                <td>
                    <a href="{{ route('admin.details-department-tree', ['management_id'=>$management_id, 'department_id'=>$b->id,'parent_type'=>2]) }}">{{ $b->name }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.details-department-tree', ['management_id'=>$management_id, 'department_id'=>$b->id,'parent_type'=>2]) }}">{{ $b->type }}</a>
                </td>
                <td>{{ $b?->manger?->employee?->name }}</td>
                <td>
                    <a href="{{route('admin.get-employee-by-branch-and-department-and-management',['branch_id'=>-1,'management_id'=>-1,'department_id'=>$b->id])}}">

                        {{$b->numberOfEmps()}}
                    </a>

                </td>

                <td>
                    <div class=" limit-2">
                        @if (havePermissionTo('departments.edit'))
                            <a href="{{ route('admin.departments.edit',  [ 'department'=>$department->id,'parent_type'=>3]) }}"
                               class="px-1">
                                <i class='bx bxs-edit bx-sm text-gray'></i>
                            </a>
                        @endif
                        @if (havePermissionTo('departments.delete'))
                            <a href="#" class="px-1" wire:click.prevent="delete({{ $b->id }})">
                                <i class='bx bx-trash bx-sm text-danger'></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            </tbody>
        </x-table>
    </div>
    @if (count($b->childrens) )
        <div class="collapse" id="childern-{{ $b->id }}" style="padding-right: {{$padding}}px">
            @include('admin.Branches._shared.sub_element_department_tree', [
                'branch' => $b->childrens,
                'light' => 'light',
                'padding'=>$padding+=10,
                 'management_id'=>$management_id,
            ])


        </div>
    @endif
@endforeach
