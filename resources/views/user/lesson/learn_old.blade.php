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
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('lessons', [$lesson->grade_id, $lesson->lesson_type]) }}"
           @if(isset($lesson->grade) && !is_null($lesson->grade->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold" @endif>الدروس</a>
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
                                                class="fa fa-arrow-right"></i> العودة للدروس </a></div>
                                    <div class="col-md-4 text-center"><h3>{{$lesson->name}}
                                            - {{$lesson->section_type_name}}</h3></div>
                                    <div class="col-md-4 text-left"><a
                                            href="{{route('lesson', [$lesson->id,'training'])}}"
                                            class="btn btn-lg btn-info">الذهاب للتدريب <i class="fa fa-arrow-left"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-0">



                                <hr/>
                                <h5 class="mt-3  w-100 mb-5 text-center"
                                    style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;">
                                    Read loudly أقرأ بصوتٍ عالٍ - Do you want to practice your reading? it’s your turn
                                    now
                                </h5>

                                <div class="row px-4 my-2" id="tasks"
                                     style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px; overflow: scroll">
                                    @if($lesson->getFirstMediaUrl('audioLessons'))
                                    <button type="button" data-id="{{$lesson->id}}"
                                            class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                        <i class="fa fa-play-circle fa-2x"></i>

                                    </button>
                                    <audio style="display:none" id="audio{{$lesson->id}}" data-id="{{$lesson->id}}" src="{{asset($lesson->getFirstMediaUrl('audioLessons'))}}"></audio>
                                    @endif
                                    <div class="col-md-12 mt-4">
                                        {!! $lesson->content !!}
                                    </div>
                                </div>
                                <hr/>


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
    <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
    <script type="text/javascript" src="https://www.arabic-keyboard.org/keyboard/keyboard.js" charset="UTF-8"></script>

{{--    <script type="text/javascript" src="{{asset('mok/dist/main.js')}}"></script>--}}
{{--    <link rel="stylesheet" type="text/css" href="{{asset('mok/dist/styles.css')}}">--}}
    <script type="text/javascript">
        // $(document).ready(function () {
        //     $(document).keyboard({
        //         language: 'arabic:العَرَبِيَّة',
        //         keyboardPosition: 'bottom',
        //         inputType: 	'text, textarea, number, password, search, tel, url, contenteditable',
        //
        //
        //     });
        // });
    </script>
    <script>


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

        //webkitURL is deprecated but nevertheless
        URL = window.URL || window.webkitURL;

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        var filename = '';
        var testerAudio = '';
        var gumStream; 						//stream from getUserMedia()
        var rec; 							//Recorder.js object
        var input; 							//MediaStreamAudioSourceNode we'll be recording

        // shim for AudioContext when it's not avb.
        var AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext //audio context to help us record

        var recordButton = document.getElementById("start-btn");
        var stopButton = document.getElementById("stop-btn");
        var deleteButton = document.getElementById("delete-btn");
        var recordingsList = document.getElementById("recordingslist");

        //add events to those 2 buttons
        recordButton.addEventListener("click", startRecording);
        stopButton.addEventListener("click", stopRecording);
        deleteButton.addEventListener("click", deleteRecording);

        function startRecording() {
            console.log("recordButton clicked");

            /*
            Simple constraints object, for more advanced audio features see
            https://addpipe.com/blog/audio-constraints-getusermedia/
            */

            var constraints = {audio: true, video: false}

            /*
            Disable the record button until we get a success or fail from getUserMedia()
            */

            recordButton.disabled = true;
            stopButton.disabled = false;
            deleteButton.disabled = true

            /*
            We're using the standard promise based getUserMedia()
            https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
            */

            navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
                console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

                /*
                create an audio context after getUserMedia is called
                sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
                the sampleRate defaults to the one set in your OS for your playback device

                */
                audioContext = new AudioContext();

//update the format
// document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

                /*  assign to gumStream for later use  */
                gumStream = stream;

                /* use the stream */
                input = audioContext.createMediaStreamSource(stream);

                /*
                Create the Recorder object and configure to record mono sound (1 channel)
                Recording 2 channels  will double the file size
                */
                rec = new Recorder(input, {numChannels: 1})

//start the recording process
                rec.record()

                console.log("Recording started");

            }).catch(function (err) {
//enable the record button if getUserMedia() fails
                recordButton.disabled = false;
                stopButton.disabled = true;
                deleteButton.disabled = true
            });
        }

        function deleteRecording() {
            console.log("deleteButton clicked rec.recording=", rec.recording);
            event.stopPropagation();
            $('#recordingslist').empty();
            testerAudio = '';
// Disable Record button and enable stop button !
            recordButton.disabled = false;
            stopButton.disabled = true;
            deleteButton.disabled = true;
        }

        function stopRecording() {
            console.log("stopButton clicked");

//disable the stop button, enable the record too allow for new recordings
            stopButton.disabled = true;
            recordButton.disabled = true;
            deleteButton.disabled = false;

//reset button just in case the recording is stopped while paused
// deleteButton.innerHTML="Pause";

//tell the recorder to stop the recording
            rec.stop();

//stop microphone access
            gumStream.getAudioTracks()[0].stop();

//create the wav blob and pass it on to createDownloadLink
            rec.exportWAV(createDownloadLink);
        }

        function createDownloadLink(blob) {
            testerAudio = blob;
            var url = URL.createObjectURL(blob);
            var au = document.createElement('audio');
            var li = document.createElement('li');
// var link = document.createElement('a');

//name of .wav file to use during upload and download (without extendion)
            filename = new Date().toISOString();

//add controls to the <audio> element
            au.controls = true;
            au.src = url;

// //save to disk link
// link.href = url;
// link.download = filename+".wav"; //download forces the browser to donwload the file using the  filename
// link.innerHTML = "Save to disk";

//add the new audio element to li
            li.appendChild(au);

//add the filename to the li
// li.appendChild(document.createTextNode(filename+".wav "))

// //add the save to disk link to li
// li.appendChild(link);
//
// //upload link
// var upload = document.createElement('a');
// upload.href="#";
// upload.innerHTML = "Upload";
// li.appendChild(document.createTextNode (" "))//add a space in between
// li.appendChild(upload)//add the upload link to li

//add the li element to the ol
            recordingsList.appendChild(li);
        }


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
                    fd.append('record1', testerAudio, filename);
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
                                $('#message').show();
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
                            window.location.href = "{{route('lesson', [$lesson->id, 'training'])}}";
                        }, 4000);
                    });
                };
// trigger the read from the reader...
                reader.readAsDataURL(testerAudio);
            }
        });

    </script>
@endsection
