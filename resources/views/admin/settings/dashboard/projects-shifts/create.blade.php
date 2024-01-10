<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="add-project-shift"></x-breadcrumb>
    @endsection
    @livewire('settings.projects-shifts.index', ['shift_id' => $shift_id])
</x-admin-app>
