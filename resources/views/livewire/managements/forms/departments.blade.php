<div class="section">
    <div class="row">
        <div class="col-md-12">
            <h4>
                {{ __('message.add', ['model' => __('names.department')]) }}
            </h4>
        </div>
{{--        @if($parent_type == 3)--}}
{{--            <div class="col-md-6 mb-2">--}}
{{--                <x-input-label :value="__('names.department-type')" required="true"></x-input-label>--}}
{{--                <select name="type" class="form-select @error('type') is-invalid  @enderror" wire:model.lazy="type">--}}
{{--                    @foreach ($types as $key => $t)--}}
{{--                        <option value="{{ $key }}" {{ $key == $type ? 'selected' : '' }}>--}}
{{--                            {{ $t }}--}}
{{--                        </option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--                @error('type')--}}
{{--                <span class="invalid-feedback" role="alert">--}}
{{--                    <strong>{{ $message }}</strong>--}}
{{--                </span>--}}
{{--                @enderror--}}

{{--            </div>--}}


{{--            <div class="col-md-6">--}}
{{--                @if ($type == 'sub')--}}
{{--                    <x-input-label :value="__('names.department-parent')"></x-input-label>--}}
{{--                    <select class="form-select @error('parent_id') is-invalid @enderror" wire:model.lazy="parent_id"--}}
{{--                            name="parent_id">--}}
{{--                        <option> {{ __('names.select') }}</option>--}}
{{--                        @foreach ($parents as $key => $branch)--}}
{{--                            <option value="{{ $key }}" {{ $parent_id == $key ? 'selected' : '' }}>--}}
{{--                                {{ $branch }} </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    @error('parent_id')--}}
{{--                    <span class="invalid-feedback" role="alert">--}}
{{--                        <strong>{{ $message }}</strong>--}}
{{--                    </span>--}}
{{--                    @enderror--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        @endif--}}
        <div class="col-md-6">
            <x-input-label :value="__('names.department-name')"></x-input-label>
            <input name="name" value="{{ $name }}" type="text"
                   class="form-control @error('name') is-invalid  @enderror" wire:model.lazy="name"/>
            @error('name')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <x-input-label :value="__('names.manager-name')" required="true"></x-input-label>
            <select class="form-control form-select  @error('manager_id') is-invalid @enderror" wire:model.lazy="manager_id">
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

        <div class="col-md-12">
            <x-input-label :value="__('names.services')" ></x-input-label>
            <div class="d-flex">

                <div class="input-group">
                    <input type="text" class="form-control @error('tags_query') is-invalid @enderror"
                           wire:model.lazy="tags_query"
                           autocomplete="tags_query">

                </div>
                <button href="#" wire:click.prevent="addTag()"
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

                        <a href="#" wire:click.prevent="addTag()"
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
            @forelse($tags as $id => $name)
                <span class="badge bg-primary ">
                                {{$name}}
                                <a href="#" class="badge text-bg-primary light"
                                   wire:click.prevent="removeTag({{$id}})">X</a>
                            </span>
            @empty
                {{ __('message.no-select',['model'=>__('names.services')]) }}
            @endforelse
        </div>
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <button wire:click.pervent="save" class="btn btn-primary w-100 btn-block">
                {{ __('names.save') }}
            </button>
        </div>

    </div>
</div>
