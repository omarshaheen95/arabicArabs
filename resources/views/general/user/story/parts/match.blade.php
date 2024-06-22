<div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> {{$counter}} : </span>
        <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل </span>
    </div>
    <div class="exercise-box-body">
        <div class="exercise-question">
            <div class="exercise-question-data border-0">
                <div class="info">
                    {{$question->content}}
                </div>
                <div class="exercise-question-answer text-center my-4">
                    @if($question->attchment)

                        <div class="row justify-content-center py-3">
                            <div class="col-lg-6 col-md-8">
                                @if(\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                                    <div class="recorder-player"
                                         id="voice_audio_2">
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
                                        <img
                                            src="{{asset($question->attachment)}}"
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
                                        @if(!isset($preview))

                                            @foreach($question->matches as $match)
                                                <li class="ui-state-default mb-2"
                                                    data-id="{{$match->id}}">
                                                    <div>
                                                        <text>{{$match->result}} </text>
                                                        <span
                                                            class="float-right"></span>
                                                        <input type="hidden"
                                                               name="re[{{$question->id}}][{{$match->id}}]"
                                                               id="" value="">
                                                    </div>
                                                </li>
                                            @endforeach

                                        @endif
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
                                                            @if($match->image)
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
                                                            @if(!$match->image)
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
                                                        @if(isset($preview))

                                                            @foreach($question->matches as $match)
                                                                <li class="ui-state-default mb-2"
                                                                    data-id="{{$match->id}}">
                                                                    <div>
                                                                        <text>{{$match->result}} </text>
                                                                        <span
                                                                            class="float-right"></span>
                                                                        <input type="hidden"
                                                                               name="re[{{$question->id}}][{{$match->id}}]"
                                                                               id="" value="">
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        @else
                                                        @endif

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
