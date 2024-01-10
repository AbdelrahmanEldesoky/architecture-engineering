<div class="container-fluid section ">
    <div class="row d-none">
        <div class="col-md-3">
            <x-input-label :value="__('names.branch-type')"></x-input-label>
            <select class="form-control" wire:model.lazy="branch_type">
                <option disabled selected>
                    {{ __('names.Select') }} {{ __('names.branch-type') }}
                </option>
                <option value="main">
                    {{ __('names.main') }}
                </option>
                <option value="sub">
                    {{ __('names.sub') }}
                </option>
            </select>
        </div>
        <div class="col-md-3">
            <x-input-label :value="__('names.management-name')"></x-input-label>
            <x-text-input wire:model.lazy="management_name"></x-text-input>
        </div>
        <div class="col-md-3">
            <x-input-label :value="__('names.manager-name')"></x-input-label>
            <x-text-input wire:model.lazy="management_manager"></x-text-input>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block w-100">
                {{ __('names.Search') }}
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if (havePermissionTo('managements.create'))
                <a href="{{ route('admin.managements.create', ['branch_id' => $branch_id,'management_id'=>$management_id,'parent_type'=>2]) }}"{{--     type 1 branch    2 managemnt   3 department--}}

                class="btn btn-primary mx-2 btn-icon">
                    <i class='bx bx-plus-circle bx-sm'></i>
                    {{ __('message.add', ['model' => __('names.management')]) }}
                </a>
            @endif
            @if (havePermissionTo('departments.create'))
                <a href="{{ route('admin.departments.create', ['management_id' => $management_id,'parent_type'=>1]) }}"
                    class="btn btn-primary btn-icon">
                    <i class="bx bx-plus-circle bx-sm"></i>
                    {{ __('message.add', ['model' => __('names.department')]) }}
                </a>
            @endif
            <x-table :responsive="true">
                <thead>
                    <th>
                        #
                    </th>
                    <th>
                        {{ __('message.name', ['model' => __('names.management')]) }}
                    </th>


                    <th>
                        {{ __('names.management-type') }}
                    </th>
                    <th>
                        {{ __('names.manager-name') }}
                    </th>
                    <th>
                        {{ __('message.count', ['model' => __('names.management')]) }}
                    </th>
                    <th>
                        {{ __('message.count', ['model' => __('names.departments')]) }}
                    </th>
                    <th>
                        {{ __('message.count', ['model' => __('names.employees')]) }}
                    </th>
                    <th>
                        {{ __('names.setting') }}
                    </th>
                </thead>
                <tbody>
                    @foreach ($managements as $key => $management)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $management->name }}
                            </td>
                            <td>
                                {{ __('names.' . $management->type) }}
                            </td>
                            <td>
                                {{ $management->manger?->employee?->name }}
                            </td>


                            <td>
                                {{ $management->childrens()->count() }}
                            </td>

                            <td>
                                {{ $management->departments()->count() }}
                            </td>
                            <td>
                                {{ $management->numberOfEmps() }}
                            </td>
                            <td>
                                @if (havePermissionTo('departments.edit'))
                                    <a href="{{ route('admin.managements.edit', ['branch_id'=>$branch_id, 'management'=>$management->id,'parent_type'=>3]) }}">
                                        <i class='bx bxs-edit bx-sm text-gray'></i>
                                    </a>
                                @endif
                                @if (havePermissionTo('managements.delete'))
                                    <a href="#" wire:click="delete({{ $management->id }})">
                                        <i class='bx bx-trash bx-sm text-danger'></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>

        </div>
    </div>
</div>
