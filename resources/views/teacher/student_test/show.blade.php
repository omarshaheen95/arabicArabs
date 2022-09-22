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
            <a href="{{ route('teacher.students_tests.index') }}">اختبارات الطلاب</a>
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
                      action="{{ route('teacher.students_tests.correct', $user_test->id) }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @if($user_test->lesson->lesson_type == "writing")
                                    @foreach($user_test->writingResults as $writingResult)
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label"></label>
                                            <div class="col-lg-9 col-xl-6">
                                                <label class="col-form-label">
                                                    {{ $writingResult->question->content }}

                                                    @if($writingResult->question->getFirstMediaUrl('imageQuestion'))
                                                        :
                                                        <div class="row justify-content-center py-3">
                                                            <div class="col-lg-6 col-md-8">
                                                                @if(\Illuminate\Support\Str::contains($writingResult->question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                                    <div class="recorder-player" id="voice_audio_2">
                                                                        <div class="audio-player">
                                                                            <audio crossorigin>
                                                                                <source
                                                                                    src="{{asset($writingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                                                    type="audio/mpeg">
                                                                            </audio>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="w-100 text-center">
                                                                        <img src="{{asset($writingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                                             width="300px">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </label>
                                                <br>
                                                <textarea disabled class="form-control">{{$writingResult->result}}</textarea>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif
                                @if($user_test->lesson->lesson_type == "speaking")
                                    @foreach($user_test->speakingResults as $speakingResult)
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label"></label>
                                            <div class="col-lg-9">
                                                <label class="col-form-label">
                                                    {{ $speakingResult->question->content }}
                                                    @if($speakingResult->question->getFirstMediaUrl('imageQuestion'))
                                                        :
                                                        <div class="row justify-content-center py-3">
                                                            <div class="col-lg-6 col-md-8">
                                                                @if(\Illuminate\Support\Str::contains($speakingResult->question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                                    <div class="recorder-player" id="voice_audio_2">
                                                                        <div class="audio-player">
                                                                            <audio crossorigin>
                                                                                <source
                                                                                    src="{{asset($speakingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                                                    type="audio/mpeg">
                                                                            </audio>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="w-100 text-center">
                                                                        <img src="{{asset($speakingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                                             width="300px">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </label>
                                                <br>
                                                <audio src="{{asset($speakingResult->attachment)}}" controls></audio>
                                            </div>

                                        </div>
                                        @endforeach
                                    @endif

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الدرجة النهائية من (100)</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="mark" type="number" max="100" min="0"
                                               value="{{ $user_test->mark }}">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="save" class="btn btn-danger">اعتماد التصحيح</button>&nbsp;
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


    <script>

    </script>

@endsection
