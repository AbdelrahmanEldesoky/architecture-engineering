<div>
    <div class="section my-4 flex-column d-flex align-items-center employee-form rtl"  id="customers">
        <table >
            <tbody>
                <tr>
                    <th colspan="6">
                        <div class="cell-5">{{ __('names.personal-information') }}</div>
                    </th>
                    <th colspan="3">
                        <div class="cell-3">
                            {{ __('names.personal') }} {{ __('names.image') }}
                        </div>
                    </th>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">
                            {{ __('names.first-name') }}
                        </div>
                    </td>
                    <td colspan="5">
                        <div class="cell-4">
                            {{ $employee->first_name }}
                        </div>
                    </td>
                    <td class="position-relative" colspan="3" rowspan="10">
                        <div class="cell-3 employee-image-container d-flex justify-center align-items-center">
                            <img class="employee-image"
                                src=" {{ url('/storage/' . $employee->attachments?->where('type', 'personal_photo')?->last()?->path) }}" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">الاسم الثاني</div>
                    </td>
                    <td colspan="4">
                        <div class="cell-4">
                            {{ $employee->second_name }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="1">
                        <div class="cell-1">الاسم الاخير</div>
                    </td>
                    <td colspan="4">
                        <div class="cell-4">
                            {{ $employee->last_name }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="1">
                        <div class="cell-1">البريد الالكتروني</div>
                    </td>
                    <td colspan="4">
                        <div class="cell-4">
                            {{ $employee->user?->email }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="1">
                        <div class="cell-1">رقم الهوية</div>
                    </td>
                    <td colspan="4">
                        <div class="cell-4">
                            {{ $employee->info?->id_number }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">الرقم القومي</div>
                    </td>
                    <td colspan="4">
                        <div class="cell-4"> {{ $employee->info?->national_id }}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="1">
                        <div class="cell-1">تاريخ الميلاد</div>
                    </td>
                    <td colspan="4">
                        <div class="cell-4">{{ $employee->info?->birth_date }}</div>
                    </td>
                </tr>


                <tr>
                    <td colspan="1">
                        <div class="cell-1">رقم الجوال</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->phone }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">رقم الهوية</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->id_number }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">رقم حدود</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->border_no }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">رقم الجواز</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->passport_no }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">الجنسية</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ __('names.' . $employee->info?->nationality) }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">الجنس</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->gender }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">تاريخ انتهاء الهوية</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->end_id_number }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">تاريخ انتهاء التأمين الطبي</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->end_medical_insurance }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">تاريخ انتهاء الهيئة السعودية</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->end_saudi_authority }}</div>
                    </td>

                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">العوان الحالي</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">
                            {{ $employee->address }}
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">المحافظة</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">
                            {{ $employee->city?->name }}
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">العنوان بالتفصيل</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">
                            {{ $employee->address }} - {{ $employee->city?->name }} - {{ $employee->country?->name }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">العنوان في الدولة الام</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->motherAddress?->address }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">المحافظة</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->motherAddress?->city?->name }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">العنوان بالتفصيل</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->motherAddress?->address }} - {{ $employee->motherAddress?->city?->name }} - {{ $employee->country?->name }}</div>
                    </td>
                </tr>
                <tr>
                    <th colspan="9">
                        <div class="cell-9">البيانات البنكية</div>
                    </th>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">رقم الحساب</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->bank_account }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">الايبان البنكي</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->bank_iban }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">اسم البنك</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">{{ $employee->info?->bank_name }}</div>
                    </td>
                </tr>

                <tr>
                    <th colspan="9" class="violet">
                        <div class="cell-9">بيانات اتصال الطوارئ</div>
                    </th>
                </tr>
                <tr>
                    <td colspan="1">
                        <div class="cell-1">الاسم الكامل</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2">
                            {{ $employee->relative?->name }}
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">رقم تليفون</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2"> {{ $employee->relative?->phone }}</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1">صلة القرابة</div>
                    </td>
                    <td colspan="2">
                        <div class="cell-2"> {{ __('names.' . $employee->relative?->type) }}</div>
                    </td>
                </tr>
                <tr>
                    <th colspan="7">
                        <div class="cell-7">نموذج تأكيد واستلام المستندات</div>
                    </th>
                    <td colspan="1">
                        <div class="cell-1 text-center">موجود</div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center">غير موجود</div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            2 صورة شخصية 4*6 خلفيه بيضاء رسمية
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            نسختين سيرة ذاتية باللغة العربية وباللغة الإنجليزية محدثه حتى تاريخ 2023/5/1
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            نموذج الاتصال طبقا لنموذج 1 المرفق

                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            صوره جواز السفر محدث + صورة التأشيرة ورقم الحدود

                        </div>
                    </td>
                    <td colspan="1">
                        @if($employee->attachments->where('type', 'passport_photo')?->last()?->path != null && $employee->attachments->where('type', 'border_photo')?->last()?->path != null)
                        <div class="cell-1 text-center">&#10004;</div>
                        @else
                        <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                    <td colspan="1">
                        @if($employee->attachments->where('type', 'passport_photo')?->last()?->path == null || $employee->attachments->where('type', 'border_photo')?->last()?->path ==null)
                        <div class="cell-1 text-center">&#10004;</div>
                        @else
                        <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            صورة بطاقة القومي /الهوية + صورة رخصة السياقة

                        </div>
                    </td>
                    <td colspan="1">
                        @if($employee->attachments->where('type', 'national_photo')?->last()?->path != null )
                        <div class="cell-1 text-center">&#10004;</div>
                        @else
                        <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                    <td colspan="1">
                        @if($employee->attachments->where('type', 'national_photo')?->last()?->path == null)
                        <div class="cell-1 text-center">&#10004;</div>
                        @else
                        <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            هويه أحد الأقارب طبقا لنموذج الأقارب المرفق نموذج2
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>

                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            صوره المؤهل (يحتوي على الوجهة والخلف) موثق + بيان الدرجات +صورة كارنيه النقابة

                        </div>
                        <td colspan="1">
                            @if( $academic != 0)
                                <div class="cell-1 text-center">&#10004;</div>
                            @else
                                <div class="cell-1 text-center"></div>
                            @endif
                        </td>
                        <td colspan="1">
                            @if($academic == 0)
                                <div class="cell-1 text-center">&#10004;</div>
                            @else
                                <div class="cell-1 text-center"></div>
                            @endif
                        </td>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">

                            صورة اعتماد الموظف لدى جهة العمل (الهيئة السعودية محاسبين / مهندسين /الخ...) كارنيه + شهادة
                            اعتماد

                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            شهادات الخبرة السابقة مطابقه للسيرة الذاتية

                        </div>
                    </td>
                    <td colspan="1">
                        @if( $experience != 0)
                            <div class="cell-1 text-center">&#10004;</div>
                        @else
                            <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                    <td colspan="1">
                        @if($experience == 0)
                            <div class="cell-1 text-center">&#10004;</div>
                        @else
                            <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            شهادة مدد واجورفي حاله العمل بالسعودية أي كان المسمى الوظيفي

                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            شهادات الدورات التدريبية في نفس مجال العمل اوفي غيره
                        </div>
                    </td>
                    <td colspan="1">
                        @if(  $cources != 0)
                            <div class="cell-1 text-center">&#10004;</div>
                        @else
                            <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                    <td colspan="1">
                        @if( $cources == 0)
                            <div class="cell-1 text-center">&#10004;</div>
                        @else
                            <div class="cell-1 text-center"></div>
                        @endif
                    </td>
                  </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            خطاب العرض الوظيفي + خطاب مباشرة العمل

                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            العقد بينه وبين المكتب

                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>

                <tr style="text-align: right">
                    <td colspan="7">
                        <div class="cell-7">
                            أي خطابات تخص استلامات العهد
                        </div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                    <td colspan="1">
                        <div class="cell-1 text-center"></div>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>

</div>

<hr>


    <div class="d-flex flex-row-reverse">
            <button type="button" class="btn btn-primary d-flex align-items-center" onclick="printData()">
                {{ __('names.print') }}
            </button>
            <button class="btn btn-primary light mx-2 d-flex align-items-center" type="button" onclick="goBack()" data-bs-toggle="collapse">
        {{ __('رجوع') }}
    </button>
    </div>

<script>
    function goBack() {
  history.back();
}


function printData(){
$table=document.getElementById('customers');
$table.style.fontSize="11px";
document.getElementsByClassName('employee-image')[0].style.width="300px"
document.getElementsByClassName('employee-image')[0].style.height="250px"
setTimeout(function (){
    window.print()
},1000)


}
</script>
