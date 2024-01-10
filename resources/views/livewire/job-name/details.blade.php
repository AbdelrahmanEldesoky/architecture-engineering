<div class="container-fluid ">
    <div class="row my-3 d-flex">
    </div>

    <div>
        
        <header class="d-flex gap-4 align-items-center mb-4">
            <h4> {{$jobNames->name}}</h4>

            @if ($jobNames->active)
            <span class="status active">
                {{ __('names.active') }}
            </span>
            @else
            <span class="status stopped">
                {{ __('names.in-active') }}
            </span>
            @endif
        </header>

        @include('admin.jobs.details', ['class' => 'job_name'])

        <div class="section d-flex gap-4">
            <h5 class="limit-4">الوصف</h5>
            <p> {{$jobNames->description}}</p>
        </div>
        
    </div>