@extends('manager.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('manager.hidden_story.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('School') }}</label>
                <select class="form-select" data-control="select2"
                        data-placeholder="{{t('Select School')}}"
                        data-allow-clear="true" name="school_id" id="school_id">
                    <option></option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{$school->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Grade') }}</label>
                <select class="form-select assignment_grade" data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="grade" id="assignment_grade">
                    <option></option>
                    @foreach(storyGradesSys() as $key => $grade)
                        <option value="{{ $key }}">{{ $grade}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Story') }}</label>
                <select class="form-select" data-control="select2" multiple
                        data-placeholder="{{t('Select Story')}}"
                        data-allow-clear="true"
                        name="story_id[]" id="assignment_lesson">
                    <option></option>
                </select>
            </div>

            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets_v1/js/custom.js')}}"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\HiddenStoryRequest::class, '#form_information'); !!}

    <script>

        onSelectAllClick('section')
        getStoriesByGrade("{{route('manager.getStoriesByGrade')}}");

    </script>

@endsection
