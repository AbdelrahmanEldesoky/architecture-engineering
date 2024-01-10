<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="job-grades" ></x-breadcrumb>
    @endsection
        <livewire:job-grade.show :jobGradesId="$id ?? null"/>
</x-admin-app>

