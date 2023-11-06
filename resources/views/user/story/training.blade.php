@extends('user.layout.container_v2')

@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> {{$story->name}} </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('stories.list', $story->grade)}}"> المستويات </a>
                            <span class="breadcrumb-item active" aria-current="page">القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row" dir="rtl">
                <div class="col-lg-3">
                    <div class="exercise-card">
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="#exercise-1" class="nav-link active">
                                    <div class="exercise-title"> التمرين الاول</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#exercise-2" class="nav-link">
                                    <div class="exercise-title"> سجل بصوتك</div>
                                </a>
                            </li>
                            @if(count($users_stories))
                            <li class="nav-item">
                                <a href="#exercise-3" class="nav-link">
                                    <div class="exercise-title">تسجيلات معتمدة</div>
                                </a>
                            </li>
                            @endif

                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{route('stories.show', [$story->id,'watch'])}}" class=" btn btn-theme px-5">
                            <i class="fa-solid fa-arrow-right"></i>
                            القصة
                        </a>
                        {{--                            @if(!is_null($level))--}}
                        {{--                                <div class="exercise-box-header text-center text-danger" style="font-size: 18px;font-weight: 700;">--}}
                        {{--                                    <span class="title">{{$level->level_note}}  </span>--}}
                        {{--                                </div>--}}
                        {{--                            @endif--}}
                        <a href="{{route('stories.show', [$story->id,'test'])}}" class=" btn btn-theme px-5">
                            الاختبار
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="exercise-box" id="exercise-1">
                        <div class="exercise-box-header">
                                    <span class="icon">
                                        <svg id="icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                                             viewBox="0 0 33 33">
                                            <g id="message-question">
                                                <path id="Vector" d="M0,0H33V33H0Z" fill="none" opacity="0"/>
                                                <path id="Vector-2" data-name="Vector"
                                                      d="M20.625,22h-5.5L9.006,26.07a1.371,1.371,0,0,1-2.131-1.141V22A6.5,6.5,0,0,1,0,15.125V6.875A6.5,6.5,0,0,1,6.875,0h13.75A6.5,6.5,0,0,1,27.5,6.875v8.25A6.5,6.5,0,0,1,20.625,22Z"
                                                      transform="translate(2.75 3.341)" fill="#223f99" opacity="0.4"/>
                                                <g id="Group" transform="translate(13.186 8.401)">
                                                    <path id="Vector-3" data-name="Vector"
                                                          d="M3.314,8.25A1.039,1.039,0,0,1,2.282,7.219V6.93A3.2,3.2,0,0,1,3.891,4.249c.509-.344.674-.577.674-.935a1.251,1.251,0,1,0-2.5,0A1.039,1.039,0,0,1,1.031,4.345,1.039,1.039,0,0,1,0,3.314a3.314,3.314,0,1,1,6.627,0,3.154,3.154,0,0,1-1.581,2.64c-.536.358-.7.591-.7.976v.289A1.03,1.03,0,0,1,3.314,8.25Z"
                                                          fill="#223f99"/>
                                                </g>
                                                <g id="Group-2" data-name="Group" transform="translate(15.469 18.012)">
                                                    <path id="Vector-4" data-name="Vector"
                                                          d="M1.031,2.063A1.031,1.031,0,1,1,2.063,1.031,1.03,1.03,0,0,1,1.031,2.063Z"
                                                          fill="#223f99"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                            <span class="title">  اقرأ بصوتٍ عالٍ</span>
                        </div>
                        <div class="exercise-box-body">
                            <div class="exercise-question">
                                {!! $story->content !!}
                            </div>
                        </div>
                    </div>
                    <div class="exercise-box" id="exercise-2">
                        <div class="exercise-box-header">
                                    <span class="icon">
                                        <svg id="icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                                             viewBox="0 0 33 33">
                                            <g id="message-question">
                                                <path id="Vector" d="M0,0H33V33H0Z" fill="none" opacity="0"/>
                                                <path id="Vector-2" data-name="Vector"
                                                      d="M20.625,22h-5.5L9.006,26.07a1.371,1.371,0,0,1-2.131-1.141V22A6.5,6.5,0,0,1,0,15.125V6.875A6.5,6.5,0,0,1,6.875,0h13.75A6.5,6.5,0,0,1,27.5,6.875v8.25A6.5,6.5,0,0,1,20.625,22Z"
                                                      transform="translate(2.75 3.341)" fill="#223f99" opacity="0.4"/>
                                                <g id="Group" transform="translate(13.186 8.401)">
                                                    <path id="Vector-3" data-name="Vector"
                                                          d="M3.314,8.25A1.039,1.039,0,0,1,2.282,7.219V6.93A3.2,3.2,0,0,1,3.891,4.249c.509-.344.674-.577.674-.935a1.251,1.251,0,1,0-2.5,0A1.039,1.039,0,0,1,1.031,4.345,1.039,1.039,0,0,1,0,3.314a3.314,3.314,0,1,1,6.627,0,3.154,3.154,0,0,1-1.581,2.64c-.536.358-.7.591-.7.976v.289A1.03,1.03,0,0,1,3.314,8.25Z"
                                                          fill="#223f99"/>
                                                </g>
                                                <g id="Group-2" data-name="Group" transform="translate(15.469 18.012)">
                                                    <path id="Vector-4" data-name="Vector"
                                                          d="M1.031,2.063A1.031,1.031,0,1,1,2.063,1.031,1.03,1.03,0,0,1,1.031,2.063Z"
                                                          fill="#223f99"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                            <span class="title">  سجل بصوتٍ عالٍ</span>

                        </div>
                        <div class="exercise-box-body">
                            <div class="exercise-question-answer text-center">
                                @if($user_story && !is_null($user_story->record))

                                    @if($user_story->status == 'pending')
                                        <h4 class="text-warning">{{$user_story->status_name}} </h4>
                                    @endif
                                    @if($user_story->status == 'corrected')
                                        <h4 class="text-success">{{$user_story->status_name}} </h4>
                                    @endif
                                    @if($user_story->status == 'returned')
                                        <h4 class="text-danger">{{$user_story->status_name}} </h4>
                                    @endif
                                    <h4 class="text-success"> @if($user_story->status == 'corrected') <span
                                            class="text-warning">  {{$user_story->mark}} / 10</span> @endif</h4>
                                    <audio src="{{asset($user_story->record)}}" controls></audio>
                                @endif

                                @if(!$user_story || ($user_story && $user_story->status == 'pending') || ($user_story && $user_story->status == 'returned'))
                                    <div class="text-center">
                                        <div class="recorder-box" id="recorder-1">
                                            <div class="controls">
                                                <!-- TicketId -->
                                                <input type="hidden" id="recorder_url_1" class="recorder_url"
                                                       name="TicketId" value="500">
                                                <!-- Start Voice -->
                                                <div class="icon start-voice startRecording">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                         fill="currentColor" class="bi bi-mic" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                              d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
                                                        <path fill-rule="evenodd"
                                                              d="M10 8V3a2 2 0 1 0-4 0v5a2 2 0 1 0 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3z"/>
                                                    </svg>
                                                    <span class="ms-2">   سجل إجابتك </span>
                                                </div>

                                                <!-- Stop Voice -->
                                                <div class="icon stop-voice stopRecording d-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-stop-fill"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                                                    </svg>
                                                    <span class="ms-2">
                                                                إيقاف
                                                            </span>
                                                </div>

                                                <!-- Remove Voice -->
                                                <div class="icon remove-voice deleteRecording d-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                         fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd"
                                                              d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                    </svg>
                                                    <span class="ms-2">
                                                                حذف
                                                            </span>
                                                </div>

                                                <!-- Timer -->
                                                <span class="timer d-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" fill="currentColor" class="bi bi-hourglass"
                                                                 viewBox="0 0 16 16">
                                                                <path
                                                                    d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z"/>
                                                            </svg>
                                                            <span id="timer">00:00</span>
                                                        </span>
                                                <div class="icon btn  btn-soft-success saveRecording d-none"
                                                     style="background-color: rgb(46, 204, 112) !important; color: #FFF !important;">
                                                    <span class="icon-spinner d-none ">
                                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                                        <span> جاري الحفظ </span>
                                                    </span>
                                                    <span class=" text ms-2"> حفظ   </span>
                                                </div>

                                            </div>
                                            <!-- Voice Audio-->
                                            <div class="recorder-player d-none" id="voice_audio_1">
                                                <!-- crossorigin -->
                                                <div class="audio-player">
                                                    <audio >
                                                        <source
                                                            src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/355309/Swing_Jazz_Drum.mp3"
                                                            type="audio/mpeg">
                                                    </audio>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            </div>
                            <div class="exercise-question">

                            </div>
                        </div>
                    </div>
                    @if(count($users_stories))
                    <div class="exercise-box" id="exercise-3">
                        <div class="exercise-box-header">
                                    <span class="icon">
                                        <svg id="icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                                             viewBox="0 0 33 33">
                                            <g id="message-question">
                                                <path id="Vector" d="M0,0H33V33H0Z" fill="none" opacity="0"/>
                                                <path id="Vector-2" data-name="Vector"
                                                      d="M20.625,22h-5.5L9.006,26.07a1.371,1.371,0,0,1-2.131-1.141V22A6.5,6.5,0,0,1,0,15.125V6.875A6.5,6.5,0,0,1,6.875,0h13.75A6.5,6.5,0,0,1,27.5,6.875v8.25A6.5,6.5,0,0,1,20.625,22Z"
                                                      transform="translate(2.75 3.341)" fill="#223f99" opacity="0.4"/>
                                                <g id="Group" transform="translate(13.186 8.401)">
                                                    <path id="Vector-3" data-name="Vector"
                                                          d="M3.314,8.25A1.039,1.039,0,0,1,2.282,7.219V6.93A3.2,3.2,0,0,1,3.891,4.249c.509-.344.674-.577.674-.935a1.251,1.251,0,1,0-2.5,0A1.039,1.039,0,0,1,1.031,4.345,1.039,1.039,0,0,1,0,3.314a3.314,3.314,0,1,1,6.627,0,3.154,3.154,0,0,1-1.581,2.64c-.536.358-.7.591-.7.976v.289A1.03,1.03,0,0,1,3.314,8.25Z"
                                                          fill="#223f99"/>
                                                </g>
                                                <g id="Group-2" data-name="Group" transform="translate(15.469 18.012)">
                                                    <path id="Vector-4" data-name="Vector"
                                                          d="M1.031,2.063A1.031,1.031,0,1,1,2.063,1.031,1.03,1.03,0,0,1,1.031,2.063Z"
                                                          fill="#223f99"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                            <span class="title"> تسجيلات معتمدة
                                </span>
                        </div>
                        <div class="exercise-box-body">
                            @foreach($users_stories as $users_story)
                            <div class="exercise-question-answer text-center mb-4">
                                    <h4 class="text-success">{{$users_story->user->name}} </h4>
                                    <h4 class="text-success"> @if($users_story->status == 'corrected') <span
                                            class="text-warning">  {{$users_story->mark}} / 10</span> @endif</h4>
                                    <audio src="{{asset($users_story->record)}}" controls></audio>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection
@section('script')
    {{--    <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>--}}

    <script>
        $('.saveRecording').click(function (e) {
            console.log('test');
            e.preventDefault();

            $(".startRecording").toggleClass("d-none");
            $(".deleteRecording").toggleClass("d-none");

            $(this).find(".text").toggleClass("d-none");
            $(this).find(".icon-spinner").toggleClass("d-none");
            $(this).css('pointer-events', "none");
            let csrf = $('meta[name="csrf-token"]').attr('content');
            var URL = '{{route('stories.record', $story->id)}}';
            var METHOD = 'post';
            var fd = new FormData();

            // $("#modal-confirm").modal("hide");

            fd.append('record', blob_recorder_files);
            fd.append('_token', csrf);


            $.ajax({
                type: METHOD,
                url: URL,
                data: fd,
                processData: false,
                contentType: false,
                xhr: function () {
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function (event) {
                            var percent_value = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent_value = Math.ceil(position / total * 100);
                            }
                            //update progressbar
                            // var percentVal = percent_value + '%';
                            // bar.width(percentVal)
                            // percent.html(percentVal);
                        }, true);
                    }
                    var percentVal = 'Saving';
                    // bar.width(percentVal)
                    // percent.html(percentVal);
                    return xhr;
                }

            }).done(function (data) {
                // console.log(data);
                toastr.success(data.message);

                setTimeout(function () {
                    window.location.reload(true);
                }, 3000)
            }).fail(function () {
                $(this).find(".text").toggleClass("d-none");
                $(this).find(".icon-spinner").toggleClass("d-none");
                $(this).css('pointer-events', "pointer");
            });
        })

        $(document).on("click", ".exercise-card .nav-link", function () {
            $(".exercise-card .nav-link").removeClass("active");
            $(this).addClass("active");
        });
    </script>
@endsection
