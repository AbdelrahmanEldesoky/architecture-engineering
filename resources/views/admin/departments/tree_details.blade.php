<x-admin-app>
{{--    <div class="row container-fluid mb-3 d-flex justify-content-end">--}}
{{--        <a class="btn-primary btn text-center d-flex justify-content-center" style="width: 100px" href="{{ URL::previous() }}--}}
{{--            ">  الرجوع</a>--}}
{{--    </div>--}}
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="departments" ></x-breadcrumb>
    @endsection
    @livewire('managements.tables.departments-tree-details', [ 'management_id'=>$management_id,'department_id' => $department_id,'parent_type'=>$parent_type])
</x-admin-app>
