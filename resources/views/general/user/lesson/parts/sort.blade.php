<div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> {{$counter}} : </span>
        <span class="title"> اسحب ورتب الإجابات في
                                        المكان أدناه </span>
    </div>
    <div class="exercise-box-body">
        <div class="exercise-question">
            <div class="exercise-question-data border-0">
                <div class="info">
                    {{$question->content}}
                </div>
                <div class="exercise-question-answer text-center my-4">
                    @if($question->getFirstMediaUrl($file_name))

                        <div class="row justify-content-center py-3">
                            <div class="col-lg-6 col-md-8">
                                @if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl($file_name), '.mp3'))
                                    <div class="recorder-player"
                                         id="voice_audio_2">
                                        <div class="audio-player">
                                            <audio >
                                                <source
                                                    src="{{asset($question->getFirstMediaUrl($file_name))}}"
                                                    type="audio/mpeg">
                                            </audio>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-100 text-center">
                                        <img
                                            src="{{asset($question->getFirstMediaUrl($file_name))}}"
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
                                    @if(!isset($preview))
                                        @foreach($question->sortWords as $word)
                                            <li class="ui-state-default mb-2"
                                                data-id="{{$word->id}}">
                                                <div>
                                                    <text>{{$word->content}} </text>
                                                    <span
                                                        class="float-right"></span>
                                                    <input type="hidden"
                                                           name="sort[{{$question->id}}][{{$word->id}}]"
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
                                        <div class="col-lg-12">
                                            <div class="position-relative">
                                                <ul class="m-0 p-0 list-unstyled add-ansar  position-absolute w-100">

                                                </ul>
                                                <ul id=""
                                                    question-id="{{$counter}}"
                                                    class="sortable2 sort_words connectedSortable list-unstyled m-0 font-bold active text-center m-0 p-0">
                                                    @if(isset($preview))
                                                        @foreach($question->sortWords as $word)
                                                            <li class="ui-state-default mb-2"
                                                                data-id="{{$word->id}}">
                                                                <div>
                                                                    <text>{{$word->content}} </text>
                                                                    <span
                                                                        class="float-right"></span>
                                                                    <input type="hidden"
                                                                           name="sort[{{$question->id}}][{{$word->id}}]"
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
