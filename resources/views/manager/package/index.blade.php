@extends('manager.layout.container')

@section('title',$title)


@section('actions')
    @can('add packages')
        <a href="{{route('manager.package.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Add Package')}}
        </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('delete packages')
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
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Days')}}:</label>
            <input type="number" name="days" class="form-control direct-search" placeholder="{{t('Days')}}">
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Price')}}:</label>
            <input type="number" name="price" class="form-control direct-search" placeholder="{{t('Price')}}">
        </div>

        <div class="col-lg-3 mb-2">
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
                <th class="text-start">{{ t('Name') }}</th>
                <th class="text-start">{{ t('Days') }}</th>
                <th class="text-start">{{ t('Price') }}</th>
                <th class="text-start">{{ t('Users Count') }}</th>
                <th class="text-start">{{ t('Activation') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('manager.package.destroy') }}";
        var TABLE_URL = "{{route('manager.package.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'days', name: 'days'},
            {data: 'price', name: 'price'},
            {data: 'users_count', name: 'users_count'},
            {data: 'active', name: 'active'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


