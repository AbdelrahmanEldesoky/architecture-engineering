<x-admin-app>
@section('breadcrumb')
        <x-breadcrumb :tree="$tree" current="clients" ></x-breadcrumb>
    @endsection

        <livewire:client.client-table />
 
</x-admin-app>


