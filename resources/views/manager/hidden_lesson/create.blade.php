@extends('manager.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('manager.hidden_lesson.store') }}" method="post">
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
                <select class="form-select " data-control="select2"
                        data-placeholder="{{t('Select grade')}}"
                        data-allow-clear="true" name="grade_id" >
                    <option></option>
                    @foreach($grades as  $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label">{{ t('Lesson') }}</label>
                <select class="form-select assignment_lesson" data-control="select2" multiple
                        data-placeholder="{{t('Select Lesson')}}"
                        data-allow-clear="true"
                        name="lesson_id[]" >
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\HiddenLessonRequest::class, '#form_information'); !!}

    <script>
        getLessonsByGrade("{{route('manager.getLessonsByGrade')}}")

    </script>

@endsection
