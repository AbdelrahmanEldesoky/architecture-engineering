<div>
    <form class="loading" method="POST" action="#" wire:submit.prevent="save">
        @csrf
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="Label">
                {{ __('names.add-project-shift') }}
            </h1>
        </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        {{--  __('names.project-name')  --}}
                        <x-input-label :value="__('اسم المحدد')"></x-input-label>
                        <input type="text" name="distance" wire:model.lazy="name"
                               class="form-control @error('name')
                                        is-invalid
                                    @enderror" />
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div wire:ignore.self class="col-md-8 " id="branch-settings" style="">
                            <div
                                class="form-group form-control @error('latitude') is-invalid @enderror @error('longitude') is-invalid @enderror mb-2">
                                <x-input-label :value="__('names.location')"></x-input-label>
                                <button type="button" class="btn btn-primary btn-block w-100" data-bs-toggle="collapse"
                                        data-bs-target="#mapContainer" aria-expanded="true">
                                    <i class="bx bx-map-pin"></i>
                                    {{ $address ?? __('names.choose-address') }}
                                </button>
                                @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                                @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-2 mb-2 loading">
                            <div class="collapse " id="mapContainer">
                                <div wire:ignore id="map" style="width: 100%; height:250px"></div>
                            </div>

                            <div wire:loading wire:target="updateLatAndLong">
                                <div class="loader-cotnainer">
                                    <div class="loader"></div>
                                </div>
                            </div>
                </div>


                @foreach ($days as $key => $value)
                    <div class="col-md-12 my-1">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-4 form-group">
                                <input class="form-check-input" type="checkbox" name="days"
                                       wire:model.lazy="selected.{{ $key }}.checked"
                                       id="flexCheckDefault{{ $key }}" />
                                <label class="form-check-label ml-2 mr-2" for="flexCheckDefault{{ $key }}">
                                    {{ __($value) }}
                                </label>


                            </div>
                            <div class="col-md-2">
                                <x-input-label :value="__('names.early-from')"></x-input-label>



                                <input type="time" wire:model.lazy="selected.{{ $key }}.early_start"
                                       id="{{ $key }}_start" class="form-control" />

                            </div>
                            <div class="col-md-2">
                                <x-input-label :value="__('names.from')"></x-input-label>

                                <input type="time" wire:model.lazy="selected.{{ $key }}.start"
                                       id="{{ $key }}_start" class="form-control" />

                            </div>
                            <div class="col-md-2">
                                <x-input-label :value="__('names.late-to')"></x-input-label>
                                <input type="time" wire:model.lazy="selected.{{ $key }}.late_start"
                                       id="{{ $key }}_start" class="form-control" />

                            </div>

                            <div class="col-md-2">
                                <x-input-label :value="__('names.to')"></x-input-label>
                                <input type="time" wire:model.lazy="selected.{{ $key }}.end"
                                       name="{{ $key }}_end" class="form-control" />

                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-3">
                    <div class="form-group">
                        <x-input-label :value="__('names.distance')"></x-input-label>
                        <input type="number" name="distance" wire:model.lazy="distance"
                               class="form-control @error('distance')
                                        is-invalid
                                    @enderror" />
                        @error('distance')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-input-label :value="__('names.offline-time')"></x-input-label>
                        <input type="number" name="offline" wire:model.lazy="offline"
                               class="form-control @error('offline')
                                        is-invalid
                                    @enderror" />
                        @error('offline')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-input-label :value="__('names.late-cost')"></x-input-label>
                        <input type="number"  name="late_cost" wire:model.lazy="late_cost"
                               class="form-control @error('late_cost')
                                        is-invalid
                                    @enderror" />
                        @error('late_cost')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-input-label :value="__('names.overtime-cost')"></x-input-label>
                        <input type="number"  name="overtime_cost" wire:model.lazy="overtime_cost"
                               class="form-control @error('overtime_cost')
                                        is-invalid
                                    @enderror" />
                        @error('overtime_cost')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="save">
            <div class="loader-cotnainer">
                <div class="loader"></div>
            </div>
        </div>
        <div class="modal-footer mt-2">
            <button type="button" class="btn btn-primary" wire:click.pervent="save">{{ __('names.save') }}</button>
        </div>
    </form>
    @livewire('map-modal', ['modal_id' => 'map', 'title_in' => __('names.map')], key(rand(99, 999999)))

</div>

@push('script')
    <script>
        window.addEventListener('load', function() {
            loadGoogleMaps();
        });

        function initMap() {
            const mapOptions = {
                center: {
                    lat: 0,
                    lng: 0
                },
                zoom: 2
            };

            const map = new google.maps.Map(document.getElementById('map'), mapOptions);

            let marker = new google.maps.Marker({
                position: {
                    lat: parseFloat("{{ $latitude ?? 30.03 }}"),
                    lng: parseFloat("{{ $longitude ?? 31.23 }}")
                },
                map: map
            });

            map.setZoom(10);
            map.panTo(marker.position);
            map.addListener('click', function(event) {
                if (marker) {
                    marker.setMap(null);
                }

                const clickedLocation = event.latLng;
                const lat = clickedLocation.lat();
                const lng = clickedLocation.lng();

                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map
                });

                console.log('Latitude: ' + lat + ', Longitude: ' + lng);
                var message = lat + "-" + lng;

                $.get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng +
                    '&key=AIzaSyBfjVre0paUOf4kvUNUPTNU3omF6iV-c5Q',
                    function(response) {
                        Livewire.emit("updateAddress", response['results'][2]['formatted_address']);
                    });

                Livewire.emit("updateLatAndLong", message);

            });
        }

        // Asynchronously load the Google Maps API with callback
        function loadGoogleMaps() {
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBfjVre0paUOf4kvUNUPTNU3omF6iV-c5Q&libraries=places&callback=initMap';
            script.defer = true;
            script.async = true;
            document.body.appendChild(script);
        }
    </script>
@endpush
