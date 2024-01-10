<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="date-setting" ></x-breadcrumb>
    @endsection
    <livewire:settings.date-end.table />
</x-admin-app>
