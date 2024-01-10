
<div class="container-fluid ">
    <div class="row my-3 d-flex">
        <div class="col-md-12 col d-flex flex-row-reverse">
            <button class="btn btn-primary  mx-2 d-flex align-items-center" wire:click="search">بحث</button>
            <input  type="text" wire:model.lazy="search" class="form-control "
                placeholder="{{ __('names.search') }}">
        </div>
    </div>
    @include('admin.clients.nav', ['class' => 'client'])

    <div class="row section my-2 justify-content-start">
        <div class="col d-flex">
            @if (havePermissionTo('clients.create'))
                <a href="{{ route('admin.clients.create') }}">
                    <x-button class="d-flex justify-content-center align-items-center">
                        <i class='bx bx-plus-circle bx-sm'></i>
                        {{ __('message.add', ['model' => __('names.client')]) }}
                    </x-button>
                </a>
            @endif
           
            @if (havePermissionTo('clients.edit'))
            <button type="button" class="btn btn-primary  d-flex mx-2 align-items-center d-flex justify-content-center mb-2" data-bs-toggle="modal"
                    wire:click="edit()" data-bs-target="#clientEditModal">
                    <i class='bx bx-edit bx-sm'></i>
                    {{  __('تعديل بيانات العميل') }}
            </button>
            @endif
        </div>
        <div class="col d-flex justify-content-end mb-2">
            @if (havePermissionTo('clients.delete'))
                <button class="btn btn-outline-primary" wire:click="deleteSelected">
                    <i class='bx bx-trash bx-sm'></i>
              حــــذف
                
            </button>
            @endif
          </div>
        <x-table :responsive="true">

            <thead>
                <th></th>
                <th>
                    {{ __('اسم المالك') }}
                </th>
                <th>
                    {{ __('رقم التليفون') }}
                </th>
                <th>
                    {{ __('names.email') }}
                </th>
                <th>
                    {{ __('names.card-id') }}
                </th>
                <th>
                    {{ __('الفرع') }}
                </th>
                <th>
                    {{ __('حالة مشاريع العميل') }}
                </th>
                <th>
                    {{ __('اسم الوكيل') }}
                </th>
                <th>
                    
                </th>
            </thead>
            <tbody>
                @forelse($clients as $key => $client)
                    <tr>
                        <td><input type="checkbox" wire:model="selected" class="form-check-input" value="{{ $client->id }}"></td>
                        <td class="text-secondary" style="text-decoration: underline;"><a href="{{ route('admin.clients.show', $client->id) }}">{{ $client->name }}</a></td>
                        <td>{{ $client->phone }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->card_id }}</td>
                        <td>{{ optional($client->branch)->name }}</td>
                        <td>
                            @if (isset($client->status))
                                <div class="status {{ $client->status?->color }}">
                                    {{ __('names.' . $client->status?->name) }}</div>
                        </td>
                    @else
                        -
                @endif
                <td>{{ optional($client->broker)->name }}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-link" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bx bx-cog"></i>
                        </button>
                        <ul class="dropdown-menu" style="z-index: 999; text-align: right;">   
                            <li>
                                <a class="dropdown-item" href="#"></a>
                            </li>
                        </ul>
                    </div>

                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        <div class="">
                            <img class="" style="height: 100%" src="{{ asset('assets/images/empty.png') }}"
                                alt="">
                        </div>
                        {{ __('message.empty', ['model' => __('names.clients')]) }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $clients->links() }}
    </div>
    @if (havePermissionTo('clients.edit'))
    <livewire:client.client-edit modal_id="clientEditModal" />
@endif
</div>
