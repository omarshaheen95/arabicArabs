@extends('general.user.test_preview.layout')

@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h1 class="title"> {{$story->name}} </h1>
                        <h1 class="title"><p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p></h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('stories.list', $story->grade)}}"> Stories </a>
                            <span class="breadcrumb-item active" aria-current="page"> Test </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form action="{{route('lesson_test', $story->id)}}" id="term_form" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">
                            <div class="justify-content-between align-items-center mb-4">

{{--                                @if(!is_null($level))--}}
{{--                                    <div class="exercise-box-header text-center text-danger" style="font-size: 18px;font-weight: 700;">--}}
{{--                                        <span class="title">{{$level->level_note}}  </span>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

                            </div>

                            <div class="question-list">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach($questions as $question)
                                    @if($question->type == 1)
                                        @include('general.user.test_preview.questions.true_false')
                                    @elseif($question->type == 2)
                                        @include('general.user.test_preview.questions.options')
                                    @elseif($question->type == 3)
                                        @include('general.user.test_preview.questions.match')
                                    @elseif($question->type == 4)
                                        @include('general.user.test_preview.questions.sort')
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
                                                                        style="font-size: 18px">  Back </span>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-theme"
                                            id="nextQuestion">
                                        <span class="txt" style="font-size: 18px"> Next  </span></button>
                                </div>
                            </div>


                            <div class="table-footer font-weight-bold">
                                <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                </ul>
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

    <script>
        $(document).ready(function () {
            // $('#confirmed_modal').click(function (e) {
            //     $("#endExam").modal("show");
            // });
            // $('#save_assessment').click(function (e) {
            //     e.preventDefault();
            //     $(this).attr('disabled', true);
            //     $('#confirmed_modal').attr('disabled', true);
            //     $('#term_form').submit();
            // })
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

        // Date.prototype.addHours = function (h) {
        //     this.setTime(this.getTime() + (h * 60 * 60 * 1000));
        //     return this;
        // }
        // // Set the date we're counting down to
        // var countDownDate = new Date().addHours(0.1666666).getTime();
        //
        // // Update the count down every 1 second
        // var x = setInterval(function () {
        //
        //     // Get today's date and time
        //     var now = new Date().getTime();
        //
        //     // Find the distance between now and the count down date
        //     var distance = countDownDate - now;
        //
        //     // Time calculations for days, hours, minutes and seconds
        //     var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        //     var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        //     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //     var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        //
        //     // Display the result in the element with id="countdown"
        //     document.getElementById("countdown").innerHTML = minutes + ":" + seconds;
        //     // console.log(distance);
        //     // If the count down is finished, write some text
        //     if (distance < 0) {
        //         clearInterval(x);
        //         $('term_form').submit();
        //         document.getElementById("countdown").innerHTML = "EXPIRED";
        //     }
        // }, 1000);


        $(function () {
            $(".sortable1, .sortable2").sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        });


        $(".sortable2").droppable({
            drop: function () {

                $questionId = $(this).attr('question-id');
                //alert($questionId);


                setTimeout(function () {
                    $i = 1;
                    $('.sortable2[question-id = ' + $questionId + '] li span').each(function () {
                        //$(this).html($i++ );
                    });
                }, 1);
                setTimeout(function () {
                    $i = 1;
                    $('.sortable2[question-id = ' + $questionId + '] li input').each(function () {
                        $(this).val($i++);
                    });
                }, 1);
            }
        });


        $(".sortable1").droppable({
            drop: function () {
                setTimeout(function () {
                    $('.sortable1 li span').each(function (i) {
                        var humanNum = i + 1;
                        $(this).html('');
                    });
                }, 1);
                setTimeout(function () {
                    $('.sortable1 li input').each(function (i) {
                        var humanNum = i + 1;
                        $(this).val('');
                    });
                }, 1);
            }
        });
    </script>
@endsection


