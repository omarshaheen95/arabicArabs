{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')
    <style>
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
    </style>
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('lessons', $lesson->level_id) }}"
           @if(isset($level) && !is_null($level->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold" @endif>{{ t('Lessons') }}</a>
    </li>
@endpush
@section('content')
    <section class="inner-page">
        <section>
            <div class="text-right">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4 border-0">
                            <div class="card-header bg-white  font-weight-bold">
                                <div class="row">
                                    <div class="col-md-4"><a href="{{route('home')}}" class="btn btn-lg btn-danger"><i
                                                class="fa fa-arrow-right"></i> Back to the lessons </a></div>
                                    <div class="col-md-4 text-center"><h3>{{$lesson->translate('ar')->name}}
                                            - {{$lesson->translate('en')->name}}</h3></div>
                                    <div class="col-md-4 text-left"><a
                                            href="{{route('lesson', [$lesson->id,'training'])}}"
                                            class="btn btn-lg btn-info">Go to practice <i class="fa fa-arrow-left"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div class="card-header bg-white text-center font-weight-bold">
                                    <h5 class="w-100 text-center"
                                        style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;">
                                        استمع إلى المفردات وأعد قراءتها - Listen to the vocabularies and repeat the
                                        words</h5>
                                </div>
                                <div class=""
                                     style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px;">
                                    @foreach($lesson->words as $key => $word)
                                        <div class="row px-4 my-3 font-weight-bold text-center">
                                            <div class="col-4 text-danger"
                                                 style="align-items: center;justify-content:center;display: flex;">
                                                {{$word->translate('ar')->content}}
                                            </div>
                                            <div class="col-4 text-primary"
                                                 style="align-items: center;justify-content:center;display: flex;">
                                                {{$word->translate('en')->content}}
                                            </div>
                                            <div class="col-4">

                                                <button type="button" data-id="{{$word->id}}"
                                                        class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                    <i class="fa fa-play-circle fa-2x"></i>

                                                </button>
                                                <audio style="display:none" id="audio{{$word->id}}"
                                                       data-id="{{$word->id}}" src="{{asset($word->file)}}"></audio>
                                                {{--                                            <div class="player" style="width: 100%; direction: ltr">--}}
                                                {{--                                               <audio >--}}
                                                {{--                                                   <source src="{{asset($word->file)}}">--}}
                                                {{--                                               </audio>--}}
                                                {{--                                            </div>--}}
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr/>
                                        @endif
                                    @endforeach

                                </div>

                                @if(count($lesson->lesson_videos))
                                    <hr/>
                                    <h5 class="mt-4 w-100 text-center"
                                        style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;">
                                        انظر إلى الكلمات في الفيديو وأعد قراءتها - Look at the words in the video and
                                        repeat the words
                                    </h5>
                                    <div class="row px-4 my-2 justify-content-center"
                                         style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px;">
                                        @foreach($lesson->lesson_videos as $lesson_video)
                                            <div class="col-10 text-center">
                                                {{--                                            <h5>{{$lesson_video->title}}</h5>--}}
                                                <div id="video{{$lesson_video->id}}" class="mb-4"></div>
                                            </div>
                                        @endforeach
                                    </div>

                                @endif

                                <hr/>
                                <h5 class="mt-3  w-100 mb-5 text-center"
                                    style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;">
                                    Read loudly أقرأ بصوتٍ عالٍ - Do you want to practice your reading? it’s your turn
                                    now
                                </h5>

                                <div class="row px-4 my-2"
                                     style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px; overflow: scroll">
                                    <div class="col-md-12">
                                        {!! $lesson->content !!}
                                    </div>
                                </div>
                                <hr/>

                                <div class="row justify-content-center">
                                    @php
                                        $user_lesson = $lesson->user_lessons()->where('user_id', Auth::user()->id)->first();
                                    @endphp
                                    @if($user_lesson && $user_lesson->status == 'corrected')
                                        @if(!is_null($user_lesson->teacher_message))
                                            <div class="col-md-12  text-center mb-3">
                                                <h6 class="text-center" dir="ltr"
                                                    style="font-weight: bold;background-color: #EFEFEF;padding: 10px;border-left: 3px solid #0048ff;;border-right: 3px solid #0048ff;color: #F00">
                                                    Teacher Feedback
                                                    <br/>
                                                    <br/>
                                                    {{$user_lesson->teacher_message}}
                                                </h6>
                                            </div>
                                        @endif

                                        <div class="col-md-12  text-center mb-3">
                                            <p class="text-left mb-3 box-style" dir="ltr">
                                                Task 1 : Read the words/sentences in the box above out load and record it. Save and send the recording to your teacher
                                            </p>
                                            <p class="w-100 text-left bold" style="font-size: 18px; font-weight: bold">
                                                <label style="color: #F00">10/<span style="color: #1eb91e">{{$user_lesson->writing_mark}}</span></label>
                                            </p>
                                            @if($user_lesson)
                                                <br />
                                                <audio src="{{asset($user_lesson->reading_answer)}}" controls></audio>
                                            @endif
                                        </div>
                                        <hr />
                                        {{--                                            <div class="col-md-12">--}}
                                        {{--                                                {!! $lesson->content !!}--}}
                                        {{--                                            </div>--}}
                                        <div class="col-md-12 mb-3">
                                            {{--                                            <p class="text-left mb-3 box-style" dir="ltr">--}}
                                            {{--                                                Task 2 : Copy the words/sentences in the box above in the box below or--}}
                                            {{--                                                in your paper and attach it - save and send the work to your teacher.--}}
                                            {{--                                            </p>--}}
                                            <p class="text-left box-style" dir="ltr">
                                                Task 2 : For this question, you need to write your answer in Arabic, you can type it or write it on a paper, upload and attach it.
                                            </p>
                                            <div style="width: 100%">
                                                {!! optional($lesson->lesson_questions()->where('type', 'writing')->first())->question !!}
                                            </div>

                                            <p class="w-100 text-left bold" style="font-size: 18px; font-weight: bold">
                                                <label style="color: #F00">10/<span style="color: #1eb91e">{{$user_lesson->writing_mark}}</span></label>
                                            </p>
                                            <textarea disabled class="form-control" name="writing_answer"
                                                      placeholder="write your answer here">{{$user_lesson ? $user_lesson->writing_answer:''}}</textarea>
                                            <input disabled type="file" class="mt-2" name="writing_attachment">
                                            @if($user_lesson && $user_lesson->attach_writing_answer)
                                                <a href="{{asset($user_lesson->attach_writing_answer)}}"
                                                   target="_blank">Browse</a>
                                            @endif
                                        </div>
                                        <div class="col-md-12 my-2">
                                            <div class="progress">
                                                <div class="bar"></div>
                                                <div class="percent">0%</div>
                                            </div>
                                        </div>
                                    @else
                                        <form class="mb-5" id="lesson_form">
                                            {{csrf_field()}}
                                            @if($user_lesson && !is_null($user_lesson->teacher_message))
                                                <div class="col-md-12  text-center mb-3">
                                                    <h6 class="text-center" dir="ltr"
                                                        style="font-weight: bold;background-color: #EFEFEF;padding: 10px;border-left: 3px solid #0048ff;;border-right: 3px solid #0048ff; color: #F00">
                                                        Teacher Feedback
                                                        <br/>
                                                        <br/>
                                                        {{$user_lesson->teacher_message}}
                                                    </h6>
                                                </div>
                                            @endif

                                            <div class="col-md-12  text-center">
                                                <p class="text-left box-style" dir="ltr">
                                                    Task 1 : Read the words/sentences in the box above out load and record it. Save and send the recording to your teacher
                                                </p>
                                                <div style="margin-top:15px;" class="center-block text-center">
                                                    <label class="text-danger" id="spiner" style="display:none;">Recording
                                                        <i
                                                            class="fa fa-circle-o-notch fa-spin"
                                                            style="font-size:24px"></i></label>
                                                    <button class="btn btn-success btn-circle record-btn btn-lg"
                                                            type="button"
                                                            id="start-btn">
                                                        <i class="fa fa-play p-0"></i>
                                                    </button>
                                                    <button type="button" data-question=""
                                                            class="btn btn-danger btn-circle record-btn btn-lg"
                                                            id="stop-btn"
                                                            disabled>
                                                        <i class="fa fa-stop p-0"></i>
                                                    </button>

                                                    <button type="button"
                                                            class="btn btn-warning btn-circle record-btn btn-lg"
                                                            id="delete-btn" disabled>
                                                        <i class="fa fa-trash p-0"></i>
                                                    </button>
                                                    <ul id="recordingslist"></ul>

                                                    <input class="form-control file-recorder" type="file"
                                                           style="margin-top: 10px;min-height: 60px;padding: 20px;"
                                                           accept="audio/*"
                                                           capture="user" id="record_file"
                                                           name="record_file">
                                                </div>
                                                @if($user_lesson)
                                                    <audio src="{{asset($user_lesson->reading_answer)}}"
                                                           controls></audio>
                                                @endif
                                            </div>
                                            <hr />

                                            <div class="col-md-12  ">
                                                {{--                                                <p class="text-left box-style" dir="ltr">--}}
                                                {{--                                                    Task 2 : Copy the words/sentences in the box above in the box below--}}
                                                {{--                                                    or in your paper and attach it - save and send the work to your--}}
                                                {{--                                                    teacher.--}}
                                                {{--                                                </p>--}}
                                                <p class="text-left box-style" dir="ltr">
                                                    Task 2 : For this question, you need to write your answer in Arabic, you can type it or write it on a paper, upload and attach it.
                                                </p>
                                                <div style="width: 100%; direction: ltr" >
                                                    {!! optional($lesson->lesson_questions()->where('type', 'writing')->first())->question !!}
                                                </div>
                                                <textarea required class="form-control" name="writing_answer"
                                                          placeholder="write your answer here">{{$user_lesson ? $user_lesson->writing_answer:''}}</textarea>
                                                <input type="file" class="mt-2" name="writing_attachment">
                                                @if($user_lesson && $user_lesson->attach_writing_answer)
                                                    <a href="{{asset($user_lesson->attach_writing_answer)}}"
                                                       target="_blank">Browse</a>
                                                @endif
                                            </div>
                                            <div class="col-md-12 my-2">
                                                <div class="progress">
                                                    <div class="bar"></div>
                                                    <div class="percent">0%</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-left">
                                                <button type="submit" id="save" class="btn btn-danger">حفظ - Save
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
@section('script')
    <script src="{{asset('s_website/js/playerjs.js')}}"></script>
    <script src="{{ asset('assets/js/recorder.js') }}" type="text/javascript"></script>


    <script>

        @foreach($lesson->lesson_videos as $lesson_video)
        var player_{{$lesson_video->id}} = new Playerjs({
            id: "video{{$lesson_video->id}}",
            file: '{{asset($lesson_video->video)}}',
        });
        @endforeach
        $(document).ready(function () {

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
            setTimeout(function () {
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '{{route('track_lesson', [$lesson->id, 'learn'])}}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                    },
                    success: function (data) {
                    },
                    error: function (errMsg) {
                    }
                });

            }, 10000);
        });
        if (navigator.userAgent.indexOf("Chrome") == -1) {
            alert('Please Make sure you are using Google Chrome as browser');
        }
        if (/iPhone|iPad|iPod|Opera Mini/i.test(navigator.userAgent)) {
            // $(".file-recorder").removeAttr("accept");
            // $(".file-recorder").attr("accept","video/*");
            // $(".file-recorder").attr("capture","user");
        }
        if (/Android|BlackBerry|Tablet|Mobile|iPhone|iPad|iPod|Opera Mini/i.test(navigator.userAgent)) {
            $('#start-btn').hide();
            $('#stop-btn').hide();
            $('.record-btn').hide();

        } else {
            $(".file-recorder").hide();
        }

        var audio_context;
        var recorder;
        var audio_stream;
        var testerAudio = '';
        var record1;
        var record_name = [];
        var percentVal = '0%';

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');
        navigator.mediaDevices.getUserMedia({audio: true})
            .then(function (stream) {
                console.log('You let me use your mic!')
            })
            .catch(function (err) {
                $("#noAudio").modal("toggle");
            });

        function Initialize() {
            try {
                // Monkeypatch for AudioContext, getUserMedia and URL
                window.AudioContext = window.AudioContext || window.webkitAudioContext;
                //navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
                navigator.getUserMedia = (navigator.getUserMedia ||
                    navigator.webkitGetUserMedia ||
                    navigator.mozGetUserMedia ||
                    navigator.msGetUserMedia);
                window.URL = window.URL || window.webkitURL;

                // Store the instance of AudioContext globally
                audio_context = new AudioContext;
                console.log('Audio context is ready !');
                console.log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
            } catch (e) {
                alert('No web audio support in this browser!');
            }
        }

        function startRecording1() {


            // Access the Microphone using the navigator.getUserMedia method to obtain a stream
            navigator.getUserMedia({audio: true}, function (stream) {
                // Expose the stream to be accessible globally
                audio_stream = stream;
                // Create the MediaStreamSource for the Recorder library
                var input = audio_context.createMediaStreamSource(stream);
                console.log('Media stream succesfully created');

                // Initialize the Recorder Library
                recorder = new Recorder(input);
                console.log('Recorder initialised');

                // Start recording !
                recorder && recorder.record();
                console.log('Recording...');

                // Disable Record button and enable stop button !
                document.getElementById("start-btn").disabled = true;
                document.getElementById("stop-btn").disabled = false;
                document.getElementById("delete-btn").disabled = true;
            }, function (e) {
                console.error('No live audio input: ' + e);
            });
        }

        function stopRecording1(callback, AudioFormat) {

            // Stop the recorder instance
            recorder && recorder.stop();
            console.log('Stopped recording.');

            // Stop the getUserMedia Audio Stream !
            audio_stream.getAudioTracks()[0].stop();

            // Disable Stop button and enable Record button !
            document.getElementById("start-btn").disabled = true;
            document.getElementById("stop-btn").disabled = true;
            document.getElementById("delete-btn").disabled = false;
            //$('#recordingslist').empty();

            // Use the Recorder Library to export the recorder Audio as a .wav file
            // The callback providen in the stop recording method receives the blob
            if (typeof (callback) == "function") {

                /**
                 * Export the AudioBLOB using the exportWAV method.
                 * Note that this method exports too with mp3 if
                 * you provide the second argument of the function
                 */
                recorder && recorder.exportWAV(function (blob) {
                    callback(blob);

                    // create WAV download link using audio data blob
                    // createDownloadLink();
                    testerAudio = blob;
                    // Clear the Recorder to start again !
                    recorder.clear();
                }, (AudioFormat || "audio/wav"));
            }
        }

        // Initialize everything once the window loads
        window.onload = function () {
            // Prepare and check if requirements are filled
            Initialize();

            // Handle on start recording button
            document.getElementById("start-btn").addEventListener("click", function (event) {
                event.stopPropagation();
                $('#spiner').show();
                startRecording1();
            }, false);


            // Handle on stop recording button
            document.getElementById("stop-btn").addEventListener("click", function (event) {
                event.stopPropagation();
                // Use wav format
                var _AudioFormat = "audio/wav";
                // You can use mp3 to using the correct mimetype
                //var AudioFormat = "audio/mpeg";

                stopRecording1(function (AudioBLOB) {

                    // Note:
                    // Use the AudioBLOB for whatever you need, to download
                    // directly in the browser, to upload to the server, you name it !

                    // In this case we are going to add an Audio item to the list so you
                    // can play every stored Audio
                    var url = URL.createObjectURL(AudioBLOB);
                    var li = document.createElement('li');
                    var au = document.createElement('audio');

                    au.controls = true;
                    au.src = url;
                    li.appendChild(au);
                    recordingslist.appendChild(li);
                }, _AudioFormat);
                record1 = $(this).data("question");
                $('#spiner').hide();
            }, false);


            // Handle on delete recording button
            document.getElementById("delete-btn").addEventListener("click", function (event) {
                event.stopPropagation();
                $('#recordingslist').empty();
                testerAudio = '';
                // Disable Record button and enable stop button !
                document.getElementById("start-btn").disabled = false;
                document.getElementById("stop-btn").disabled = true;
                document.getElementById("delete-btn").disabled = true;
            }, false);


        };

        $("#save").click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $('#save').prop('disabled', true);
            document.getElementById("start-btn").disabled = true;
            document.getElementById("stop-btn").disabled = true;
            document.getElementById("delete-btn").disabled = true;
            // $(this).prop('disabled', true);
            if (testerAudio == '') {
                testerAudio = new Blob(['no file'], {type: "text/plain"});
            }
            uploadBlob(testerAudio);

            function uploadBlob(testerAudio) {
                var reader = new FileReader();
                // this function is triggered once a call to readAsDataURL returns
                reader.onload = function (event) {
                    var fd = new FormData($("#lesson_form")[0]);
                    fd.append('record1', testerAudio, record_name[1]);
                    console.log(fd);
                    $.ajax({
                        url: '{{ route('user_lesson', $lesson->id) }}',
                        data: fd,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            xhr.onprogress = function e() {
                                // For downloads
                                if (e.lengthComputable) {
                                    console.log(e.loaded / e.total);
                                }
                            };
                            xhr.upload.onprogress = function (e) {
                                $('.progress').show();
                                var percent_value = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent_value = Math.ceil(position / total * 100);
                                }
                                //update progressbar
                                var percentVal = percent_value + '%';
                                bar.width(percentVal)
                                percent.html(percentVal);
                            };
                            return xhr;
                        },
                        success: function (data) {
                            toastr.success(data);
                        }
                    }).done(function (data) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 4000);
                    });
                };
                // trigger the read from the reader...
                reader.readAsDataURL(testerAudio);
            }
        });

    </script>
@endsection
