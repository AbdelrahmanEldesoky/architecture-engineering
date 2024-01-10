<div class="container-fluid">


    <h4>
        {{ __('message.add', ['model' => __('names.management')]) }}
    </h4>
    <form method="POST" action="#" wire:submit.prevent="save">
        @csrf
        {{--        <div class="row">--}}
        {{--            @if($parent_type == 3)--}}
        {{--                <div class="col-md-4 form-group mb-2">--}}
        {{--                    <x-input-label :value="__('names.branch-name')"></x-input-label>--}}
        {{--                    <select name="branch_id" wire:model.lazy="branch_id"--}}
        {{--                            class="form-select @error('branch_id') is-invalid  @enderror">--}}
        {{--                        <option value=""> {{ __('names.select') }}</option>--}}
        {{--                        @foreach ($branches as $key => $branch)--}}
        {{--                            <option value="{{ $key }}" {{ $branch_id == $key ? 'selected' : '' }}>--}}
        {{--                                {{ $branch }} </option>--}}
        {{--                        @endforeach--}}
        {{--                    </select>--}}
        {{--                    @error('branch_id')--}}
        {{--                    <span class="invalid-feedback" role="alert">--}}
        {{--                        <strong>{{ $message }}</strong>--}}
        {{--                    </span>--}}
        {{--                    @enderror--}}
        {{--                </div>--}}
        {{--            @else--}}
        {{--                <div class="col-md-4 form-group mb-2">--}}
        {{--                    <x-input-label :value="__('names.branch-name')"></x-input-label>--}}
        {{--                    <input class="form-control"--}}
        {{--                           value="{{\App\Models\Hr\Branch::where('id',$branch_id)->first()->name}}">--}}
        {{--                </div>--}}
        {{--            @endif--}}

        {{--        </div>--}}
        {{--        @if($parent_type==3)--}}
        {{--                <div class="row">--}}
        {{--                    <div class="col-md-4 form-group mb-2">--}}

        {{--                        <x-input-label :value="__('names.management-type')"></x-input-label>--}}
        {{--                        <select name="type" class="form-select @error('type') is-invalid  @enderror"--}}
        {{--                            wire:model.lazy="type">--}}
        {{--                            @foreach ($types as $key => $t)--}}
        {{--                                <option value="{{ $key }}">--}}
        {{--                                    {{ $t }}--}}
        {{--                                </option>--}}
        {{--                            @endforeach--}}
        {{--                        </select>--}}
        {{--                        @error('type')--}}
        {{--                            <span class="invalid-feedback" role="alert">--}}
        {{--                                <strong>{{ $message }}</strong>--}}
        {{--                            </span>--}}
        {{--                        @enderror--}}
        {{--                    </div>--}}
        {{--                    @if ($type == 'sub')--}}
        {{--                        <div class="col-md-4 form-group mb-2">--}}
        {{--                            <x-input-label :value="__('names.management-parent')"></x-input-label>--}}
        {{--                            <select class="form-select @error('parent_id') is-invalid @enderror" wire:model.lazy="parent_id"--}}
        {{--                                name="parent_id">--}}
        {{--                                <option> {{ __('names.select') }}</option>--}}
        {{--                                @foreach ($management_parents as $key => $branch)--}}
        {{--                                    <option value="{{ $key }}" {{ $parent_id == $key ? 'selected' : '' }}>--}}
        {{--                                        {{ $branch }} </option>--}}
        {{--                                @endforeach--}}
        {{--                            </select>--}}
        {{--                            @error('parent_id')--}}
        {{--                                <span class="invalid-feedback" role="alert">--}}
        {{--                                    <strong>{{ $message }}</strong>--}}
        {{--                                </span>--}}
        {{--                            @enderror--}}
        {{--                        </div>--}}
        {{--                    @endif--}}
        {{--                </div>--}}
        {{--        @endif--}}
        <div class="row">
            <div class="col-md-4 form-group mb-2">
                <x-input-label :value="__('names.management-name')"></x-input-label>
                <input name="name" type="text" class="form-control @error('name') is-invalid  @enderror"
                       wire:model.lazy="name"/>
                @error('name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4 form-group mb-2">
                <x-input-label :value="__('names.manager-name')"></x-input-label>


                <select class="form-control form-select @error('manager_id') is-invalid  @enderror"
                        wire:model.lazy="manager_id">
                    <option value="">
                        {{ __('names.select') }}
                    </option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">
                            {{ $manager->first_name . ' ' . $manager->second_name . ' ' . $manager->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="mt-4">
            <div class="col-md-12">
                <h4>
                    {{ __('names.departments') }}
                </h4>
            </div>


            @foreach ($departments as $key => $department)
                <div class="row mb-4 mt-2">
                    <div class="col-md-6 mb-2">
                        <x-input-label :value="__('names.department-name')"></x-input-label>
                        <x-text-input class="" :value="$name"
                                      wire:model.lazy="departments.{{ $key }}.name">
                        </x-text-input>
                        @error("departments.{{ $key }}.name")
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-2">
                        <x-input-label :value="__('names.manager-name')"></x-input-label>


                        <select class="form-control form-select
                                @error("departments.{{ $key }}.manager_id") is-invalid  @enderror"
                                wire:model.lazy="departments.{{ $key }}.manager_id">
                            <option value="">
                                {{ __('names.select') }}
                            </option>
                            @foreach ($deps_managers as $manager)
                                <option value="{{ $manager->user_id }}">
                                    {{ $manager->first_name . ' ' . $manager->second_name . ' ' . $manager->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error("departments.{{ $key }}.manager_id")
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                             </span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <x-input-label :value="__('names.services')"></x-input-label>
                        <div class="d-flex">
                            {{--                            wire:model.lazy="departments.{{ $key }}.service"--}}
                            <div class="input-group">
                                <input type="text" class="form-control @error('tags_query') is-invalid @enderror"
                                       wire:model.lazy="tags_query"
                                       autocomplete="tags_query">

                            </div>
                            <button href="#" wire:click.prevent="addTag({{$key}})"
                                    class="btn btn-primary">
                                {{__('names.add')}}
                            </button>
                        </div>
                        {{--                            <div class="list-group" wire:loading wire:target="searchTags">--}}
                        {{--                                <div class="list-group-item">--}}
                        {{--                                    {{ __('message.loading',['model'=>__('names.tags')]) }}--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}

                        @if(!empty($tags_query))

                            <div class="list-group" wire:loading.remove>

                                <a href="#" wire:click.prevent="addTag({{$key}})"
                                   class="list-group-item text-decoration-none">
                                    {{__('names.add')}}
                                </a>
                            </div>
                        @endif

                        @error('tags_query')
                        <strong>{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="">
                        @forelse($department['tags'] as $id => $name)
                            <span class="badge bg-primary ">
                                {{$name}}
                                <a href="#" class="badge text-bg-primary light"
                                   wire:click.prevent="removeTag({{$key}},{{$id}})">X</a>
                            </span>
                        @empty
                            {{ __('message.no-select',['model'=>__('names.services')]) }}
                        @endforelse
                    </div>

                </div>
            @endforeach
            <div class="col-md-12">
                <button type="button" wire:click="addDepartment" class="btn btn-primary btn-icon">
                    <i class='bx bx-plus-circle bx-sm'></i>
                    {{ __('message.add', ['model' => __('names.department')]) }}
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <button type="button" wire:click.pervent="save" class="btn btn-primary w-100">
                    {{ __('names.save') }}
                </button>
            </div>
        </div>
    </form>
</div>
