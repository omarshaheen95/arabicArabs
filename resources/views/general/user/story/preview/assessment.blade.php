{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('general.user.std_container')


@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : {{$story->grade}} </h3>
                        <h1 class="title"><p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p></h1>

                        <nav class="breadcrumb fw-bold">
                            {{$title}} : {{$story->name}}
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form id="term_form" method="post">

                            <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">

                            <div class="question-list">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach($questions as $question)
                                    @if($question->type == 1)
                                        @include('general.user.story.parts.true_false')
                                    @elseif($question->type == 2)
                                        @include('general.user.story.parts.options')
                                    @elseif($question->type == 3)
                                        @include('general.user.story.parts.match')
                                    @elseif($question->type == 4)
                                        @include('general.user.story.parts.sort')
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
                                    <button type="button" class="btn btn-theme"
                                            id="nextQuestion">
                                        <span class="txt" style="font-size: 18px"> التالي  </span></button>
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

