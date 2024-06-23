@extends('teacher.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form class="kt-form kt-form--fit kt-margin-b-20" action="{{route('teacher.report.usage_report')}}" id="filter">
        {{csrf_field()}}
        <div class="row gap-3">
            <div class="col-12">
                <label class="form-label">{{ t('Grade') }}:</label>
                <select class="form-select grade" data-placeholder="{{t('Select grade')}}" data-control="select2" data-allow-clear="true" name="grades[]" multiple id="grades">
                    <option value="all" selected>{{t('All')}}</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" selected>{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="mb-2">{{ t('Sections') }}:</label>
                <select class="form-select sections" data-placeholder="{{t('Select sections')}}" data-control="select2" data-allow-clear="true" name="sections[]" multiple id="sections">
                    <option value="all" selected>{{t('All')}}</option>
                    @foreach(teacherSections(Auth::guard('teacher')->user()->id) as $section)
                        <option selected value="{{$section}}">{{$section}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="mb-2">{{t('Date Range')}} :</label>
                <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="{{t('Pick date range')}}" id="date_range"/>
                <input type="hidden" name="start_date" id="start_date_range" />
                <input type="hidden" name="end_date" id="end_date_range" />
            </div>
            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Get Report') }}</button>&nbsp;
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script>
        initializeDateRangePicker('date_range')
        onSelectAllClick('grades')
        onSelectAllClick('sections')
    </script>
@endsection
