@extends('user.layout.container_v2')
@section('style')
    <style>
        .green-audio-player{
            width: 100%;
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
                        <form action="{{route('lesson_speaking_test', $lesson->id)}}" enctype="multipart/form-data" id="term_form" method="post">
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
                                                @if(!is_null($lesson->content))
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            {!! $lesson->content !!}
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="exercise-question-answer text-center my-4">

                                                    @if($question->getFirstMediaUrl('imageQuestion'))

                                                        <div class="row justify-content-center py-3">
                                                            <div class="col-lg-6 col-md-8">
                                                                @if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                                    <div class="recorder-player" id="voice_audio_2">
                                                                        <div class="audio-player">
                                                                            <audio >
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
                                                            <div class="text-center">
{{--                                                                <div class="recorder-box" id="recorder-{{$question->id}}">--}}
{{--                                                                    <div class="controls">--}}
{{--                                                                        <!-- TicketId -->--}}
{{--                                                                        <input type="hidden" id="recorder_url_{{$question->id}}" class="recorder_url" name="TicketId" value="500">--}}
{{--                                                                        <!-- Start Voice -->--}}
{{--                                                                        <div class="icon start-voice startRecording">--}}
{{--                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-mic" viewBox="0 0 16 16">--}}
{{--                                                                                <path fill-rule="evenodd" d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>--}}
{{--                                                                                <path fill-rule="evenodd" d="M10 8V3a2 2 0 1 0-4 0v5a2 2 0 1 0 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3z"/>--}}
{{--                                                                            </svg>--}}
{{--                                                                            <span class="ms-2">--}}
{{--                                                                ابداً التسجيل الان--}}
{{--                                                            </span>--}}
{{--                                                                        </div>--}}

{{--                                                                        <!-- Stop Voice -->--}}
{{--                                                                        <div class="icon stop-voice stopRecording d-none">--}}
{{--                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-fill" viewBox="0 0 16 16">--}}
{{--                                                                                <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>--}}
{{--                                                                            </svg>--}}
{{--                                                                            <span class="ms-2">--}}
{{--                                                                إنهاء التسجيل--}}
{{--                                                            </span>--}}
{{--                                                                        </div>--}}

{{--                                                                        <!-- Remove Voice -->--}}
{{--                                                                        <div class="icon remove-voice deleteRecording d-none">--}}
{{--                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">--}}
{{--                                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>--}}
{{--                                                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>--}}
{{--                                                                            </svg>--}}
{{--                                                                            <span class="ms-2">--}}
{{--                                                                حذف التسجيل--}}
{{--                                                            </span>--}}
{{--                                                                        </div>--}}

{{--                                                                        <!-- Timer -->--}}
{{--                                                                        <span class="timer d-none">--}}
{{--                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass" viewBox="0 0 16 16">--}}
{{--                                                                <path d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z"/>--}}
{{--                                                            </svg>--}}
{{--                                                            <span id="timer">00:00</span>--}}
{{--                                                        </span>--}}

{{--                                                                    </div>--}}
{{--                                                                    <!-- Voice Audio-->--}}
{{--                                                                    <div class="recorder-player d-none" id="voice_audio_{{$question->id}}">--}}
{{--                                                                        <!-- crossorigin -->--}}
{{--                                                                        <div class="audio-player">--}}
{{--                                                                            <audio >--}}
{{--                                                                                <source src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/355309/Swing_Jazz_Drum.mp3" type="audio/mpeg">--}}
{{--                                                                            </audio>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
                                                                <div class="recorder-box" id="recorder-{{$question->id}}">
                                                                    <div class="controls">
                                                                        <!-- TicketId -->
                                                                        <input type="hidden" id="recorder_url_{{$question->id}}" class="recorder_url"
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
                                                                            <span class="ms-2">
                                                                سجل إجابتك
                                                            </span>
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
                                                                        <div class="icon btn  btn-soft-success saveRecording d-none" data-id="{{$question->id}}"
                                                                             style="background-color: rgb(46, 204, 112) !important; color: #FFF !important;">
                                                    <span class="icon-spinner d-none ">
                                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                                        <span> جاري الحفظ </span>
                                                    </span>
                                                                            <span class=" text ms-2"> حفظ</span>
                                                                        </div>

                                                                    </div>
                                                                    <!-- Voice Audio-->
                                                                    <div class="recorder-player d-none" id="voice_audio_{{$question->id}}">
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
{{--                                <div class="text-center">--}}
{{--                                    <button type="button" class="btn btn-theme @if(count($questions) == 1) @else d-none @endif endExam" id="confirmed_modal"--}}
{{--                                            data-toggle="modal" data-target="#endExam"--}}
{{--                                            style="font-weight: bold;background-color: #0043b3;">--}}
{{--                                        <span class="txt" style="font-size: 18px">حفظ و إنهاء</span>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
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
            var URL = '{{route('lesson_speaking_test', $lesson->id)}}';
            var METHOD = 'post';
            var fd = new FormData();

            // $("#modal-confirm").modal("hide");

            fd.append('question_id', $(this).attr('data-id'));
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
                console.log(data);

                $(this).find(".text").toggleClass("d-none");
                $(this).find(".icon-spinner").toggleClass("d-none");
                $(this).css('pointer-events', "pointer");
                setTimeout(function () {
                    window.location.href = "{{route('lessons', [$lesson->grade->grade_number, $lesson->lesson_type])}}";
                }, 2000)
            }).fail(function (data) {
                toastr.error(data.responseJSON.message);
                $(this).find(".text").toggleClass("d-none");
                $(this).find(".icon-spinner").toggleClass("d-none");
                $(this).css('pointer-events', "pointer");
            });
        })


    </script>
@endsection

