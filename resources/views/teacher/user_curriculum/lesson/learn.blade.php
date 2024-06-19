{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('teacher.user_curriculum.layout.container_v2')
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
        #keyboardInputLayout{
            direction: ltr !important;
        }
        #keyboardInputMaster tbody tr td div#keyboardInputLayout table tbody tr td{
            font: normal 30px 'Lucida Console',monospace;
        }
        .keyboardInputInitiator{
            width: 50px
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
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('teacher.lessons', [$lesson->grade_id, $lesson->lesson_type])}}">
                                {{$lesson->type_name}} </a>
                            <span class="breadcrumb-item active" aria-current="page">الدروس </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="exercise-box" id="exercise-1">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{route('teacher.lessons', [$lesson->grade_id, $lesson->lesson_type])}}" class=" btn btn-theme px-5">
                        <i class="fa-solid fa-arrow-right"></i>
                        الدروس
                    </a>
                    {{--                            @if(!is_null($level))--}}
                    {{--                                <div class="exercise-box-header text-center text-danger" style="font-size: 18px;font-weight: 700;">--}}
                    {{--                                    <span class="title">{{$level->level_note}}  </span>--}}
                    {{--                                </div>--}}
                    {{--                            @endif--}}
                    <a href="{{route('teacher.lesson', [$lesson->id,'training'])}}" class=" btn btn-theme px-5">
                        التدريب
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <div class="exercise-box-header text-center">
                    @if($lesson->lesson_type == 'reading')
                    <span class="title">اقرأ النَّص التالي للفَهم والاستيعابٍ</span>
                    @endif
                    @if($lesson->lesson_type == 'listening')
                    <span class="title">استمع للنص التاليٍ</span>
                    @endif
                </div>
                <div class="exercise-box-body">
                    <div class="table-responsove">
                        <table class="table">
                            @if($lesson->getFirstMediaUrl('audioLessons'))
                                <tr>
                                    <td>
                                        <div class="audio-player">
                                            <audio >
                                                <source
                                                    src="{{asset($lesson->getFirstMediaUrl('audioLessons'))}}"
                                                    type="audio/mpeg" />
                                            </audio>
                                        </div>
{{--                                        <a href="#!" class="play-and-listen" data-recorde-id="{{$lesson->id}}"--}}
{{--                                           data-recorde-url="{{asset($lesson->getFirstMediaUrl('audioLessons'))}}">--}}
{{--                                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60"--}}
{{--                                                 viewBox="0 0 39 39">--}}
{{--                                                <g id="play" transform="translate(7.618 6.252)">--}}
{{--                                                    <rect id="bg" width="39" height="39" rx="19.5"--}}
{{--                                                          transform="translate(-7.618 -6.252)"--}}
{{--                                                          fill="rgba(217,227,253)" opacity="0.1"/>--}}
{{--                                                    <path id="Vector"--}}
{{--                                                          d="M16.535,6.358.157,16.178A4.449,4.449,0,0,1,0,15.008V4.5A4.5,4.5,0,0,1,6.749.61l4.544,2.621,4.555,2.632A3.81,3.81,0,0,1,16.535,6.358Z"--}}
{{--                                                          transform="translate(4.499 3.742)" fill="#223f99"/>--}}
{{--                                                    <path id="Vector-2" data-name="Vector"--}}
{{--                                                          d="M15.039,6.085,10.483,8.717,5.939,11.338A4.485,4.485,0,0,1,0,10.022l.472-.281L16.715,0A4.5,4.5,0,0,1,15.039,6.085Z"--}}
{{--                                                          transform="translate(5.309 11.304)" fill="#223f99"--}}
{{--                                                          opacity="0.4"/>--}}
{{--                                                </g>--}}
{{--                                            </svg>--}}
{{--                                        </a>--}}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="exercise-question">
                        <div class="row justify-content-center my-5">
                            @foreach($lesson->getMedia('videoLessons') as $video)
                                <div class="col-8">
                                    <div id="vid_player_{{$video->id}}" class="mb-4"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="exercise-question">
                        {!! $lesson->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <audio class="play-and-listen-audio" src=""></audio>

@endsection
@section('script')
    <script src="{{asset('s_website/js/playerjs.js')}}"></script>

    <script>
        @foreach($lesson->getMedia('videoLessons') as $video)
        var player_{{$video->id}} = new Playerjs({
            id: "vid_player_{{$video->id}}",
            file: '{{asset($video->getUrl())}}',
        });
        @endforeach
    </script>
    <script>


        $(document).ready(function () {
            $(document).on("click", ".play-and-listen", function (e) {
                e.preventDefault();
                var audio_ID = $(this).data("recorde-id"),
                    audio_url = $(this).data("recorde-url");
                if ($(this).hasClass("active")) {
                    $(".play-and-listen-audio").attr("src", audio_url);
                    $(".play-and-listen-audio").trigger("pause");
                    $(".play-and-listen").removeClass("active");
                } else {
                    $(".play-and-listen-audio").attr("src", audio_url);
                    $(".play-and-listen-audio").trigger("play");

                    $(".play-and-listen").removeClass("active");
                    $(this).addClass("active");
                }
            });
        });


    </script>
@endsection
