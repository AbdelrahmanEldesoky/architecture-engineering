<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" :current="isset($id) ? 'edit-state' : 'create-state'" ></x-breadcrumb>
    @endsection
    <livewire:settings.states.states-form :id="$id ?? null"/>
</x-admin-app>
