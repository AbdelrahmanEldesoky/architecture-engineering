<div class="container-fluid  section">
    <div class="row">
        <div>
            {{-- Showing Branches --}}
            @if (havePermissionTo('departments.create'))
                <a href="{{ route('admin.departments.create', ['management_id' => $management_id,'parent_type'=>1]) }}"
                   class="btn btn-primary btn-icon">
                    <i class="bx bx-plus-circle bx-sm"></i>
                    {{ __('message.add', ['model' => __('names.department')]) }}
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
        @foreach ($departments->where('type', 'main') as $department)
            @include('admin.Branches._shared.department_element', ['branch' => $department, 'management_id'=>$management_id,'light' => ''])
            @if (!empty($department->childrens))
                <div class="collapse" id="childern-{{ $department->id }}">
                    @include('admin.Branches._shared.sub_element_department_tree', [
                        'branch' => $department->childrens,
                        'light' => 'light',
                        'padding'=>18,
                        'management_id'=>$management_id
                    ])


                </div>
            @endif
        @endforeach

    </div>
</div>
