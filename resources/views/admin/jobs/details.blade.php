<div class="nav nav-tabs mt-2" id="myTab" role="tablist">

    @if (havePermissionTo('jobNames.view'))
        <a class="mx-1 active"
            @if (isset($class) && $class == 'job_name') href="#content-03" id="tab-03" data-toggle="tab"  role="tab"
           aria-controls="content-03" aria-selected="true"
           @else
               href="{{ route('admin.job_names.details') }}" @endif>
            التفاصيل
        </a>

        <a class=" mx-1"
            @if (isset($class) && $class == 'job_name') href="{{ route('admin.job_names.index') }}" id="tab-4" data-toggle="tab"  role="tab"
           aria-controls="content-04" aria-selected="true"
           @else
               href="{{ route('admin.job_names.index') }}" @endif>
            {{ __('names.job-name') }}
        </a>
    @endif

</div>
