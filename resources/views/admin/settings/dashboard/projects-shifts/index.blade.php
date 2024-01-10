<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="projects-shifts-setting"></x-breadcrumb>
    @endsection
    @livewire('settings.projects-shifts.table')
</x-admin-app>
