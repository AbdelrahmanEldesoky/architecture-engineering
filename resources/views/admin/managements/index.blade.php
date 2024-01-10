<x-admin-app>
{{--    <div class="row container-fluid mb-3 d-flex justify-content-end">--}}
{{--        <a class="btn-primary btn text-center d-flex justify-content-center" style="width: 100px" href="{{ URL::previous() }}--}}
{{--            ">  الرجوع</a>--}}
{{--    </div>--}}
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="managements" ></x-breadcrumb>
    @endsection

    @if (!isset($branch_id) || $branch_id == null)
        @livewire('managements.tables.branches')
    @endif

    @if (isset($branch_id) && $branch_id != null)
        @livewire('managements.tables.managements', ['branch_id' => $branch_id])
    @endif
</x-admin-app>
