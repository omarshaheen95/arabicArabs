@extends('manager.layout.container')

@section('title')
    {{t('Managers')}}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item text-muted">{{t('Managers')}}</li>
@endpush

@section('actions')
    @can('add managers')
        <a href="{{ route('manager.manager.create') }}" class="btn btn-primary btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            {{t('Add New Manager')}}
        </a>
    @endcan


    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('export managers')
                <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route('manager.manager.export')}}')">{{t('Export')}}</a></li>
            @endcan
            @can('delete managers')
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" id="id" name="id" class="form-control kt-input" placeholder="E.g: 4590">
        </div>

        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Name')}}:</label>
            <input type="text" id="name" name="name" class="form-control direct-search" placeholder="{{t('Name')}}">
        </div>

        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Email')}}:</label>
            <input type="text" id="email" name="email" class="form-control kt-input" placeholder="{{t('Email')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Status')}}:</label>
            <select name="active" class="form-control form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-hide-search="true" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Active')}}</option>
                <option value="2">{{t('Not Active')}}</option>
            </select>
        </div>
    </div>

@endsection




@section('content')
    <!--begin::Hidden Form-->
    <form action="" method="post" id="filter_action" style="display: none;">
        @csrf
        <input type="hidden" name="row_id[]" id="row_id">
    </form>
    <div class="row">

        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start">{{t('ID')}}</th>
                <th class="text-start">{{t('Name')}}</th>
                <th class="text-start">{{t('Email')}}</th>
                <th class="text-start">{{t('Last Login')}}</th>
                <th class="text-start">{{t('Status')}}</th>
                <th class="text-start">{{t('Actions')}}</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('manager.manager.destroy')}}";
        var TABLE_URL = "{{route('manager.manager.index')}}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'last_login', name: 'last_login'},
            {data: 'active', name: 'active'},
            {data: 'actions', name: 'actions'}
        ];


    </script>
    <script src={{asset('assets_v1/js/datatable.js')}}?v={{time()}}></script>

@endsection


