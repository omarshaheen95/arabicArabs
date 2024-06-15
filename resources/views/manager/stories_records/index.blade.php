@extends('manager.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>

        <ul class="dropdown-menu">
            @can('export user records')
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('{{route('manager.stories_records.export_excel')}}')">{{t('Export')}}</a></li>
            @endcan

            @can('delete user records')
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan

        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-2 mb-2">
            <label>{{t('Student ID')}}:</label>
            <input type="text" name="user_id" class="form-control direct-search" placeholder="{{t('Student ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label>{{t('Student Name')}}:</label>
            <input type="text" name="user_name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label>{{t('Student Email')}}:</label>
            <input type="text" name="user_email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>

        <div class="col-lg-3 mb-2">
            <label>{{t('Student Grade')}} :</label>
            <select name="student_grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{$grade->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="">{{t('School')}}:</label>
            <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select School')}}">
                <option></option>
                @foreach($schools as $school)
                    <option value="{{$school->id}}">{{$school->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
            <label class="">{{t('Teacher')}}:</label>
            <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Teacher')}}">
                <option></option>
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class="">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @foreach(schoolSections() as $section)
                    <option value="{{$section}}">{{$section}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class="">{{t('Activation')}}:</label>
            <select class="form-select" name="user_status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                <option value="active">{{t('Active')}}</option>
                <option value="expire">{{'Expired'}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label>{{t('Story Grade')}} :</label>
            <select name="grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach(storyGradesSys() as $key => $grade)
                    <option value="{{$key}}">{{$grade}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label>{{t('Story')}} :</label>
            <select  name="story_id" id="story_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Story')}}" data-allow-clear="true">
                <option></option>
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="">{{t('Status')}}:</label>
            <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                @foreach(\App\Models\UserLesson::STATUS_MODEL as $key => $status)
                    <option value="{{$key}}">{{t($status)}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label>{{t('Submitted Date')}} :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="{{t('Pick date range')}}" id="date_range"/>
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
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
                {{--                <th class="text-start">{{ t('ID') }}</th>--}}
                <th class="text-start">{{ t('Student') }}</th>
                <th class="text-start">{{ t('School') }}</th>
                <th class="text-start">{{ t('Story') }}</th>
                <th class="text-start">{{ t('Status') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>


@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>

    <script>
        var DELETE_URL = "{{ route('manager.stories_records.destroy') }}";
        var TABLE_URL = "{{ route('manager.stories_records.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'story', name: 'story'},
            {data: 'status', name: 'status'},
            {data: 'actions', name: 'actions'},
        ];
        initializeDateRangePicker();

    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>

    <script>
        getTeacherBySchool('teacher_school_id')
        getSectionBySchool()
        getSectionByTeacher()
        getStoriesByGrade()
    </script>
@endsection


