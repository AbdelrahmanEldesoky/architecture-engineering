<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="branches" ></x-breadcrumb>
    @endsection

        @livewire('branch.table')
</x-admin-app>



