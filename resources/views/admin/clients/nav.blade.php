<div class="nav nav-tabs mt-2 static-4" id="myTab" role="tablist">

        <a class="mx-1  @if (isset($class) && $class == 'client') active @endif"
            @if (isset($class) && $class == 'client') href="#content-03" id="tab-03" data-toggle="tab"  role="tab"
           aria-controls="content-03" aria-selected="true"
           @else
               href="{{ route('admin.clients.index') }}" @endif>
            {{ __('العملاء') }}
        </a>

</div>
