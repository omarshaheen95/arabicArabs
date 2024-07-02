{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('manager.layout.container')
@section('title',$title)


@section('actions')

    <div class="dropdown with-filter">


        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>

        <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#!"
                       onclick="excelExport('{{route('supervisor.students_works.export')}}')">{{t('Export')}}</a>
                </li>


        </ul>
    </div>

@endsection
@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label class="mb-2">{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-lg-2  mb-2">
            <label class="mb-2">{{t('Student Name')}}:</label>
            <input type="text" name="user_name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2">{{t('Email')}}:</label>
            <input type="text" name="user_email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Grade')}} :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
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
                @foreach($teachers as $row)
                    <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @foreach(schoolSections() as $section)
                    <option value="{{$section}}">{{$section}}</option>
                @endforeach
            </select>
        </div>


        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Activation')}}:</label>
            <select class="form-select" name="user_status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                <option value="active">{{t('Active')}}</option>
                <option value="expire">{{'Expired'}}</option>
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2">{{t('Status')}}:</label>
            <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Status')}}">
                <option></option>
                @foreach(\App\Models\UserLesson::STATUS_MODEL as $key => $status)
                <option value="{{$key}}">{{t($status)}}</option>
                @endforeach
            </select>
        </div>


        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Lesson')}} :</label>
            <select name="lesson_id" id="lesson_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Lesson')}}" data-allow-clear="true">
                <option></option>

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
                <th class="text-start">{{ t('School') }}</th>
                <th class="text-start">{{ t('Lesson') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>


@endsection
@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>

    <script>
        var TABLE_URL = "{{ route('supervisor.students_works.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'lesson', name: 'lesson'},
            {data: 'actions', name: 'actions'},
        ];
    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>

    <script>
        getSectionByTeacher()
        getLessonsByGrade()
    </script>
@endsection
