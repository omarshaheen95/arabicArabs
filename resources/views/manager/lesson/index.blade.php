{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('manager.layout.container')

@section('title',$title)


@section('actions')
    @can('add lessons')
        <a href="{{route('manager.lesson.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Add Lesson')}}
        </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('export lessons')
                <li><a class="dropdown-item" href="#!" onclick="excelExport('{{route('manager.lesson.export')}}')">{{t('Export')}}</a></li>
            @endcan
            @can('delete lessons')
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
        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Grade')}} :</label>
            <select name="grade" id="grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}" data-allow-clear="true">
                <option></option>
                @foreach($grades as  $grade)
                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Level')}} :</label>
            <select name="level" id="level" class="form-select" data-control="select2" data-placeholder="{{t('Select Level')}}" data-allow-clear="true">
                <option></option>
                @foreach(range(1,12) as  $level)
                    <option value="{{$level}}">{{$level}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Lesson Type')}} :</label>
            <select name="lesson_type" id="lesson_type" class="form-select" data-control="select2" data-placeholder="{{t('Select Lesson Type')}}" data-allow-clear="true">
                <option></option>
                @foreach($lesson_types as  $lesson_type)
                    <option value="{{$lesson_type}}">{{$lesson_type}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Section Type')}} :</label>
            <select name="section_type" id="section_type" class="form-select" data-control="select2" data-placeholder="{{t('Select Section Type')}}" data-allow-clear="true">
                <option></option>
                @foreach($section_types as  $section_type)
                    <option value="{{$section_type}}">{{$section_type}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
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
                <th class="text-start">{{ t('Grade') }}</th>
                <th class="text-start">{{ t('Level') }}</th>
                <th class="text-start">{{ t('Activation') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection


@section('script')

    <script>
        var DELETE_URL = "{{route('manager.lesson.destroy') }}";
        var TABLE_URL = "{{route('manager.lesson.index') }}";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'grade', name: 'grade'},
            {data: 'level', name: 'level'},
            {data: 'active', name: 'active'},
            {data: 'actions', name: 'actions'}
        ];


    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>
@endsection



