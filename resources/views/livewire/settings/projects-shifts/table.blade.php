<div>
    <div class="row">
        <div class="col-md-9">
            <h4>
                {{ __('names.projects-shifts-setting') }}
            </h4>
        </div>
        <div class="col-md-3">
            @if (havePermissionTo('attendance.projectsShift.create'))
                <a href="{{ route('admin.attendance.projectsShifts.create') }}" class="btn btn-primary w-100">
                    <i class="bx bx-plux-circle"></i>
                    {{ __('names.add-project-shift') }}
                </a>
            @endif
        </div>

        <div class="col-md-12 mt-2">
            <section class="section">
                <table class="table table-borderless">
                    <thead>
                    <th>
                        #
                    </th>
                    <th>
                        {{ __('names.name') }}
                    </th>
                    <th>
                        {{ __('names.setting') }}
                    </th>
                    </thead>
                    <tbody>
                    @forelse ($shifts as $key=>$shift)
                        <tr>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                {{ $shift->name }}
                            </td>
                            <td>
                                <div class="">
                                    <div class=" limit-2">
                                        @if (havePermissionTo('attendance.projectsShift.edit'))
                                            <a href="{{ route('admin.attendance.projectsShifts.create', ['shift_id' => $shift->id]) }}"
                                               class="px-1">
                                                <i class='bx bxs-edit bx-sm text-gray'></i>
                                            </a>
                                    @endif
                                    @if (havePermissionTo('attendance.projectsShift.delete'))
                                        <a wire:click.prevent="delete('{{ $shift->id }}')" class="px-1"
                                                   type="button">
                                                    <i class='bx bx-trash bx-sm text-danger'></i>
                                                </a>
                                            @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @empty
                    @endforelse
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>
