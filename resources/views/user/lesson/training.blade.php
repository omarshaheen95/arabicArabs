{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')
    <style>
        text {
            font-size: 14px;
        }
    </style>
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('lessons', [$lesson->grade_id, $lesson->lesson_type]) }}">الدروس</a>
    </li>
@endpush
@section('content')
    <section class="inner-page">
        <section>
            <div class="container text-right">
                <div class="row">
                    <div class="col-12">
                        <div class="box-shado-question">
                            <div class="bg-light text-secondary font-weight-bold py-3 px-4">
                                <div class="row align-items-center">
                                    <div class="col-md-4"><a href="{{route('lesson', [$lesson->id,'learn'])}}"
                                                             class="btn btn-danger btn-lg"><i
                                                class="fa fa-arrow-right"></i> العودة للتدريب </a></div>
                                    <div class="col-md-4 text-center"><h3>{{$lesson->name}}</h3></div>
                                    <div class="col-md-4 text-left"><a href="{{route('lesson', [$lesson->id,'test'])}}"
                                                                       class="btn btn-info btn-lg">الذهاب للاختبار
                                            <i class="fa fa-arrow-left"></i></a></div>
                                </div>
                            </div>
                            <div class="p-50">
                                <div class="question-list">
                                    @php
                                        $counter = 1
                                    @endphp
                                    @foreach($tf_questions as $key => $tf_question)
                                        <div class="question-item @if($loop->first) active @endif" id="{{$counter}}">
                                            <h3> أكد إذا كانت هذه الجمل صواب أم خطأ</h3>
                                            <div class="row mt-4 justify-content-center">
                                                <div class="col-8">
                                                    <div class="w-100 text-center mt-4">
                                                        <h2 style="border: #0043b3 solid 2px !important;">  {{$tf_question->content}} </h2>
                                                    </div>
                                                </div>
                                                @if(!is_null($tf_question->attachment))
                                                    <div class="col-4">

                                                        @if(!\Illuminate\Support\Str::contains($tf_question->attachment, '.mp3'))
                                                            <div class="w-100 text-center">
                                                                <img src="{{asset($tf_question->attachment)}}"
                                                                     width="300px">
                                                            </div>
                                                        @else
                                                            <div class="w-100 text-center mt-4">
                                                                <button type="button" data-id="{{$tf_question->id}}"
                                                                        class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                    <i class="fa fa-play-circle fa-2x"></i>
                                                                </button>
                                                                <audio controls style="display:none"
                                                                       id="audio{{$tf_question->id}}"
                                                                       src="{{asset($tf_question->attachment)}}"></audio>
                                                            </div>
                                                        @endif

                                                    </div>
                                                @endif
                                            </div>


                                            <div class="answers">
                                                <div class="row text-center justify-content-center">
                                                    <div class="col-md-3"><label class="mb-0 d-block py-3 fs-20">
                                                            <input data-ans="{{$tf_question->trueFalse->result == 1 ? 'true':'false'}}"
                                                                type="radio" question-grop-name="q{{$counter}}"
                                                                name="tf[{{$tf_question->id}}]" value="1"> <i
                                                                class="tf_q fa fa-check-circle fa-fw text-success fa-2x"></i></label>
                                                    </div>
                                                    <div class="col-md-3"><label class="mb-0 d-block py-3 fs-20">
                                                            <input data-ans="{{$tf_question->trueFalse->result == 0 ? 'true':'false'}}"
                                                                type="radio" question-grop-name="q{{$counter}}"
                                                                name="tf[{{$tf_question->id}}]" value="0"> <i
                                                                class="tf_q fa fa-times-circle fa-fw text-danger fa-2x"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $counter ++
                                        @endphp
                                    @endforeach


                                    @foreach($c_questions as $key => $c_question)
                                        <div class="question-item" id="{{$counter}}">
                                            <h3> اختر الإجابة الصحيحة</h3>
                                            <div class="row mt-4 justify-content-center">
                                                <div class="col-8">
                                                    <div class="w-100 text-center mt-4">
                                                        <h2 style="border: #0043b3 solid 2px !important;">  {{$c_question->content}} </h2>
                                                    </div>
                                                </div>
                                                @if(!is_null($c_question->attachment))
                                                    <div class="col-4">

                                                        @if(!\Illuminate\Support\Str::contains($c_question->attachment, '.mp3'))
                                                            <div class="w-100 text-center">
                                                                <img src="{{asset($c_question->attachment)}}"
                                                                     width="300px">
                                                            </div>
                                                        @else
                                                            <div class="w-100 text-center mt-4">
                                                                <button type="button" data-id="{{$c_question->id}}"
                                                                        class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                    <i class="fa fa-play-circle fa-2x"></i>
                                                                </button>
                                                                <audio controls style="display:none"
                                                                       id="audio{{$c_question->id}}"
                                                                       src="{{asset($c_question->attachment)}}"></audio>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="answers">
                                                <div class="row text-center">
                                                    @foreach($c_question->options as $option)
                                                        <div class="col-md-3"><label class="mb-0 d-block py-3">
                                                                <input data-ans="{{$option->result == 1 ? 'true':'false'}}"
                                                                    type="radio" question-grop-name="q{{$counter}}"
                                                                    name="option[{{$c_question->id}}]"
                                                                    value="{{$option->id}}" class="co_q"> {{$option->content}} </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $counter ++
                                        @endphp
                                    @endforeach

                                    @foreach($m_questions as $key => $m_question)
                                        <div class="question-item" id="{{$counter}}">
                                            <h3> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل </h3>
                                            <div class="row mt-4 justify-content-center">
                                                <div class="col-8">
                                                    <div class="w-100 text-center mt-4">
                                                        <h2 style="border: #0043b3 solid 2px !important;">  {{$m_question->content}} </h2>
                                                    </div>
                                                </div>
                                                @if(!is_null($m_question->attachment))
                                                    <div class="col-4">
                                                        @if(!\Illuminate\Support\Str::contains($m_question->attachment, '.mp3'))
                                                            <div class="w-100 text-center">
                                                                <img src="{{asset($m_question->attachment)}}"
                                                                     width="300px">
                                                            </div>
                                                        @else
                                                            <div class="w-100 text-center mt-4">
                                                                <button type="button" data-id="{{$m_question->id}}"
                                                                        class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                    <i class="fa fa-play-circle fa-2x"></i>
                                                                </button>
                                                                <audio controls style="display:none"
                                                                       id="audio{{$m_question->id}}"
                                                                       src="{{asset($m_question->attachment)}}"></audio>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="answers">
                                                <div class="row justify-content-center">

                                                    <div class="col-md-12">
                                                        <ul id="match_{{$m_question->id}}_result"
                                                            class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                            @foreach($m_question->matches()->inRandomOrder()->get() as $match)
                                                                <li class="ui-state-default mb-2"
                                                                    data-id="{{$match->id}}">
                                                                    <div>
                                                                        <text>{{$match->result}} </text>
                                                                        <span class="float-right"></span>
                                                                        <input type="hidden"
                                                                               name="re[{{$m_question->id}}][{{$match->id}}]"
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
                                                                        @foreach($m_question->matches as $match)
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
                                                                <div class="col-lg-4 ">
                                                                    <div class="position-relative">
                                                                        <ul class="m-0 p-0 list-unstyled add-ansar position-absolute w-100">
                                                                            @foreach($m_question->matches as $match)
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
                                                                        <ul id="match_{{$m_question->id}}_answer" question-id="{{$counter}}"
                                                                            class="sortable2 connectedSortable list-unstyled m-0 font-bold {{$styleClass}} active text-center m-0 p-0">
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="button" data-id="{{$m_question->id}}" class="checkAnswer theme-btn btn-style-one mx-4"style="font-weight: bold;background-color: #dc3545;">
                                                    <span class="txt" style="font-size: 18px">  Check your answer </span>
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                            $counter ++
                                        @endphp
                                    @endforeach

                                    @foreach($s_questions as $key => $s_question)
                                        <div class="question-item" id="{{$counter}}">
                                            <h3> اسحب ورتب الإجابات في
                                                المكان أدناه </h3>
                                            <div class="row mt-4 justify-content-center">
                                                <div class="col-8">
                                                    <div class="w-100 text-center mt-4">
                                                        <h2 style="border: #0043b3 solid 2px !important;">  {{$s_question->content}} </h2>
                                                    </div>
                                                </div>
                                                @if(!is_null($s_question->attachment))
                                                    <div class="col-4">
                                                        @if(!\Illuminate\Support\Str::contains($s_question->attachment, '.mp3'))
                                                            <div class="w-100 text-center">
                                                                <img src="{{asset($s_question->attachment)}}"
                                                                     width="300px">
                                                            </div>
                                                        @else
                                                            <div class="w-100 text-center mt-4">
                                                                <button type="button" data-id="{{$s_question->id}}"
                                                                        class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                    <i class="fa fa-play-circle fa-2x"></i>
                                                                </button>
                                                                <audio controls style="display:none"
                                                                       id="audio{{$s_question->id}}"
                                                                       src="{{asset($s_question->attachment)}}"></audio>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="answers">
                                                <div class="row justify-content-center">

                                                    <div class="col-md-12">
                                                        <ul id="sort_{{$s_question->id}}_answer"
                                                            class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                            @foreach($s_question->sortWords()->inRandomOrder()->get() as $word)
                                                                <li class="ui-state-default mb-2"
                                                                    data-id="{{$word->id}}">
                                                                    <div>
                                                                        <text>{{$word->content}} </text>
                                                                        <span class="float-right"></span>
                                                                        <input type="hidden"
                                                                               name="re[{{$s_question->id}}][{{$word->id}}]"
                                                                               id="" value="">
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
                                                                        <ul id="sort_{{$s_question->id}}_result" question-id="{{$counter}}"
                                                                            class="sortable2 sort_words connectedSortable list-unstyled m-0 font-bold active text-center m-0 p-0">
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="button" data-id="{{$s_question->id}}" class="checkSort theme-btn btn-style-one mx-4"style="font-weight: bold;background-color: #dc3545;">
                                                    <span class="txt" style="font-size: 18px">  Check your answer </span>
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                            $counter ++
                                        @endphp
                                    @endforeach


                                </div>
                                <div class="d-flex justify-content-center question-control">
                                    <div class="text-center">
                                        <button type="button" class="theme-btn btn-style-one mx-4 d-none"
                                                style="font-weight: bold;background-color: #0043b3;"
                                                id="previousQuestion"><i
                                                class="far fa-chevron-right position-relative ml-3"
                                                style="z-index: 9;"></i><span class="txt"
                                                                              style="font-size: 18px">  Back </span>
                                        </button>
                                    </div>
                                    <div class="text-center"><a href="{{route('home')}}"
                                                                class="theme-btn btn-style-one mx-4 d-none endExam"
                                                                style="font-weight: bold;background-color: #0043b3;"><span
                                                class="txt" style="font-size: 18px">End</span></a></div>
                                    <div class="text-center">
                                        <button type="button" class="theme-btn btn-style-one mx-4"
                                                style="font-weight: bold;background-color: #0043b3;" id="nextQuestion">
                                            <span class="txt" style="font-size: 18px"> Next  </span> <i
                                                class="far fa-chevron-left position-relative mr-3"
                                                style="z-index: 9;"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-light text-secondary font-weight-bold py-3 px-4">
                                <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                </ul>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="endExam" tabindex="-1" aria-labelledby="endExamLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body text-center py-5"><h2 class="mb-0">Are you sure that you save
                                            your assessment? </h2></div>
                                    <div class="modal-footer border-0 justify-content-center">
                                        <button type="button" class="theme-btn btn-style-one btnSecondary"
                                                data-dismiss="modal"><span class="txt"> Continue the assessment</span>
                                        </button>
                                        <button href="submit" class="theme-btn btn-style-one btnSuccess"
                                                id="save_assessment"><span class="txt"> Save the assessment </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <audio controls style="display:none"
           id="false_audio"
           src="{{asset('website/false_result.mp3')}}"></audio>
    <audio controls style="display:none"
           id="ture_audio"
           src="{{asset('website/true_result.mp3')}}"></audio>
@endsection

@section('script')
    <script>

    </script>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('s_website/js/jquery.ui.touch-punch.js')}}"></script>

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

            $('.tf_q').click(function (){
                 var ans = $(this).attr('data-ans');
                 console.log(ans);
            });
            $('.co_q').click(function (){
                 var ans = $(this).attr('data-ans');
                 console.log(ans);
            });

            setTimeout(function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '{{route('track_lesson', [$lesson->id, 'practise'])}}';
                $.ajax({
                    url : url,
                    type: 'POST',
                    data : {
                        '_token': csrf,
                    },
                    success: function (data) {
                    },
                    error: function(errMsg) {
                    }
                });

            }, 10000);

        });
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
