<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="job-name" />
    @endsection
    <livewire:job-name.show :jobTypeId="$id ?? null"/>
</x-admin-app>