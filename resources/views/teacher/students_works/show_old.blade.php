{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('teacher.layout.container')
@section('style')
    <style>
        .kt-radio > span {
            width: 25px;
            height: 25px;
        }

        label.kt-radio {
            font-size: 18px;
        }

        .kt-radio > span:after {
            margin-right: -5px;
            margin-top: -5px;
            width: 10px;
            height: 10px;
        }
    </style>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('teacher.students_works.index') }}">{{ t('Students works') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ $title }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"> {{ $title }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ route('teacher.students_works.update', $user_lesson->id) }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                        <label
                                            class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing answer') }}</label>
                                        <div class="col-lg-9 col-xl-6">
                                            {{--                                                <div class="upload-btn-wrapper">--}}
                                            {{--                                                    <button class="btn btn-danger">{{ t('upload attach') }}</button>--}}
                                            {{--                                                    <input name="attach_writing_answer" class="imgInp" id="imgInp" type="file" />--}}
                                            {{--                                                </div>--}}
                                            @if(!is_null($user_lesson->attach_writing_answer))
                                                <img src="{{$user_lesson->attach_writing_answer}}"/>
                                            @else
                                                {{t('No Answer')}}
                                            @endif
                                        </div>
                                    </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing answer') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea class="form-control"
                                                  name="writing_answer">{{$user_lesson->writing_answer}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing mark') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="writing_mark" type="number"
                                               value="{{ $user_lesson->writing_mark }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Reading answer') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        @if(!is_null($user_lesson->getOriginal('reading_answer')))
                                            <audio class="w-100" controls
                                                   src="{{$user_lesson->reading_answer}}"></audio>

                                        @else
                                            {{t('No Answer')}}
                                        @endif
                                        {{--                                        <input class="form-control" name="reading_answer" type="file">--}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Reading mark') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="reading_mark" type="number"
                                               value="{{ $user_lesson->reading_mark }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Feedback') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea class="form-control"
                                                  name="teacher_message">{{$user_lesson->teacher_message}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Status') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="kt-radio-list">
                                            <label class="kt-radio kt-radio--bold kt-radio--disabled ">
                                                <input type="radio" disabled="disabled" value="" name="status"
                                                       @if($user_lesson->status == 'pending') checked @endif> {{t(ucfirst('Waiting list'))}}
                                                <span></span>
                                            </label>
                                            <label class="kt-radio kt-radio--bold kt-radio--success">
                                                <input type="radio" name="status" value="corrected"
                                                       @if($user_lesson->status == 'corrected') checked @endif> {{t(ucfirst('Marking Completed'))}}
                                                <span></span>
                                            </label>
                                            <label class="kt-radio kt-radio--bold kt-radio--brand">
                                                <input type="radio" name="status" value="returned"
                                                       @if($user_lesson->status == 'returned') checked @endif> {{t(ucfirst('Send back'))}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\UserLessonRequest::class, '#form_information'); !!}

@endsection
