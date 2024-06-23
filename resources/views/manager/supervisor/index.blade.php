@extends('manager.layout.container')

@section('title',$title)


@section('actions')
    @can('add supervisors')
    <a href="{{route('manager.supervisor.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-plus"></i>
        {{t('Add Supervisor')}}
    </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
                @can('export supervisors')
                    <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route('manager.supervisor.export')}}')">{{t('Export')}}</a></li>
                @endcan
                @can('supervisors activation')
                    <li><a class="dropdown-item text-primary" href="#!" data-bs-toggle="modal" data-bs-target="#activation_modal">{{t('Activation')}}</a></li>
                @endcan
                @can('delete supervisor')
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
            <label class="mb-2">{{t('Approval')}} :</label>
            <select name="approved" id="approved" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
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
                <th class="text-start">{{ t('Supervisor') }}</th>
                <th class="text-start">{{ t('Teachers Count') }}</th>
                <th class="text-start">{{ t('Approval') }}</th>
                <th class="text-start">{{ t('Activation') }}</th>
                <th class="text-start">{{ t('Last login') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" tabindex="-1" id="activation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{t('Supervisor Activation')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <form id="activation_form">
                        <div class="mb-2">
                            <label class="mb-2">{{t('Approval Status')}} :</label>
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
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{t('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="btn_activation">{{t('Save')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('manager.supervisor.destroy') }}";
        var TABLE_URL = "{{route('manager.supervisor.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'supervisor_data', name: 'supervisor_data'},
            {data: 'teachers_count', name: 'teachers_count'},
            {data: 'approved', name: 'approved'},
            {data: 'active', name: 'active'},
            {data: 'last_login', name: 'last_login'},
            {data: 'actions', name: 'actions'}
        ];
        $(document).on('click', '#btn_activation',function(){
            $('#activation_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['activation_data'] = getFormDataAsObject('activation_form')
            data['_token'] = '{{csrf_token()}}'
            $('#activation_form').find('select').val('').trigger('change')
            $.ajax({
                url: "{{route('manager.supervisor.activation')}}",
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


