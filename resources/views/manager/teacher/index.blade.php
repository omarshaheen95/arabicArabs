@extends('manager.layout.container')

@section('title',$title)


@section('actions')
    @can('add teachers')
    <a href="{{route('manager.teacher.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
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
                <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route('manager.teacher.export')}}')">{{t('Export')}}</a></li>
            @endcan
            @can('teachers activation')
                    <li><a class="dropdown-item text-primary" href="#!" data-bs-toggle="modal" data-bs-target="#teacher_activation_modal">{{t('Activation')}}</a></li>
            @endcan
            @can('teacher users unsigned')
                    <li><a class="dropdown-item text-warning" href="#!" data-bs-toggle="modal" data-bs-target="#delete_students_modal">{{t('Unsigned Students')}}</a></li>
            @endcan

            @can('delete teachers')
                    <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan
{{--            <li><a class="dropdown-item" href="{{route('manager.import.teachers_import_view')}}">{{t('Import')}}</a></li>--}}
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1 mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Name')}}">
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Mobile')}}:</label>
            <input type="text" name="mobile" class="form-control" placeholder="{{t('Mobile')}}">
        </div>


        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('School')}} :</label>
            <select name="school_id" class="form-select" data-control="select2" data-placeholder="{{t('Select School')}}" data-allow-clear="true">
                <option></option>
                @foreach($schools as $school)
                    <option value="{{$school->id}}">{{$school->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 mb-2">
            <label class="mb-2">{{t('Students')}} :</label>
            <select name="student_status" id="student_status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Has students')}}</option>
                <option value="2">{{t('Has no students')}}</option>
                <option value="3">{{t('Has active students')}}</option>
                <option value="4">{{t('Has inactive students')}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2">{{t('Approval')}} :</label>
            <select name="approved" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Approved')}}</option>
                <option value="2">{{t('Under review')}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2">{{t('Activation')}} :</label>
            <select name="active" id="status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Active')}}</option>
                <option value="2">{{t('Non-Active')}}</option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2">{{ t('Activation Date') }}:</label>
            <input id="active_to" class="form-control " placeholder="{{t('Select Activation Date')}}">
            <input type="hidden" id="start_active_to" name="start_active_to" value="">
            <input type="hidden" id="end_active_to" name="end_active_to" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2">{{ t('Login Date') }}:</label>
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
                <th class="text-start">{{ t('School') }}</th>
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
                           <label class="mb-2">{{t('Approved Status')}} :</label>
                           <select name="approved" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                               <option></option>
                               <option value="1">{{t('Approved')}}</option>
                               <option value="2">{{t('Under review')}}</option>
                           </select>
                       </div>
                       <div class="mb-2">
                           <label class="mb-2">{{t('Activation Status')}} :</label>
                           <select name="active" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                               <option></option>
                               <option value="1">{{t('Activate')}}</option>
                               <option value="2">{{t('Deactivate')}}</option>
                           </select>
                       </div>

                       <div class="mb-2">
                           <label class="mb-2">{{t('Active To')}} :</label>
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
                           <label class="mb-2">{{t('Students Type')}} :</label>
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
@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('manager.teacher.destroy') }}";
        var TABLE_URL = "{{route('manager.teacher.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
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
            data['activation_data'] = getFormDataAsObject('activation_form')
            data['_token'] = '{{csrf_token()}}'
            $('#activation_form').find('input').val('')
            $('#activation_form').find('select').val('').trigger('change')
            $.ajax({
                url: "{{route('manager.teacher.activation')}}",
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
            data['delete_students'] = getFormDataAsObject('delete_students_form')
            data['_token'] = '{{csrf_token()}}'
            $('#delete_students_form').find('select').val('').trigger('change')
            $.ajax({
                url: "{{route('manager.teacher.user-unsigned')}}",
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

    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


