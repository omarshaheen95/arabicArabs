{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('supervisor.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('supervisor.students_works.index') }}">{{ t('Students works') }}</a>
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
                       method="post">
                    {{ csrf_field() }}
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Attach Writing Answer') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            @if(isset($user_lesson) && !is_null($user_lesson->attach_writing_answer))
                                <label class="mb-2">
                                    <a href="{{$user_lesson->attach_writing_answer}}" class="kt-font-warning"
                                       target="_blank">{{t('Browse')}}</a>
                                </label>
                            @endif
                            <input type="file" name="attach_writing_answer"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing answer') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <textarea class="form-control"
                                      name="writing_answer">{{$user_lesson->writing_answer}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing mark') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" name="writing_mark" type="number"
                                   value="{{ $user_lesson->writing_mark }}">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Reading answer') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            @if(!is_null($user_lesson->getOriginal('reading_answer')))
                                <audio class="w-100" controls src="{{$user_lesson->reading_answer}}"></audio>
                            @endif
                            <input class="form-control" name="reading_answer" type="file">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Reading mark') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" name="reading_mark" type="number"
                                   value="{{ $user_lesson->reading_mark }}">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Feedback') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <textarea class="form-control"
                                      name="teacher_message">{{$user_lesson->teacher_message}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Status') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="d-flex gap-2 mt-3">
                                <div
                                    class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input"
                                           @if($user_lesson->status == 'pending') checked @endif type="radio" required
                                           value="pending"
                                           name="status" id="flexRadioLg"/>
                                    <label class="form-check-label" for="flexRadioLg">
                                        {{t(ucfirst('Waiting list'))}}
                                    </label>
                                </div>
                                <div
                                    class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input"
                                           @if($user_lesson->status == 'corrected') checked @endif type="radio" required
                                           value="corrected"
                                           name="status" id="flexRadioLg"/>
                                    <label class="form-check-label" for="flexRadioLg">
                                        {{t(ucfirst('Marking Completed'))}}
                                    </label>
                                </div>
                                <div
                                    class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input"
                                           @if($user_lesson->status == 'returned') checked @endif type="radio" required
                                           value="returned"
                                           name="status" id="flexRadioLg"/>
                                    <label class="form-check-label" for="flexRadioLg">
                                        {{t(ucfirst('Send back'))}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


