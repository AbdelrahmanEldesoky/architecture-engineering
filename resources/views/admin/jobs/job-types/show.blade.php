<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="job-type" ></x-breadcrumb>
    @endsection
    <livewire:job-type.show :jobTypeId="$id ?? null"/>
</x-admin-app>
