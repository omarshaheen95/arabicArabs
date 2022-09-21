
@extends('user.layout.container')
@section('style')
    <style>
        text{
            font-size: 14px;
        }
    </style>
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('stories.list', $story->grade) }}" >{{ t('Stories') }}</a>
    </li>
@endpush
@section('content')
    <section class="inner-page">
        <section>
            <div class="container text-right">
                <div class="row">
                    <div class="col-12">

                        <form action="{{route('story_test', $story->id)}}" id="term_form" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">

                            <div class="box-shado-question">
                                <div class="bg-light text-secondary font-weight-bold py-3 px-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mob-text-center" style="font-size: 20px;color: #000;"> {{$story->translate('ar')->name}} - {{$story->translate('en')->name}}</div>
                                        <div class="col-md-4 mob-text-center text-center"><p id="countdown" class="mb-0 text-danger"></p></div>
                                        <div class="col-md-4 mob-text-center text-left" style="color: #000;">
                                            {{w('Question')}}
                                            <span id="questionNumber"></span>
                                            {{w('from')}}
                                            <span id="numberQuestions"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-50" style="border: 3px #067af1 solid;">
                                    <div class="question-list">
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach($questions as $question)
                                                @if($question->type == 1)
                                                    <div class="question-item {{$loop->first ? 'active':''}}" id="{{$counter}}">
                                                        <h3 class="text-danger"> أكد إذا كانت هذه الجملة صواب أم خطأ - Confirm whether these sentence are true or false</h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  {{$question->content}} </h2>
                                                                </div>
                                                            </div>
                                                            @if(!is_null($question->attachment))
                                                                <div class="col-4">
                                                                    @if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                        <div class="w-100 text-center">
                                                                            <img src="{{asset($question->attachment)}}" width="300px">
                                                                        </div>
                                                                    @else
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="{{$question->id}}" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio{{$question->id}}" src="{{asset($question->attachment)}}"></audio>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row text-center justify-content-center">
                                                                <div class="col-md-3"><label class="mb-0 d-block py-3 fs-20"><input type="radio" question-grop-name="q{{$counter}}" name="tf[{{$question->id}}]" value="1"> <i class="fa fa-check-circle fa-fw text-success fa-2x"></i></label></div>
                                                                <div class="col-md-3"><label class="mb-0 d-block py-3 fs-20"><input type="radio" question-grop-name="q{{$counter}}" name="tf[{{$question->id}}]" value="0"> <i class="fa fa-times-circle fa-fw text-danger fa-2x"></i></label></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($question->type == 2)
                                                    <div class="question-item {{$loop->first ? 'active':''}}" id="{{$counter}}">
                                                        <h3 class="text-danger"> اختر الإجابة الصحيحة - Choose the correct answer</h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  {{$question->content}} </h2>
                                                                </div>
                                                            </div>
                                                            @if(!is_null($question->attachment))
                                                                <div class="col-4">
                                                                    @if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                        <div class="w-100 text-center">
                                                                            <img src="{{asset($question->attachment)}}" width="300px">
                                                                        </div>
                                                                    @else
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="{{$question->id}}" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio{{$question->id}}" src="{{asset($question->attachment)}}"></audio>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row text-center  justify-content-center">
                                                                @foreach($question->options as $option)
                                                                    <div class="col-md-3"><label class="mb-0 d-block py-3"><input type="radio" question-grop-name="q{{$counter}}" name="option[{{$question->id}}]" value="{{$option->id}}"> {{$option->content}} </label></div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($question->type == 3)
                                                    <div class="question-item {{$loop->first ? 'active':''}}" id="{{$counter}}">
                                                        <h3 class="text-danger"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل – Drag the answers in the right places below </h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  {{$question->content}} </h2>
                                                                </div>
                                                            </div>
                                                            @if(!is_null($question->attachment))
                                                                <div class="col-4">
                                                                    @if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                        <div class="w-100 text-center">
                                                                            <img src="{{asset($question->attachment)}}" width="300px">
                                                                        </div>
                                                                    @else
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="{{$question->id}}" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio{{$question->id}}" src="{{asset($question->attachment)}}"></audio>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row justify-content-center">

                                                                <div class="col-md-12">
                                                                    <ul id="" class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                        @foreach($question->matches()->inRandomOrder()->get() as $match)
                                                                            <li class="ui-state-default mb-2" data-id="{{$match->id}}">
                                                                                <div>
                                                                                    <text>{{$match->result}} </text>
                                                                                    <span class="float-right"></span>
                                                                                    <input type="hidden" name="re[{{$question->id}}][{{$match->id}}]" id="" value="" >
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
                                                                                                <span class="ml-3"></span>

                                                                                                <text>{{$match->content}}</text>
                                                                                            @endif
                                                                                        </li>
                                                                                        @php
                                                                                            $n_counter ++;
                                                                                        @endphp
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            <div class="col-lg-4">
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
                                                                                    <ul id="" question-id="{{$counter}}" class="sortable2 connectedSortable list-unstyled m-0 font-bold active {{$styleClass}} text-center m-0 p-0">
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($question->type == 4)
                                                    <div class="question-item {{$loop->first ? 'active':''}}" id="{{$counter}}">
                                                        <h3 class="text-danger"> اسحب ورتب الإجابات في المكان أدناه – Drag and order the answers in the below box </h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  {{$question->content}} </h2>
                                                                </div>
                                                            </div>
                                                            @if(!is_null($question->attachment))
                                                                <div class="col-4">
                                                                    @if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                                                        <div class="w-100 text-center">
                                                                            <img src="{{asset($question->attachment)}}" width="300px">
                                                                        </div>
                                                                    @else
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="{{$question->id}}" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio{{$question->id}}" src="{{asset($question->attachment)}}"></audio>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row justify-content-center">

                                                                <div class="col-md-12">
                                                                    <ul id="" class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                        @foreach($question->sort_words()->inRandomOrder()->get() as $word)
                                                                            <li class="ui-state-default mb-2" data-id="{{$word->id}}">
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
                                                                    <div class="ewewe" style="border: #0043b3 solid 2px !important; box-shadow: none;">
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
                                                @endif
                                            @php
                                                $counter ++;
                                            @endphp
                                        @endforeach
                                    </div>
                                    <div class="d-flex justify-content-center question-control">
                                        <div class="text-center"><button type="button" class="theme-btn btn-style-one mx-4 d-none" id="previousQuestion"><i class="far fa-chevron-right position-relative ml-3" style="z-index: 9;"></i><span class="txt" style="font-size: 18px">  {{w('Previous question')}} </span></button></div>
                                        <div class="text-center"><button type="button" class="theme-btn btn-style-one mx-4 d-none endExam" id="confirmed_modal" data-toggle="modal" data-target="#endExam"><span class="txt" style="font-size: 18px">{{w('Save the assessment')}}</span></button></div>
                                        <div class="text-center"><button type="button" class="theme-btn btn-style-one mx-4" id="nextQuestion"><span class="txt" style="font-size: 18px"> {{w('Next question')}}  </span> <i class="far fa-chevron-left position-relative mr-3" style="z-index: 9;"></i> </button></div>
                                    </div>
                                </div>
                                <div class="bg-light text-secondary font-weight-bold py-3 px-4">
                                    <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                    </ul>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="endExam" tabindex="-1" aria-labelledby="endExamLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body text-center py-5"> <h2 class="mb-0" style="direction: ltr">{{w('You are going to submitting your assessment,  are you sure ?')}} </h2></div>
                                        <div class="modal-footer border-0 justify-content-center">
                                            <button type="submit" class="theme-btn btn-style-one btnSecondary" id="save_assessment"><span class="txt"> {{w('Yes submit my assessment')}}</span></button>
                                            <button type="button" class="theme-btn btn-style-one btnSuccess" data-dismiss="modal"><span class="txt"> {{w('No i want to continue answering')}} </span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </section>

@endsection
@section('script')
    <script>

    </script>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('s_website/js/jquery.ui.touch-punch.js')}}"></script>

    <script>
        $(document).ready(function (){
            $('#save_assessment').click(function (e){
                e.preventDefault();
                $(this).attr('disabled', true);
                $('#confirmed_modal').attr('disabled', true);
                $('#term_form').submit();
            })
            $('.audio').click(function(){
                var elem = $(this);
                var data_id = $(this).attr('data-id');
                $('audio').each(function(){
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

        Date.prototype.addHours = function(h) {
            this.setTime(this.getTime() + (h*60*60*1000));
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


