<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/table2excel.js"></script>

    @include('admin.inc._head')

</head>

<body>
    <div id="app" class="layout">
        {{ havePermissionTo('123') }}
        @include('admin.inc._side')
        {{-- <div class="body-container"> --}}
            @include('admin.inc._navbar')
            <main class="main-view" style="min-height: 100vh">
                {{ $slot }}
            </main>
            <div class="icon-circuit-board"></div>
        {{-- </div> --}}
    </div>

    <script type="module">
        // // Get a reference to the file input element
        // const fileInputs = document.querySelectorAll('input[type="file"]');
        // fileInputs.forEach(function (fileInput){
        //     FilePond.create(fileInput);
        // });

        // function toggleLog() {
        //
        // }

        window.addEventListener('toastr', event => {
            toastr[event.detail.type](event.detail.message,
                event.detail.title ?? ''), toastr.options = {
                "closeButton": true,
                "progressBar": true,
                'timeOut': 10000
            }
        });
        window.addEventListener('closeModal', (event) => {
            // console.log(event.detail.id);
            var myModalEl = document.getElementById(event.detail.id);
            var myModal = bootstrap.Modal.getInstance(myModalEl);
            myModal.hide();
        });

        window.addEventListener('showDeleteConfirmation', event => {
            Swal.fire({
                title: '{{ __('message.sure') }}',
                text: '{{ __('message.delete-warn') }}',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('names.cancel') }}',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#004693',
                confirmButtonText: '{{ __('message.delete-confirm') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('confirmDelete', event.detail.id);
                }
            });
        });

        window.addEventListener('closeModal', (event) => {
            // console.log(event.detail.id);
            var myModalEl = document.getElementById(event.detail.id);
            var myModal = bootstrap.Modal.getInstance(myModalEl);
            myModal.hide();
        });


        window.addEventListener('openModal', (event) => {
            // console.log(event.detail.id);
            var myModalEl = document.getElementById(event.detail.id);
            var myModal = bootstrap.Modal.getInstance(myModalEl);
            myModal.show();
        });

        window.addEventListener('showDeleteConfirmation', event => {
            Swal.fire({
                title: '{{ __('message.sure') }}',
                text: '{{ __('message.delete-warn') }}',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('names.cancel') }}',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#004693',
                confirmButtonText: '{{ __('message.delete-confirm') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('confirmDelete', event.detail.id,event.detail.type);
                }
            });
        });

        // window.addEventListener('initMap', event => {
        //     loadGoogleMaps();
        // });

        // function initMap() {
        //     const mapOptions = {
        //         center: {
        //             lat: 0,
        //             lng: 0
        //         },
        //         zoom: 2
        //     };

        //     const map = new google.maps.Map(document.getElementById('map'), mapOptions);

        //     let marker = new google.maps.Marker();

        //     map.addListener('click', function(event) {
        //         if (marker) {
        //             marker.setMap(null);
        //         }


        //         const clickedLocation = event.latLng;
        //         const lat = clickedLocation.lat();
        //         const lng = clickedLocation.lng();

        //         marker = new google.maps.Marker({
        //             position: clickedLocation,
        //             map: map
        //         });

        //         console.log('Latitude: ' + lat + ', Longitude: ' + lng);
        //         var message = lat + "-" + lng;
        //         Livewire.emit("updateLatAndLong", message);

        //     });
        // }

        // // Asynchronously load the Google Maps API with callback
        // function loadGoogleMaps() {
        //     const script = document.createElement('script');
        //     script.src =
        //         'https://maps.googleapis.com/maps/api/js?key=AIzaSyBfjVre0paUOf4kvUNUPTNU3omF6iV-c5Q&libraries=places&callback=initMap';
        //     script.defer = true;
        //     script.async = true;
        //     document.body.appendChild(script);
        // }
    </script>


    <livewire:scripts />

    <script type="text/javascript">
         function demoFromHTML() {
            // var pdf = new jsPDF()
            // source can be HTML-formatted string, or a reference
            // to an actual DOM element from which the text will be scraped.
            return new Promise((resolve,reject)=>{
                var element = document.getElementById('customers');
                console.log(element);
                element.style.fontSize='10px';
                var opt = {
                    margin:       10,
                    filename:     'myfile.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2 },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                    pagebreak: { avoid: "tr", mode: "css", before: "#nextpage1" },


                };

// New Promise-based usage:

                html2pdf().set(opt).from(element).save();
                resolve();
            }).then(()=>{
                var element = document.getElementById('customers');
                element.style.fontSize='1rem';
            })

// Old monolithic-style usage:
            // var options = {
            //     applyImageFit: true
            // }
            // // we support special element handlers. Register them with jQuery-style
            // // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
            // // There is no support for any other type of selectors
            // // (class, of compound) at this time.
            // specialElementHandlers = {
            //     // element with id of "bypass" - jQuery style selector
            //     '#bypassme': function(element, renderer) {
            //         // true = "handled elsewhere, bypass text extraction"
            //         return true
            //     }
            // };
            // margins = {
            //     top: 80,
            //     bottom: 60,
            //     left: 40,
            //     width: 522
            // };
            // // all coords and widths are in jsPDF instance's declared units
            // // 'inches' in this case
            // pdf.fromHTML(
            //     source, // HTML string or DOM elem ref.
            //     margins.left, // x coord
            //     margins.top, {// y coord
            //         'width': margins.width, // max width of content on PDF
            //         'elementHandlers': specialElementHandlers
            //     },
            //     function(dispose) {
            //         // dispose: object with X, Y of the last line add to the PDF
            //         //          this allow the insertion of new lines after html
            //         pdf.save('Test.pdf');
            //     }
            //     , margins);
        }

         function exportExcel(){
                 var table2excel = new Table2Excel();
                 table2excel.export(document.getElementById('customers'));
    }
    </script>
    @stack('script')

</body>

</html>


{{-- <div id="toast-container" class="toast-top-right"> --}}
{{--    <div class="toast toast-success" aria-live="polite" style="display: block;"> --}}
{{--        <div class="toast-message">تم إضافة نوع الوظيفة بنجاح</div> --}}
{{--    </div> --}}
{{-- </div> --}}
