@extends('school.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!"
                   onclick="excelExport('{{route('school.stories_tests.export_excel')}}')">{{t('Export')}}</a></li>
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Student ID')}}:</label>
            <input type="text" name="user_id" class="form-control direct-search" placeholder="{{t('Student ID')}}">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Student Name')}}:</label>
            <input type="text" name="user_name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Student Email')}}:</label>
            <input type="text" name="user_email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Student Grade')}} :</label>
            <select name="student_grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Teacher')}}:</label>
            <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Teacher')}}">
                <option></option>
                @foreach($teachers as $teacher)
                    <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Gender')}}:</label>
            <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Gender')}}">
                <option></option>
                <option value="Boy">{{t('Boy')}}</option>
                <option value="Girl">{{'Girl'}}</option>
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2">{{t('Activation')}}:</label>
            <select class="form-select" name="user_status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                <option value="active">{{t('Active')}}</option>
                <option value="expire">{{'Expired'}}</option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2">{{t('Grade')}} :</label>
            <select name="grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach(storyGradesSys() as $key => $grade)
                    <option value="{{$key}}">{{$grade}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Story')}} :</label>
            <select  name="story_id" id="story_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Story')}}" data-allow-clear="true">
                <option></option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Status')}} :</label>
            <select name="status" id="status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="Pass">{{t('Pass')}}</option>
                <option value="Fail">{{t('Fail')}}</option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Submitted Date')}} :</label>
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
                <th class="text-start">{{ t('Student') }}</th>
                <th class="text-start">{{ t('Information') }}</th>
                <th class="text-start">{{ t('Story') }}</th>
                <th class="text-start">{{ t('Result') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>


@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>
    <script>
        var TABLE_URL = "{{ route('school.stories_tests.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'story', name: 'story'},
            {data: 'result', name: 'result'},
            {data: 'actions', name: 'actions'},
        ];
        initializeDateRangePicker();
        getSectionByTeacher()
        getStoriesByGrade()
    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


