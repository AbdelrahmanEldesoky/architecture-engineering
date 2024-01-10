<x-admin-app>
    <div class="row container-fluid mb-3 d-flex justify-content-end">
        <a class="btn-primary btn text-center d-flex justify-content-center" style="width: 100px" href="{{ URL::previous() }}
            ">  الرجوع</a>
    </div>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="edit-management" ></x-breadcrumb>
    @endsection
    @livewire('managements.forms.management', ['branch_id' => $branch_id,'management_id'=>$management_id,'parent_type' => $parent_type])
</x-admin-app>
