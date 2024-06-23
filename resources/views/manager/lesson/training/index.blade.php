{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('manager.layout.container')
@section('title',$title)
@section('actions')
    <a href="{{route('manager.lesson.training.edit', [$lesson->id])}}" target="_blank" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-edit"></i>
        {{t('Edit Questions')}}
    </a>
@endsection
@section('filter')
    <div class="row">
        <div class="col-1 mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Question')}}:</label>
            <input type="text" name="content" class="form-control direct-search" placeholder="{{t('Question')}}">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Type')}} :</label>
            <select name="type" id="type" class="form-select" data-control="select2" data-placeholder="{{t('Select Type')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('True&False')}}</option>
                <option value="2">{{t('Choose')}}</option>
                <option value="3">{{t('Match')}}</option>
                <option value="4">{{t('Sort Words')}}</option>
            </select>
        </div>



    </div>

@endsection

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{$title}}
    </li>
    <li class="breadcrumb-item">
        {{$lesson->name}}
    </li>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-row-bordered gy-5" id="datatable">
                <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="text-start"></th>
                    <th class="text-start">{{ t('Question') }}</th>
                    <th class="text-start">{{ t('Type') }}</th>
                    <th class="text-start">{{ t('Actions') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
@section('script')
    <script>
        var DELETE_URL = "{{route('manager.lesson.training.delete-question') }}";
        var TABLE_URL = "{{route('manager.lesson.training.index', $lesson) }}";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'content', name: 'content'},
            {data: 'type_name', name: 'type_name'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection
