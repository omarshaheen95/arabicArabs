@extends('school.layout.container')

@section('title',$title)


@section('actions')
    <a href="{{route('school.teacher.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-plus"></i>
        {{t('Add Teacher')}}
    </a>
    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route('school.teacher.export')}}')">{{t('Export')}}</a></li>
            <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-2 mb-2">
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
                <th class="text-start">{{ t('Status') }}</th>
                <th class="text-start">{{ t('Active') }}</th>
                <th class="text-start">{{ t('Students Count') }}</th>
                <th class="text-start">{{ t('Last login') }}</th>
                <th class="text-start">{{ t('Active To') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('school.teacher.destroy') }}";
        var TABLE_URL = "{{route('school.teacher.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
            {data: 'approved', name: 'approved'},
            {data: 'active', name: 'active'},
            {data: 'students_count', name: 'students_count'},
            {data: 'last_login', name: 'last_login'},
            {data: 'active_to', name: 'active_to'},
            {data: 'actions', name: 'actions'}
        ];

    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


