@extends(getGuard().'.layout.container')

@section('title',$title)


@section('actions')

      @can('add teachers')
           <a href="{{route(getGuard().'.teacher.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
               <i class="la la-plus"></i>
               {{t('Add Teacher')}}
           </a>
      @endcan


    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">


            @can('export teachers')
                <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route(getGuard().'.teacher.export_teachers_excel')}}')">{{t('Export')}}</a></li>
            @endif

            @can('teachers activation')
                <li><a class="dropdown-item text-primary" href="#!" data-bs-toggle="modal" data-bs-target="#teacher_activation_modal">{{t('Activation')}}</a></li>
            @endif

            @can('teacher users unsigned')
                <li><a class="dropdown-item text-warning" href="#!" data-bs-toggle="modal" data-bs-target="#delete_students_modal">{{t('Unsigned Students')}}</a></li>
            @endcan

            @can('import files')
                <li><a class="dropdown-item" href="{{route(getGuard().'.import_files.create', ['type' => 'Teacher'])}}">{{t('Import')}}</a></li>
            @endcan
            @can('reset teachers passwords')
                <li><a class="dropdown-item" href="#!" data-bs-toggle="modal"
                       data-bs-target="#reset_passwords_modal">{{t('Reset Passwords')}}</a></li>
            @endcan
            @can('edit teachers')
                <li><a class="dropdown-item text-warning" href="#!"
                       data-filtered-action="{{route(getGuard().'.teacher.force-password-change')}}"
                       data-confirm="{{t('Password change will be enforced on every account matching the current filters. Continue?')}}">{{t('Force Password Change')}}</a></li>
            @endcan
            @can('delete teachers')
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan


        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1 mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label>{{t('Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Name')}}">
        </div>

        <div class="col-3 mb-2">
            <label>{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>

        <div class="col-2 mb-2">
            <label>{{t('Mobile')}}:</label>
            <input type="text" name="mobile" class="form-control" placeholder="{{t('Mobile')}}">
        </div>


        @if(guardIs('manager'))
            <div class="col-lg-3 mb-2">
                <label>{{t('School')}} :</label>
                <select name="school_id" class="form-select" data-control="select2" data-placeholder="{{t('Select School')}}" data-allow-clear="true">
                    <option></option>
                    @foreach($schools as $school)
                        <option value="{{$school->id}}">{{$school->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-lg-2 mb-2">
            <label>{{t('Student Status')}} :</label>
            <select name="student_status" id="student_status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Has students')}}</option>
                <option value="2">{{t('Has no students')}}</option>
                <option value="3">{{t('Has active students')}}</option>
                <option value="4">{{t('Has inactive students')}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label>{{t('Approval')}} :</label>
            <select name="approved" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Approved')}}</option>
                <option value="2">{{t('Under review')}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label>{{t('Activation')}} :</label>
            <select name="active" id="status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Active')}}</option>
                <option value="2">{{t('Non-Active')}}</option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>{{ t('Activation Date') }}:</label>
            <input id="active_to" class="form-control " placeholder="{{t('Select Activation Date')}}">
            <input type="hidden" id="start_active_to" name="start_active_to" value="">
            <input type="hidden" id="end_active_to" name="end_active_to" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>{{ t('Login Date') }}:</label>
            <input id="login_at" class="form-control " placeholder="{{t('Select Login Date')}}">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
        </div>

    </div>
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{$title}}
    </li>
@endpush


@section('content')
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start">{{ t('Teacher') }}</th>
                <th class="text-start">{{ t('Role') }}</th>
                <th class="text-start">{{ guardIs('manager')?t('School'): t('Students Count')}}</th>
                <th class="text-start">{{ t('Approval') }}</th>
                <th class="text-start">{{ t('Activation') }}</th>
                <th class="text-start">{{ t('Last login') }}</th>
                <th class="text-start">{{ t('Active To') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" tabindex="-1" id="teacher_activation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{t('Teacher Activation')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                   <form id="activation_form">
                       <div class="mb-2">
                           <label>{{t('Approved Status')}} :</label>
                           <select name="approved" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                               <option></option>
                               <option value="1">{{t('Approved')}}</option>
                               <option value="2">{{t('Under review')}}</option>
                           </select>
                       </div>
                       <div class="mb-2">
                           <label>{{t('Activation Status')}} :</label>
                           <select name="active" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                               <option></option>
                               <option value="1">{{t('Activate')}}</option>
                               <option value="2">{{t('Deactivate')}}</option>
                           </select>
                       </div>

                       <div class="mb-2">
                           <label>{{t('Active To')}} :</label>
                           <input class="form-control form-control-solid" id="active_to_date" name="active_to" value="" placeholder="{{t('Active to')}}"/>
                       </div>
                   </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="btn_teacher_activation">{{t('Save')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="delete_students_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{t('Unsigned Students')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                   <form id="delete_students_form">
                       <div class="mb-2">
                           <label>{{t('Students Type')}} :</label>
                           <select name="type" class="form-select" data-control="select2" data-placeholder="{{t('Select Type')}}" data-allow-clear="true">
                               <option></option>
                               <option value="1">{{t('All')}}</option>
                               <option value="2">{{t('Active')}}</option>
                               <option value="3">{{t('Inactive')}}</option>
                           </select>
                       </div>
                   </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="btn_delete_students">{{t('Save')}}</button>
                </div>
            </div>
        </div>
    </div>
    @can('reset teachers passwords')
        <div class="modal fade" tabindex="-1" id="reset_passwords_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{t('Reset Users Passwords')}}</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body d-flex flex-column">
                        <form id="reset_passwords_form">
                            @csrf
                            <div class="mb-2">
                                <label>{{t('New Password')}} :</label>
                                <input class="form-control" type="text" name="password">
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                        <button type="button" class="btn btn-primary" id="btn_reset_passwords">{{t('Update')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route(getGuard().'.teacher.destroy') }}";
        var TABLE_URL = "{{route(getGuard().'.teacher.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
            {data: 'role', name: 'role'},
            {data: 'school', name: 'school'},
            {data: 'approved', name: 'approved'},
            {data: 'active', name: 'active'},
            {data: 'last_login', name: 'last_login'},
            {data: 'active_to', name: 'active_to'},
            {data: 'actions', name: 'actions'}
        ];

        $('#active_to_date').flatpickr();
        initializeDateRangePicker('active_to');
        initializeDateRangePicker('login_at');

        $(document).on('click', '#btn_teacher_activation',function(){
            $('#teacher_activation_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['activation_data'] = getFormData('activation_form')
            data['_token'] = '{{csrf_token()}}'
            $('#activation_form').find('input').val('')
            $('#activation_form').find('select').val('').trigger('change')
            $.ajax({
                url: "{{route(getGuard().'.teacher.activation')}}",
                type: 'post',
                data: data,
                success: function(response){
                    hideLoadingModal()
                    table.DataTable().draw(true);
                    toastr.success(response.message);
                },
                error(error){
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message);
                }
            });
        });

        $(document).on('click', '#btn_delete_students',function(){
            $('#delete_students_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['delete_students'] = getFormData('delete_students_form')
            data['_token'] = '{{csrf_token()}}'
            $('#delete_students_form').find('select').val('').trigger('change')
            $.ajax({
                url: "{{route(getGuard().'.teacher.delete_students')}}",
                type: 'post',
                data: data,
                success: function(response){
                    hideLoadingModal()
                    table.DataTable().draw(true);
                    toastr.success(response.message);
                },
                error(error){
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message);
                }
            });
        });

        @can('reset users passwords')

        $('#btn_reset_passwords').click(function () {
            let formData = getFormData('reset_passwords_form')
            let data = getFilterData()
            $.each(formData, function (key, val) {
                data[key] = val;
            });

            if (data['password']){
                $('#reset_passwords_modal').modal('hide')
                showLoadingModal()
                $.ajax({
                    type: "POST", //we are using GET method to get data from server side
                    url: '{{route(getGuard().'.teacher.reset-passwords')}}', // get the route value
                    data: data,
                    success:function (result) {
                        hideLoadingModal()
                        toastr.success(result.message)
                        table.DataTable().draw(false);
                    },
                    error:function (error) {
                        hideLoadingModal()
                        toastr.error(error.responseJSON.message)
                    }
                })
            }
        })
        @endcan

    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


