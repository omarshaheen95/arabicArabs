{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://www.arabic-keyboard.org/keyboard/keyboard.css">
    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }

        .progress {
            position: relative;
            width: 100%;
            height: 30px;
            border: 1px solid #7F98B2;
            border-radius: 3px;
            border-radius: 36px !important;
            display: none;
        }

        .bar {
            background-color: #17C41A;
            width: 0%;
            height: 30px;
            border-radius: 3px;
        }

        .percent {
            position: absolute;
            display: inline-block;
            top: 4px;
            left: 48%;
            color: #000;
        }

        .text-success {
            color: #0F0 !important;
        }

        .box-style {
            color: #FF0000;
            font-size: 15px;
            font-weight: bold;
            background-color: #EFEFEF;
            padding: 10px;
            border-left: 5px solid #F00;;
            border-right: 5px solid #F00;
            border-top: 5px solid #000;
            border-bottom: 5px solid #000;
        }

        #recordingslist {
            list-style: none;
        }

        #keyboardInputLayout {
            direction: ltr !important;
        }

        #keyboardInputMaster tbody tr td div#keyboardInputLayout table tbody tr td {
            font: normal 30px 'Lucida Console', monospace;
        }

        .keyboardInputInitiator {
            width: 50px
        }

        .exercise-question .exercise-question-data .info {
            font-size: 18px
        }
    </style>

@endsection
@section('content')

    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : {{$lesson->grade_name}} </h3>
{{--                        <h1 class="title"><p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p></h1>--}}

                        <nav class="breadcrumb">
                            <a class="breadcrumb-item"
                               href="{{route('lessons', [$lesson->grade_id, $lesson->lesson_type])}}">
                                {{$lesson->type_name}} </a>
                            <span class="breadcrumb-item active" aria-current="page">الدروس </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form action="{{route('lesson_writing_test', $lesson->id)}}" enctype="multipart/form-data" id="term_form" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">
                            <div class="justify-content-between align-items-center mb-4">


                            </div>

                            <div class="question-list">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach($questions as $question)

                                    <div id="{{$counter}}"
                                         class="exercise-box @if($loop->first) active @endif question-item">
{{--                                        <div class="exercise-box-header text-center">--}}
{{--                                            <span class="number"> {{$counter}} : </span>--}}
{{--                                            <span class="title">   أكد إذا كانت هذه الجمل صواب أم خطأ - Confirm whether these sentences--}}
{{--                                                are true or false  </span>--}}
{{--                                        </div>--}}
                                        <div class="exercise-box-body">
                                            <div class="exercise-question">
                                                <div class="exercise-question-data border-0">
                                                    <div class="info">
                                                        {{$question->content}}
                                                    </div>
                                                </div>
                                                <div class="exercise-question-answer text-center my-4">

                                                    @if($question->getFirstMediaUrl('imageQuestion'))

                                                        <div class="row justify-content-center py-3">
                                                            <div class="col-lg-6 col-md-8">
                                                                @if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                                    <div class="recorder-player" id="voice_audio_2">
                                                                        <div class="audio-player">
                                                                            <audio crossorigin>
                                                                                <source
                                                                                    src="{{asset($question->getFirstMediaUrl('imageQuestion'))}}"
                                                                                    type="audio/mpeg">
                                                                            </audio>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="w-100 text-center">
                                                                        <img src="{{asset($question->getFirstMediaUrl('imageQuestion'))}}"
                                                                             width="300px">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="exercise-question-answer">
                                                        <div class="textarea">
                                                    <textarea class="form-control keyboard-input keyboardInput" name="writing_answer[{{$question->id}}]" id="" rows="4" placeholder="اكتب إجابتك هنا....."></textarea>
                                                            <div class="text-end">
                                                                <span class="word-count "> الكلمات: <span>0</span></span>
                                                            </div>
                                                        </div>
                                                        <div class="upload-files text-right" style="text-align: right;">
                                                            <label for="file-1" class="btn btn-upload-file">
                                                                <svg id="vuesax_bulk_document-upload"
                                                                     data-name="vuesax/bulk/document-upload"
                                                                     xmlns="http://www.w3.org/2000/svg" width="25.721"
                                                                     height="25.721"
                                                                     viewBox="0 0 25.721 25.721">
                                                                    <g id="document-upload">
                                                                        <path id="Vector"
                                                                              d="M19.291,8.777h-3.1a4.615,4.615,0,0,1-4.608-4.608v-3.1A1.075,1.075,0,0,0,10.514,0H5.969A5.656,5.656,0,0,0,0,5.969v9.5a5.656,5.656,0,0,0,5.969,5.969h8.424a5.656,5.656,0,0,0,5.969-5.969V9.849A1.075,1.075,0,0,0,19.291,8.777Z"
                                                                              transform="translate(2.679 2.143)"
                                                                              fill="#20b2aa"
                                                                              opacity="0.4"></path>
                                                                        <path id="Vector-2" data-name="Vector"
                                                                              d="M1.2.209A.7.7,0,0,0,0,.68v3.74A2.922,2.922,0,0,0,2.947,7.282c1.018.011,2.433.011,3.644.011a.676.676,0,0,0,.5-1.147C5.551,4.592,2.786,1.795,1.2.209Z"
                                                                              transform="translate(15.733 2.16)"
                                                                              fill="#20b2aa"></path>
                                                                        <path id="Vector-3" data-name="Vector"
                                                                              d="M5.656,2.379,3.513.236C3.5.225,3.491.225,3.491.214A.917.917,0,0,0,3.255.054H3.234A1.1,1.1,0,0,0,2.977,0H2.891a.692.692,0,0,0-.2.043.523.523,0,0,0-.075.032.71.71,0,0,0-.236.161L.233,2.379A.8.8,0,0,0,1.369,3.515l.772-.772v4.49a.8.8,0,0,0,1.608,0V2.744l.772.772a.8.8,0,0,0,1.136,0A.809.809,0,0,0,5.656,2.379Z"
                                                                              transform="translate(6.701 10.985)"
                                                                              fill="#20b2aa"></path>
                                                                        <path id="Vector-4" data-name="Vector"
                                                                              d="M0,0H25.721V25.721H0Z"
                                                                              fill="none" opacity="0"></path>
                                                                    </g>
                                                                </svg>
                                                                <span class="ms-3"> ارفاق الملف</span>

                                                            </label>
                                                            <input type="file" name="writing_attachment[{{$question->id}}]"
                                                                   class="form-control d-none"
                                                                   id="file-1">
                                                            <div class="files-upload"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $counter ++;
                                    @endphp
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center question-control btn-wizard">
                                @if(count($questions) > 1)
                                <div class="text-center">
                                    <button type="button" class="d-none btn btn-light border"
                                            id="previousQuestion"><span class="txt"
                                                                        style="font-size: 18px">  السابق </span>
                                    </button>
                                </div>
                            @endif
                                <div class="text-center">
                                    <button type="button" class="btn btn-theme @if(count($questions) == 1) @else d-none @endif endExam" id="confirmed_modal"
                                            data-toggle="modal" data-target="#endExam"
                                            style="font-weight: bold;background-color: #0043b3;">
                                        <span class="txt" style="font-size: 18px">حفظ و إنهاء</span>
                                    </button>
                                </div>
                                @if(count($questions) > 1)
                                <div class="text-center">
                                    <button type="button" class="btn btn-theme"
                                            id="nextQuestion">
                                        <span class="txt" style="font-size: 18px"> التالي  </span></button>
                                </div>
                                @endif
                            </div>


                            @if(count($questions) > 1)
                            <div class="table-footer font-weight-bold">
                                <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                </ul>
                            </div>
                            @endif
                            <!-- Modal -->

                            <div class="modal fade" id="endExam" tabindex="-1" role="dialog"
                                 aria-labelledby="endExamLabel" aria-hidden="true">

                                <div class="modal-dialog">
                                    <div class="modal-content" style="padding: 15px;">
                                        <div class="modal-header border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center py-5"><h2 class="mb-0"
                                                                                     style="direction: ltr">
                                                هل أنت متأكد من حفظ الاختبار </h2>
                                        </div>
                                        <div class="modal-footer border-0 justify-content-center">
                                            <button type="submit" class="btn btn-soft-danger me-3"
                                                    id="save_assessment"><span
                                                    class="txt">  نعم احفظ الاختبار</span></button>
                                            <button type="button" class="btn btn-light border"
                                                    data-bs-dismiss="modal"><span
                                                    class="txt"> أريد البقاء في الاختبار </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('s_website/js/jquery.ui.touch-punch.js')}}"></script>
    <script type="text/javascript" src="https://www.arabic-keyboard.org/keyboard/keyboard.js" charset="UTF-8"></script>


    <script>
        $(document).ready(function () {
            $('#confirmed_modal').click(function (e) {
                $("#endExam").modal("show");
            });
            $('#save_assessment').click(function (e) {
                e.preventDefault();
                $(this).attr('disabled', true);
                $('#confirmed_modal').attr('disabled', true);
                $('#term_form').submit();
            })
            $('.audio').click(function () {
                var elem = $(this);
                var data_id = $(this).attr('data-id');
                $('audio').each(function () {
                    this.pause(); // Stop playing
                    this.currentTime = 0; // Reset time
                    console.log('pause');
                });
                console.log('#audio' + data_id);
                $('#audio' + data_id)[0].currentTime = 0;
                $('#audio' + data_id)[0].play();
                console.log('play');

            });
        });


    </script>
@endsection

