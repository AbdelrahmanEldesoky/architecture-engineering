<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="date-end" ></x-breadcrumb>
    @endsection
    <livewire:settings.date-end.form />
</x-admin-app>