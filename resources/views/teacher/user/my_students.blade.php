@extends('teacher.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!" onclick="cardsExport(true)">{{t('Cards')}}</a></li>
            <li><a class="dropdown-item" href="#!"
                   onclick="excelExport('{{route('teacher.user.export_students_excel')}}')">{{t('Export')}}</a></li>
            <li><a class="dropdown-item d-none checked-visible" href="#!" onclick="$('#update_learning_years_modal').modal('show')" >{{t('Update Learning Years')}}</a></li>
            <li><a class="dropdown-item text-danger d-none checked-visible" id="delete_assign">{{t('Unsigned')}}</a></li>

            {{--            <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>--}}
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Student Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2">{{t('Learning Years')}} :</label>
            <select name="year_learning" class="form-select" data-control="select2"
                    data-placeholder="{{t('Select Year')}}" data-allow-clear="true">
                <option></option>
                @foreach(range(0,12) as $value)
                    <option value="{{ $value }}">{{$value == 0 ? '-':$value }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Grade')}} :</label>
            <select name="grade_d" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Package')}}:</label>
            <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Package')}}">
                <option></option>
                @foreach($packages as $package)
                    <option value="{{$package->id}}">{{$package->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @foreach(teacherSections() as $section)
                    <option value="{{$section}}">{{$section}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2">{{ t('Register Date') }}:</label>
            <input id="register_date" class="form-control " placeholder="{{t('Select Register Date')}}">
            <input type="hidden" id="start_register_date" name="start_register_date" value="">
            <input type="hidden" id="end_register_date" name="end_register_date" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2">{{ t('Login Date') }}:</label>
            <input id="login_at" class="form-control " placeholder="{{t('Select Login Date')}}">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Activation')}}:</label>
            <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                <option value="active">{{t('Active')}}</option>
                <option value="expire">{{'Expired'}}</option>
            </select>
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
                <th class="text-start">{{ t('Student') }}</th>
                <th class="text-start">{{ t('Information') }}</th>
                <th class="text-start">{{ t('Dates') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="update_learning_years_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{t('Update Learning Years')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <div class="">
                        <label class="mb-2">{{t('Learning Years')}} :</label>
                        <select id="learning_years" class="form-select" data-control="select2" data-placeholder="{{t('Select Learning Year')}}" data-allow-clear="true">
                            @foreach(range(0,12) as $value)
                                <option value="{{ $value }}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="update_learning_year" >{{t('Update')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('script')

    <script>
        var DELETE_URL = '{{route('teacher.student.delete_student_assign')}}';
        var TABLE_URL = "{{ route('teacher.student.my_students') }}";
        var STUDENT_CARD_URL = "{{route('teacher.student.student-cards-export')}}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];


        $('#delete_assign').on('click',function () {
            showAlert('{{t('Delete Assign')}}','{{t('Do you really want to delete assign students?')}}','warning','{{t('Yes')}}','{{t('No')}}',(callback)=>{
                if (callback){
                    showLoadingModal()
                    $.ajax({
                        url: DELETE_URL,
                        type: 'POST',
                        data: {
                            '_token': '{{csrf_token()}}',
                            user_id: getSelectedRows(),
                        },
                        success: function (response) {
                            table.DataTable().draw(true);
                            toastr.success(response.message);
                            hideLoadingModal()
                        },
                        error: function (error) {
                            toastr.error(error.responseJSON.message);
                            hideLoadingModal()
                        }
                    });
                }
            })
        })
        $('#update_learning_year').on('click',function () {
            $('#update_learning_years_modal').modal('hide')
            showLoadingModal()
            $.ajax({
                url: "{{route('teacher.student.updateLearningYears')}}",
                type: 'POST',
                data: {
                    '_token': '{{csrf_token()}}',
                    user_id: getSelectedRows(),
                    learning_years: $('#learning_years').val()
                },
                success: function (response) {
                    table.DataTable().draw(true);
                    toastr.success(response.message);
                    hideLoadingModal()
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                    hideLoadingModal()
                }
            });
        })
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


