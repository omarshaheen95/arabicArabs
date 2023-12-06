@extends('teacher.user_curriculum.layout.container_v2')

@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> {{$story->name}} </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('teacher.stories.list', $story->grade)}}"> المستويات </a>
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
                            @if(count($users_story))
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
                        <a href="{{route('teacher.stories.show', [$story->id,'watch', $grade])}}" class=" btn btn-theme px-5">
                            <i class="fa-solid fa-arrow-right"></i>
                            القصة
                        </a>
                        {{--                            @if(!is_null($level))--}}
                        {{--                                <div class="exercise-box-header text-center text-danger" style="font-size: 18px;font-weight: 700;">--}}
                        {{--                                    <span class="title">{{$level->level_note}}  </span>--}}
                        {{--                                </div>--}}
                        {{--                            @endif--}}
                        <a href="{{route('teacher.stories.show', [$story->id,'test', $grade])}}" class=" btn btn-theme px-5">
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
                    @if(count($users_story))
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
                            @foreach($users_story as $user_story)
                            <div class="exercise-question-answer text-center mb-4">
                                    <h4 class="text-success">{{$user_story->user->name}} </h4>
                                    <h4 class="text-success"> @if($user_story->status == 'corrected') <span
                                            class="text-warning">  {{$user_story->mark}} / 10</span> @endif</h4>
                                    <audio src="{{asset($user_story->record)}}" controls></audio>
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
