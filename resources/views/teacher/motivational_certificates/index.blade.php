@extends('teacher.layout.container')

@section('title',$title)

@section('actions')
        <a href="{{route('teacher.motivational_certificate.create')}}?cer_type=lesson" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Lesson Certificate')}}
        </a>
        <a href="{{route('teacher.motivational_certificate.create')}}?cer_type=story" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Story Certificate')}}
        </a>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('{{route('teacher.motivational_certificate.export')}}')">{{t('Export')}}</a>
                </li>

                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!"
                       id="delete_rows">{{t('Delete')}}</a></li>


        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-1  mb-2">
            <label>{{t('ID')}}:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="{{t('ID')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label>{{t('Student Name')}}:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="{{t('Student Name')}}">
        </div>
        <div class="col-lg-3  mb-2">
            <label>{{t('Email')}}:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="{{t('Email')}}">
        </div>
        <div class="col-lg-2 mb-2">
            <label>{{t('Learning Years')}} :</label>
            <select name="year_learning" class="form-select" data-control="select2"
                    data-placeholder="{{t('Select Year')}}" data-allow-clear="true">
                <option></option>
                @foreach(range(0,12) as $value)
                    <option value="{{ $value }}">{{$value == 0 ? '-':$value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Student Grade')}} :</label>
            <select name="student_grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                @endforeach
            </select>
        </div>


        <div class="col-3 mb-2">
            <label class="">{{t('Section')}}:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Section')}}">
                <option></option>
                @foreach(teacherSections() as $section)
                    <option value="{{$section}}">{{$section}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="">{{t('Certificate Type')}}:</label>
            <select class="form-select" name="model_type" data-control="select2" data-allow-clear="true"
                    data-placeholder="{{t('Select Type')}}">
                <option></option>
                <option value="{{\App\Models\Lesson::class}}">{{t('Lesson')}}</option>
                <option value="{{\App\Models\Story::class}}">{{'Story'}}</option>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Granted in')}} :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="{{t('Pick date range')}}" id="date_range"/>
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Created in')}} :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="created_at" value="" placeholder="{{t('Pick date range')}}" id="created"/>
            <input type="hidden" name="start_created" id="start_created" />
            <input type="hidden" name="end_created" id="end_created" />
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
                <th class="text-start">{{ t('Granted To') }}</th>
                <th class="text-start">{{ t('Dates') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>


@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>

    <script>

        var DELETE_URL = "{{ route('teacher.motivational_certificate.destroy') }}";
        var TABLE_URL = "{{ route('teacher.motivational_certificate.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'model', name: 'model'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];
        initializeDateRangePicker();
        initializeDateRangePicker('created');

    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


