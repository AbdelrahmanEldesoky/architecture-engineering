<x-admin-app>
    @section('breadcrumb')
        <x-breadcrumb :tree="$tree" :current="isset($id) ? 'create-branch' : 'create-branch'" ></x-breadcrumb>
    @endsection
    <livewire:branch.form :branchId="$id ?? null" />
</x-admin-app>
