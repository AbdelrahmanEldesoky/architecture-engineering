<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="states-setting" ></x-breadcrumb>
    @endsection
    <livewire:settings.states.states-table />
</x-admin-app>
   