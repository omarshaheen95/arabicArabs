//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream;	//stream from getUserMedia()
var rec;		//Recorder.js object
var input;		//MediaStreamAudioSourceNode we'll be recording
var audio_player_id;
var recorder_url;
var blob_recorder_url;
var question_id;
var blob_recorder_files = [];
var refreshIntervalId = 0;


// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

/*-----------------------------------
    startRecording
-----------------------------------*/

$(document).on("click", ".startRecording", function(){
    var mainRecorderBox = $(this).parent().parent().parent();

    //	console.log("recordButton clicked");
    var constraints = { audio: true, video:false }
    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
        //		console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

        audioContext = new AudioContext();
        gumStream = stream;

        input = audioContext.createMediaStreamSource(stream);
        rec = new Recorder(input,{numChannels:1})

        rec.record();

        //	console.log("Recording started");

        $(".recorder-box").find(".startRecording").addClass("disabled");
        console.log('disabled startRecording')
        mainRecorderBox.find(".startRecording").removeClass("disabled");
        mainRecorderBox.find(".icon").addClass("d-none");
        mainRecorderBox.find(".recorder-player").addClass("d-none");
        mainRecorderBox.find(".timer").removeClass("d-none");
        mainRecorderBox.find("#timer").text("00:00");
        mainRecorderBox.find(".stop-voice").removeClass("d-none");
        mainRecorderBox.find("#url").val('');
        var elapsed_seconds = 0;
        refreshIntervalId = setInterval(function(){
            elapsed_seconds = elapsed_seconds + 1;
            mainRecorderBox.find(".timer").text(get_elapsed_time_string(elapsed_seconds));
        }, 1000);

    }).catch(function(err) {
        //enable the record button if getUserMedia() fails
        //	recordButton.disabled = false;
        //	stopButton.disabled = true;
        //	pauseButton.disabled = true
    });
});

/*-----------------------------------
    stopRecording
-----------------------------------*/

$(document).on("click", ".stopRecording", function(){
    var mainRecorderBox = $(this).parent().parent().parent();
    rec.stop();
    $(".recorder-box").find(".startRecording").removeClass("disabled");
    mainRecorderBox.find(".icon").addClass("d-none");
    mainRecorderBox.find(".remove-voice").removeClass("d-none");
    mainRecorderBox.find(".start-voice").removeClass("d-none");
    mainRecorderBox.find(".recorder-player").removeClass("d-none");

    clearInterval(refreshIntervalId); // stop Interval time
    audio_player_id = mainRecorderBox.find(".recorder-player").attr("id");
    // alert(audio_player_id);
    recorder_url = mainRecorderBox.find(".recorder_url").attr("id");
    question_id = mainRecorderBox.find(".recorder_url").attr("data-id");
    blob_recorder_url = mainRecorderBox.find(".blob_recorder_url").attr("id");

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();
    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);

});

/*-----------------------------------
    deleteRecording
-----------------------------------*/

$(document).on("click", ".deleteRecording", function(){
    var mainRecorderBox = $(this).parent().parent().parent();

    $(".recorder-box").find(".startRecording").removeClass("disabled");
    mainRecorderBox.find(".icon").addClass("d-none");
    mainRecorderBox.find(".start-voice").removeClass("d-none");
    mainRecorderBox.find(".recorder-player").addClass("d-none");
    mainRecorderBox.find(".timer").addClass("d-none");
    mainRecorderBox.find("#timer").text("00:00");
    mainRecorderBox.find("#recorder-url").val("");
    mainRecorderBox.find(".recorder_url").val("");
    audio_player_id = "";
    recorder_url = "";
    blob_recorder_url = "";
    //
    // audio.pause();
    // audio.currentTime = 0;
});

function createDownloadLink(blob) {

    var url = URL.createObjectURL(blob);
    //var au = document.getElementById('audio');
    //var TicketId = document.getElementById('TicketId');
    //var upload = document.getElementById('upload_voice');
    //var download = document.getElementById('download');

    //name of .wav file to use during upload and download (without extendion)
    var filename = new Date().toISOString();
    // alert(audio_player_id);
    $("#"+audio_player_id+" audio").attr("src", url);
    $("#"+recorder_url).val(url);
    // $("#"+blob_recorder_url).val(blob);

    blob_recorder_files[question_id] = blob;
    //add controls to the <audio> element
    //au.src = url;
    //download.href = url;
    //download.download = filename+".wav";

    /*
    upload.addEventListener("click", function(event){
        var xhr=new XMLHttpRequest();
        xhr.onload=function(e) {
            if(this.readyState === 4) {
                console.log("Server returned: ",e.target.responseText);
            }
        };
        var fd=new FormData();
    //	fd.append("TicketId",TicketId);
        fd.append("recorder_url", blob, filename);
        xhr.open("POST","",true);
        xhr.send(fd);
    });
    */

    console.log(blob_recorder_files);
}


function get_elapsed_time_string(total_seconds) {
    function pretty_time_string(num) {
        return ( num < 10 ? "0" : "" ) + num;
    }

    /*var hours = Math.floor(total_seconds / 3600);
    total_seconds = total_seconds % 3600;*/

    var minutes = Math.floor(total_seconds / 60);
    total_seconds = total_seconds % 60;

    var seconds = Math.floor(total_seconds);

    // Pad the minutes and seconds with leading zeros, if required
    //hours = pretty_time_string(hours);
    minutes = pretty_time_string(minutes);
    seconds = pretty_time_string(seconds);

    // Compose the string for display
    var currentTimeString = minutes + ":" + seconds;

    return currentTimeString;
}
