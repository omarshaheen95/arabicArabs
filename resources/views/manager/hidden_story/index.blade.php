@extends('manager.layout.container')

@section('title',$title)


@section('actions')
    @can('add hidden stories')
        <a href="{{route('manager.hidden_story.create')}}" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            {{t('Hide Story')}}
        </a>
    @endcan

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            @can('export hidden stories')
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('{{route('manager.hidden_story.export')}}')">{{t('Export')}}</a>
                </li>
            @endcan

            @can('delete hidden stories')
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows">{{t('Delete')}}</a></li>
            @endcan

        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
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
        <div class="col-lg-3 mb-2">
            <label>{{t('Grade')}} :</label>
            <select name="grade" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @foreach(storyGradesSys() as $key => $grade)
                    <option value="{{ $key }}">{{ $grade}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Story')}} :</label>
            <select  name="story_id" id="story_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Story')}}" data-allow-clear="true">
                <option></option>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Hidden at')}} :</label>
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
                <th class="text-start">{{ t('School') }}</th>
                <th class="text-start">{{ t('Grade') }}</th>
                <th class="text-start">{{ t('Story') }}</th>
                <th class="text-start">{{ t('Hidden At') }}</th>
                <th class="text-start">{{ t('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>


@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

    <script>
        var DELETE_URL = "{{ route('manager.hidden_story.destroy') }}";
        var TABLE_URL = "{{ route('manager.hidden_story.index') }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'school', name: 'school'},
            {data: 'grade', name: 'grade'},
            {data: 'story', name: 'story'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions'},
        ];
        initializeDateRangePicker();
        getStoriesByGrade("{{route('manager.getStoriesByGrade')}}");

    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


