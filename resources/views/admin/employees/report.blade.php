<x-admin-app>

    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="employees-report"></x-breadcrumb>
    @endsection
    <div class="section">
        <livewire:employee.report />
    </div>
</x-admin-app>
