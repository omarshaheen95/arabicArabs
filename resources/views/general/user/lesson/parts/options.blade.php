@php
$answer = null;
if (isset($question)){
        if (isset($preview) && $question->options){
            $answer = $question->options->where('result',1)->first()->id;

        }elseif (isset($question->optionResults) && count($question->optionResults)>0){
                $answer = $question->optionResults->first()->option_id;
            }
}
 @endphp
<div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif  question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> {{$counter}} : </span>
        <span
            class="title"> اختر الإجابة الصحيحة - Choose the correct answer</span>
    </div>
    <div class="exercise-box-body">
        <div class="exercise-question">
            <div class="exercise-question-data border-0">
                <div class="info">
                    {{$question->content}}
                </div>
            </div>
            <div class="exercise-question-answer text-center my-4">

                @if($question->getFirstMediaUrl($file_name))

                    <div class="row justify-content-center py-3">
                        <div class="col-lg-6 col-md-8">
                            @if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl($file_name), '.mp3'))
                                <div class="recorder-player" id="voice_audio_2">
                                    <div class="audio-player">
                                        <audio crossorigin>
                                            <source
                                                src="{{asset($question->getFirstMediaUrl($file_name))}}"
                                                type="audio/mpeg">
                                        </audio>
                                    </div>
                                </div>
                            @else
                                <div class="w-100 text-center">
                                    <img src="{{asset($question->getFirstMediaUrl($file_name))}}"
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
                                       type="radio"
                                       question-grop-name="q{{$counter}}"
                                       name="option[{{$question->id}}]"
                                       value="{{$option->id}}"
                                       class="co_q d-none"
                                    {{$answer==$option->id?'checked':''}}>

                                <label for="option-{{$option->id}}"
                                       class="option option-true"
                                       @if($answer!=$option->id && $question->options->where('result',1)->first()->id==$option->id) style="background-color: #00b300" @endif
                                >
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
