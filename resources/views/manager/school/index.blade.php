@extends('manager.layout.container')

@section('title',$title)


@section('actions')
    @can('add schools')
        <a href="{{route('manager.school.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Add School')}}
        </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('export schools')
                <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route('manager.school.export')}}')">{{t('Export')}}</a></li>
            @endcan
            @can('school activation')
                    <li><a class="dropdown-item text-primary" href="#!" data-bs-toggle="modal" data-bs-target="#school_activation_modal">{{t('Activation')}}</a></li>
            @endcan
            @can('delete school')
                    <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan
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

        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Mobile')}}:</label>
            <input type="text" name="mobile" class="form-control" placeholder="{{t('Mobile')}}">
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2">{{t('Activation')}} :</label>
            <select name="active" id="status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Active')}}</option>
                <option value="2">{{t('Non-Active')}}</option>
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
                <th class="text-start">{{ t('School') }}</th>
                <th class="text-start">{{ t('Teachers Count') }}</th>
                <th class="text-start">{{ t('Students Count') }}</th>
                <th class="text-start">{{ t('Status') }}</th>
                <th class="text-start">{{ t('Last login') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="school_activation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{t('School Activation')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <form id="activation_form">
                        <div class="mb-2">
                            <label class="mb-2">{{t('Activation Status')}} :</label>
                            <select name="active" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                                <option></option>
                                <option value="1">{{t('Activate')}}</option>
                                <option value="2">{{t('Deactivate')}}</option>
                            </select>
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="btn_school_activation">{{t('Save')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('manager.school.destroy') }}";
        var TABLE_URL = "{{route('manager.school.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'school', name: 'school'},
            {data: 'teachers_count', name: 'teachers_count'},
            {data: 'students_count', name: 'students_count'},
            {data: 'active', name: 'active'},
            {data: 'last_login', name: 'last_login'},
            {data: 'actions', name: 'actions'}
        ];

        $(document).on('click', '#btn_school_activation',function(){
            $('#school_activation_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['activation_data'] = getFormDataAsObject('activation_form')
            data['_token'] = '{{csrf_token()}}'
            $('#activation_form').find('select').val('').trigger('change')
            $.ajax({
                url: "{{route('manager.school.activation')}}",
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


