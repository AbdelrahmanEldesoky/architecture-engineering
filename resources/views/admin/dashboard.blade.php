<x-admin-app>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('names.dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($count_bitrhday != 0 && $employee_show != 0)
        <div class="container-fluid ">
            <div class="row section my-2">
                <x-table :responsive="true">
                    اعياد ميلاد اليوم   :  {{$date_day}}
                    <thead>
                        <th scope="col">اسم الموظف</th>
                        <th scope="col">الفرع</th>
                        <th scope="col">النوع الوظيفي</th>
                        <th scope="col">المسمي الوظيفي</th>
                    </thead>
                    <tbody>
                    @foreach ($Employee_birthday as $Employee)
                      <tr> 
                        <td>{{$Employee->first_name}} {{$Employee->second_name}} {{$Employee->last_name}}</td>
                        <td>{{$Employee->branchName()}}</td>
                        <td>{{$Employee->employmentData?->typeName?->name}}</td>
                        <td>{{$Employee->employmentData?->jobName?->name}}</td>
                      </tr>
                    @endforeach
                    </tbody>
                </x-table>
            </div>
        </div>
        @endif
    </div>
</div>
</x-admin-app>




