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
                                @if(!is_null($user_lesson->attach_writing_answer))
                                    <div class="form-group row">
                                        <label
                                            class="col-xl-3 col-lg-3 col-form-label">{{ t('Attachment Writing answer') }}</label>
                                        <div class="col-lg-9 col-xl-6">
                                            {{--                                                <div class="upload-btn-wrapper">--}}
                                            {{--                                                    <button class="btn btn-danger">{{ t('upload attach') }}</button>--}}
                                            {{--                                                    <input name="attach_writing_answer" class="imgInp" id="imgInp" type="file" />--}}
                                            {{--                                                </div>--}}
                                            <img src="{{$user_lesson->attach_writing_answer}}"/>


                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing answer') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea class="form-control"
                                                  name="writing_answer">{{$user_lesson->writing_answer}}</textarea>
                                    </div>
                                    <div class="col-xl-3">
                                    </div>
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Writing mark') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="writing_mark" type="number"
                                               value="{{ $user_lesson->writing_mark }}">
                                    </div>
                                </div>
                                <div class="form-group row">

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
                                    <div class="col-xl-3">
                                    </div>
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Reading mark') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="reading_mark" type="number"
                                               value="{{ $user_lesson->reading_mark }}">
                                    </div>
                                </div>
                                <div class="form-group row">

                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Feedback') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea class="form-control"
                                                  name="teacher_message">{{$user_lesson->teacher_message}}</textarea>
                                    </div>
                                    <div class="col-xl-3">
                                    </div>
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Record your Feedback') }}</label>
                                    <div class="col-lg-9 col-xl-6 justify-content-center text-center">
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
                                        @if($user_lesson && !is_null($user_lesson->teacher_audio_message) )
                                            <audio src="{{asset($user_lesson->teacher_audio_message)}}" controls></audio>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">

                                </div>
                                <div style="margin-top:15px;" class="center-block text-center">

                                    {{--                                                    <input class="form-control file-recorder" type="file"--}}
                                    {{--                                                           style="margin-top: 10px;min-height: 60px;padding: 20px;"--}}
                                    {{--                                                           accept="audio/*"--}}
                                    {{--                                                           capture="user" id="record_file"--}}
                                    {{--                                                           name="record_file">--}}
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
                                    <button type="submit" id="save" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
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
    <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>

    {!! JsValidator::formRequest(\App\Http\Requests\UserLessonRequest::class, '#form_information'); !!}

    <script>
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


        $("#form_information").submit(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $('#save').prop('disabled', true);
            $('#save').text('{{t('Please wait, saving ...')}}');

            document.getElementById("start-btn").disabled = true;
            document.getElementById("stop-btn").disabled = true;
            document.getElementById("delete-btn").disabled = true;
            if (testerAudio == '') {
                testerAudio = new Blob(['no file'], {type: "text/plain"});
            }

            uploadBlob(testerAudio);

            function uploadBlob(testerAudio) {
                var reader = new FileReader();
                // this function is triggered once a call to readAsDataURL returns
                reader.onload = function (event) {
                    var fd = new FormData($("#form_information")[0]);
                    fd.append('record1', testerAudio, filename);
                    console.log(fd);
                    $.ajax({
                        url: '{{ route('teacher.students_works.update', $user_lesson->id) }}',
                        data: fd,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            // xhr.onprogress = function e() {
                            //     // For downloads
                            //     if (e.lengthComputable) {
                            //         console.log(e.loaded / e.total);
                            //     }
                            // };
                            // xhr.upload.onprogress = function (e) {
                            //     $('.progress').show();
                            //     $('#message').show();
                            //     var percent_value = 0;
                            //     var position = event.loaded || event.position;
                            //     var total = event.total;
                            //     if (event.lengthComputable) {
                            //         percent_value = Math.ceil(position / total * 100);
                            //     }
                            //     //update progressbar
                            //     var percentVal = percent_value + '%';
                            //     bar.width(percentVal)
                            //     percent.html(percentVal);
                            // };
                            return xhr;
                        },
                        success: function (data) {
                            toastr.success("{{t('Successfully saved')}}");
                        },
                        error: function (data) {
                            $('#save').prop('disabled', false);
                            $('#save').text("{{ t('Save') }}");
                            document.getElementById("start-btn").disabled = false;
                            document.getElementById("stop-btn").disabled = true;
                            document.getElementById("delete-btn").disabled = false;

                        }
                    }).done(function (data) {
                        setTimeout(function () {
                            window.location.href = "{{route('teacher.students_works.index')}}";
                        }, 500);
                    });
                };
                // trigger the read from the reader...
                reader.readAsDataURL(testerAudio);
            }
        });

    </script>

@endsection
