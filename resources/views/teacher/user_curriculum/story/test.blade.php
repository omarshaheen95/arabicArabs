
@extends('teacher.user_curriculum.layout.container_v2')
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> {{$story->name}}  </h1>
                        <h1 class="title"> <p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p>  </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('teacher.stories.list', $story->grade)}}"> المستويات </a>
                            <span class="breadcrumb-item active" aria-current="page"> القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form action="{{route('story_test', $story->id)}}" id="term_form" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">

                            <div class="question-list">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach($questions as $question)
                                    @if($question->type == 1)
                                        <div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> {{$counter}} : </span>
                                                <span class="title">ضع علامة (صح) أو (خطأ)</span>
                                            </div>
                                            <div class="exercise-box-body">
                                                <div class="exercise-question">
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            {{$question->content}}
                                                        </div>
                                                    </div>
                                                    <div class="exercise-question-answer text-center my-4">

                                                        @if(!is_null($question->attachment))

                                                            <div class="row justify-content-center py-3">
                                                                <div class="col-lg-6 col-md-8">
                                                                    @if(\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                        <div class="recorder-player" id="voice_audio_2">
                                                                            <div class="audio-player">
                                                                                <audio >
                                                                                    <source
                                                                                        src="{{asset($question->attachment)}}"
                                                                                        type="audio/mpeg">
                                                                                </audio>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="w-100 text-center">
                                                                            <img src="{{asset($question->attachment)}}"
                                                                                 width="300px">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="true-false-question">
                                                            <div class="answer-box">
                                                                <input
                                                                    type="radio" question-grop-name="q{{$counter}}"
                                                                    name="tf[{{$question->id}}]" value="1"
                                                                    id="true-{{$question->id}}" class="d-none">
                                                                <label for="true-{{$question->id}}"
                                                                       class="option option-true">
                                                                    <svg id="Group_68450" data-name="Group 68450"
                                                                         xmlns="http://www.w3.org/2000/svg" width="80"
                                                                         height="80" viewBox="0 0 102 102">
                                                                        <g id="Ellipse_360" data-name="Ellipse 360" fill="#fff"
                                                                           stroke="#2ecc71" stroke-width="1">
                                                                            <circle cx="51" cy="51" r="51" stroke="none"/>
                                                                            <circle cx="51" cy="51" r="50.5" fill="none"/>
                                                                        </g>
                                                                        <circle id="Ellipse_180" data-name="Ellipse 180" cx="41"
                                                                                cy="41" r="41" transform="translate(10 10)"
                                                                                fill="#2ecc71"/>
                                                                        <path id="Shape"
                                                                              d="M12.176,23.045,3.093,13.962,0,17.033,12.176,29.209,38.314,3.071,35.243,0Z"
                                                                              transform="translate(32.856 37.409)" fill="#fff"/>
                                                                    </svg>
                                                                </label>
                                                            </div>
                                                            <div class="answer-box">
                                                                <input
                                                                    type="radio" question-grop-name="q{{$counter}}"
                                                                    name="tf[{{$question->id}}]" value="0"
                                                                    id="false-{{$question->id}}" class="d-none">
                                                                <label for="false-{{$question->id}}"
                                                                       class="option option-false">
                                                                    <svg id="Group_68451" data-name="Group 68451"
                                                                         xmlns="http://www.w3.org/2000/svg" width="80"
                                                                         height="80" viewBox="0 0 102 102">
                                                                        <g id="Ellipse_361" data-name="Ellipse 361" fill="#fff"
                                                                           stroke="#dc3545" stroke-width="1">
                                                                            <circle cx="51" cy="51" r="51" stroke="none"/>
                                                                            <circle cx="51" cy="51" r="50.5" fill="none"/>
                                                                        </g>
                                                                        <circle id="Ellipse_362" data-name="Ellipse 362" cx="41"
                                                                                cy="41" r="41" transform="translate(10 10)"
                                                                                fill="#dc3545"/>
                                                                        <g id="close" transform="translate(38.938 38.938)">
                                                                            <line id="Line_1" data-name="Line 1" x2="26" y2="26"
                                                                                  transform="translate(-0.938 -0.938)"
                                                                                  fill="none" stroke="#fff"
                                                                                  stroke-linecap="round" stroke-width="6"/>
                                                                            <line id="Line_2" data-name="Line 2" x1="26" y2="26"
                                                                                  transform="translate(-0.938 -0.938)"
                                                                                  fill="none" stroke="#fff"
                                                                                  stroke-linecap="round" stroke-width="6"/>
                                                                        </g>
                                                                    </svg>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($question->type == 2)
                                        <div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> {{$counter}} : </span>
                                                <span class="title"> اختر الإجابة الصحيحة</span>
                                            </div>
                                            <div class="exercise-box-body">
                                                <div class="exercise-question">
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            {{$question->content}}
                                                        </div>
                                                    </div>
                                                    <div class="exercise-question-answer text-center my-4">

                                                        @if(!is_null($question->attachment))

                                                            <div class="row justify-content-center py-3">
                                                                <div class="col-lg-6 col-md-8">
                                                                    @if(\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                        <div class="recorder-player" id="voice_audio_2">
                                                                            <div class="audio-player">
                                                                                <audio >
                                                                                    <source
                                                                                        src="{{asset($question->attachment)}}"
                                                                                        type="audio/mpeg">
                                                                                </audio>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="w-100 text-center">
                                                                            <img src="{{asset($question->attachment)}}"
                                                                                 width="300px">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="exercise-question-answer text-center my-4">

                                                            <div class="multi-choice-question">
                                                                @foreach($question->options as $option)
                                                                    <div class="answer-box">
                                                                        <input id="option-{{$option->id}}"
                                                                               type="radio" question-grop-name="q{{$counter}}"
                                                                               name="option[{{$question->id}}]"
                                                                               value="{{$option->id}}" class="co_q d-none">

                                                                        <label for="option-{{$option->id}}"
                                                                               class="option option-true">
                                                                            {{$option->content}}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($question->type == 3)
                                        <div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> {{$counter}} : </span>
                                                <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل</span>
                                            </div>
                                            <div class="exercise-box-body">
                                                <div class="exercise-question">
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            {{$question->content}}
                                                        </div>
                                                        <div class="exercise-question-answer text-center my-4">
                                                            @if(!is_null($question->attachment))

                                                                <div class="row justify-content-center py-3">
                                                                    <div class="col-lg-6 col-md-8">
                                                                        @if(\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                            <div class="recorder-player" id="voice_audio_2">
                                                                                <div class="audio-player">
                                                                                    <audio >
                                                                                        <source
                                                                                            src="{{asset($question->attachment)}}"
                                                                                            type="audio/mpeg">
                                                                                    </audio>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="w-100 text-center">
                                                                                <img src="{{asset($question->attachment)}}"
                                                                                     width="300px">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="multi-choice-question">
                                                                <div class="w-100 answers">
                                                                    <div class="row justify-content-center">

                                                                        <div class="col-md-12">
                                                                            <ul id="match_{{$question->id}}_result"
                                                                                class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                                @foreach($question->matches()->inRandomOrder()->get() as $match)
                                                                                    <li class="ui-state-default mb-2"
                                                                                        data-id="{{$match->id}}">
                                                                                        <div>
                                                                                            <text>{{$match->result}} </text>
                                                                                            <span class="float-right"></span>
                                                                                            <input type="hidden"
                                                                                                   name="re[{{$question->id}}][{{$match->id}}]"
                                                                                                   id="" value="">
                                                                                        </div>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="ewewe">
                                                                                <div class="row">
                                                                                    <div class="col-lg-8">
                                                                                        <ul class="connectedNoSortable list-unstyled m-0 font-bold active text-right m-0 p-0">
                                                                                            @php
                                                                                                $n_counter = 1;
                                                                                            @endphp
                                                                                            @foreach($question->matches as $match)
                                                                                                <li class="ui-state-default mb-2">
                                                                                                    @if(!is_null($match->image))
                                                                                                        <div
                                                                                                            class="row justify-content-center ">
                                                                                                            <div
                                                                                                                class="col-md-12 text-center">
                                                                                                                <img
                                                                                                                    src="{{asset($match->image)}}"
                                                                                                                    style="width:100%; max-width: 100px"/>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @else
                                                                                                        <span
                                                                                                            class="ml-3"></span>

                                                                                                        <text>{{$match->content}}</text>
                                                                                                    @endif
                                                                                                </li>
                                                                                                @php
                                                                                                    $n_counter ++;
                                                                                                @endphp
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div class="col-lg-4 ">
                                                                                        <div class="position-relative">
                                                                                            <ul class="m-0 p-0 list-unstyled add-ansar position-absolute w-100">
                                                                                                @foreach($question->matches as $match)
                                                                                                    @if(is_null($match->image))
                                                                                                        @php
                                                                                                            $styleClass = "textOnly";
                                                                                                        @endphp
                                                                                                        <li></li>
                                                                                                    @else
                                                                                                        @php
                                                                                                            $styleClass = "imageOnly";
                                                                                                        @endphp
                                                                                                        <li class="ui-state-default mb-2"
                                                                                                            style="height: auto !important;">
                                                                                                            <div
                                                                                                                class="row justify-content-center">
                                                                                                                <div
                                                                                                                    class="col-12">
                                                                                                                    <div>
                                                                                                                        <div
                                                                                                                            style="width: 100px; height: 122px"></div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </ul>
                                                                                            <ul id="match_{{$question->id}}_answer"
                                                                                                question-id="{{$counter}}"
                                                                                                class="sortable2 connectedSortable list-unstyled m-0 font-bold {{$styleClass}} active text-center m-0 p-0">
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @elseif($question->type == 4)
                                        <div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> {{$counter}} : </span>
                                                <span class="title">  اسحب ورتب الإجابات في
                                        المكان أدناه </span>
                                            </div>
                                            <div class="exercise-box-body">
                                                <div class="exercise-question">
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            {{$question->content}}
                                                        </div>
                                                        <div class="exercise-question-answer text-center my-4">
                                                            @if(!is_null($question->attachment))

                                                                <div class="row justify-content-center py-3">
                                                                    <div class="col-lg-6 col-md-8">
                                                                        @if(\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                            <div class="recorder-player" id="voice_audio_2">
                                                                                <div class="audio-player">
                                                                                    <audio >
                                                                                        <source
                                                                                            src="{{asset($question->attachment)}}"
                                                                                            type="audio/mpeg">
                                                                                    </audio>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="w-100 text-center">
                                                                                <img src="{{asset($question->attachment)}}"
                                                                                     width="300px">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="answers">
                                                                <div class="row justify-content-center">

                                                                    <div class="col-md-12">
                                                                        <ul id="sort_{{$question->id}}_answer"
                                                                            class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                            @foreach($question->sort_words()->inRandomOrder()->get() as $word)
                                                                                <li class="ui-state-default mb-2"
                                                                                    data-id="{{$word->id}}">
                                                                                    <div>
                                                                                        <text>{{$word->content}} </text>
                                                                                        <span class="float-right"></span>
                                                                                        <input type="hidden" name="sort[{{$question->id}}][{{$word->id}}]" id="" value="" >
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div class="ewewe">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="position-relative">
                                                                                        <ul class="m-0 p-0 list-unstyled add-ansar  position-absolute w-100">

                                                                                        </ul>
                                                                                        <ul id="" question-id="{{$counter}}" class="sortable2 sort_words connectedSortable list-unstyled m-0 font-bold active text-center m-0 p-0">
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    @endif
                                    @php
                                        $counter ++;
                                    @endphp
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center question-control btn-wizard">
                                <div class="text-center">
                                    <button type="button" class="d-none btn btn-light border"
                                            id="previousQuestion"><span class="txt"
                                                                        style="font-size: 18px">  السابق </span>
                                    </button>
                                </div>
                                <div class="text-center">
{{--                                    <button type="button" class="btn btn-theme d-none endExam" id="confirmed_modal" data-toggle="modal" data-target="#endExam" style="font-weight: bold;background-color: #0043b3;">--}}
{{--                                        <span class="txt" style="font-size: 18px">إنهاء و حفظ</span>--}}
{{--                                    </button>--}}
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-theme"
                                            id="nextQuestion">
                                        <span class="txt" style="font-size: 18px"> التالي  </span></button>
                                </div>
                            </div>


                            <div class="table-footer font-weight-bold">
                                <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                </ul>
                            </div>
                            <!-- Modal -->

                            <div class="modal fade" id="endExam" tabindex="-1" role="dialog" aria-labelledby="endExamLabel" aria-hidden="true">

                                <div class="modal-dialog">
                                    <div class="modal-content" style="padding: 15px;">
                                        <div class="modal-header border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    </script>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('s_website/js/jquery.ui.touch-punch.js')}}"></script>

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

        Date.prototype.addHours = function (h) {
            this.setTime(this.getTime() + (h * 60 * 60 * 1000));
            return this;
        }
        // Set the date we're counting down to
        var countDownDate = new Date().addHours(0.1666666).getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML = minutes + ":" + seconds;
            // console.log(distance);
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $('term_form').submit();
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);
        $( function() {
            $( ".sortable1, .sortable2" ).sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        } );







        $(".sortable2").droppable({
            drop: function() {

                $questionId = $(this).attr('question-id');
                //alert($questionId);


                setTimeout(function(){
                    $i = 1;
                    $('.sortable2[question-id = '+ $questionId +'] li span').each(function () {
                        //$(this).html($i++ );
                    });
                }, 1);
                setTimeout(function(){
                    $i = 1;
                    $('.sortable2[question-id = '+ $questionId +'] li input').each(function () {
                        $(this).val($i++);
                    });
                }, 1);
            }
        });



        $(".sortable1").droppable({
            drop: function() {
                setTimeout(function(){
                    $('.sortable1 li span').each(function (i) {
                        var humanNum = i + 1;
                        $(this).html('');
                    });
                }, 1);
                setTimeout(function(){
                    $('.sortable1 li input').each(function (i) {
                        var humanNum = i + 1;
                        $(this).val('');
                    });
                }, 1);
            }
        });
    </script>
@endsection


