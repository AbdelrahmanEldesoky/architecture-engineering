<button type="button" class="btn btn-primary {{ $light ?? '' }} w-100  collapsed my-2"
        style="display: flex ;justify-content: space-between">
    @if (count($branch->childrens)  || count($branch->departments))
        <div>
            <i data-bs-toggle="collapse" data-bs-target="#childern-{{ $branch->id }}" aria-expanded="true"
               class="bx bx-sm bx-plus-circle"></i>
            {{ $branch->name }}
        </div>

    @else
        {{ $branch->name }}
    @endif
    <div>
        <i data-bs-toggle="collapse" data-bs-target="#show-{{ $branch->id }}" aria-expanded="true"
           class="bx bx-sm bx-show"></i>
    </div>

</button>
<div class="collapse mt-2 mb-2" id="show-{{ $branch->id }}">
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
            <td>{{ $branch->id }}</td>
            <td>
                <a href="{{ route('admin.departments.index', ['branch_id'=>$branch_id, 'management_id'=>$branch->id,'parent_type'=>2]) }}">{{ $branch->name }}</a>
            </td>
            <td>{{ __('names.' . $branch->type) }}</td>
            <td>{{ $branch?->manger?->employee?->name }}</td>
            <td>
                <a href="{{route('admin.department-tree',['branch_id'=>$branch_id, 'management_id'=>$branch->id,'parent_type'=>2])}}">{{ $branch->directChildren()->count() }}</a>
            </td>
            <td>
                <a href="{{route('admin.get-employee-by-branch-and-department-and-management',['branch_id'=>-1,'management_id'=>$branch->id])}}">

                    {{ $branch->numberOfEmps() }}
                </a>
            </td>
            <td>
                <div class=" limit-2">
                    @if (havePermissionTo('managements.edit'))
                        <a href="{{ route('admin.managements.edit',  ['branch_id'=>$branch_id, 'management'=>$branch->id,'parent_type'=>3]) }}"
                           class="px-1">
                            <i class='bx bxs-edit bx-sm text-gray'></i>
                        </a>
                    @endif
                    @if (havePermissionTo('managements.delete'))
                        <a href="#" class="px-1" wire:click.prevent="delete({{ $branch->id }})">
                            <i class='bx bx-trash bx-sm text-danger'></i>
                        </a>
                    @endif
                </div>
            </td>
        </tr>
        </tbody>
    </x-table>
</div>
