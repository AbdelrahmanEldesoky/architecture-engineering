<x-admin-app>

    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="employees-attendance"></x-breadcrumb>
    @endsection
    <div class="section">
        <livewire:employee.report />
    </div>
</x-admin-app>
