@foreach($branch as $b)

    <button type="button" class="btn btn-primary {{ $light ?? '' }} w-100  collapsed my-2"
            style="display: flex ;justify-content: space-between">

        @if (count($b->childrens)  )
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
                {{ __('names.management-name') }}
            </th>
            <th>
                {{ __('names.management-type') }}
            </th>

            <th>
                {{ __('message.manager', ['model' => __('names.management')]) }}
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
                <td>{{ $b->id }}</td>
                <td>
                    <a href="{{ route('admin.departments.index', ['branch_id'=>$branch_id, 'management_id'=>$b->id,'parent_type'=>2]) }}">{{ $b->name }}</a>
                </td>
                <td>{{ __('names.' . $b->type) }}</td>
                <td>{{ $b->manger?->employee?->name }}</td>
                <td>
                    <a href="{{route('admin.department-tree',['branch_id'=>$branch_id, 'management_id'=>$b->id,'parent_type'=>2])}}">
                        {{ $b->directChildren()->count() }}
                    </a>
                </td>
                <td>
                    <a href="{{route('admin.get-employee-by-branch-and-department-and-management',['branch_id'=>-1,'management_id'=>$b->id])}}">

                        {{ $b->numberOfEmps() }}
                    </a>
                </td>
                <td>
                    <div class=" limit-2">
                        @if (havePermissionTo('managements.edit'))
                            <a href="{{ route('admin.managements.edit',  ['branch_id'=>$branch_id, 'management'=>$b->id,'parent_type'=>3]) }}"
                               class="px-1">
                                <i class='bx bxs-edit bx-sm text-gray'></i>
                            </a>
                        @endif
                        @if (havePermissionTo('managements.delete'))
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
            @include('admin.Branches._shared.sub_element', [
                'branch' => $b->childrens,
                'light' => 'light',
                'padding'=>$padding+=10,
                 'branch_id'=>$branch_id,
            ])


        </div>
    @endif



@endforeach


