{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('teacher.curriculum.lesson_layout')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/greghub/green-audio-player/dist/css/green-audio-player.min.css">
    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }
    </style>
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('lessons', $lesson->level_id) }}" @if(isset($level) && !is_null($level->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold" @endif>{{ t('Lessons') }}</a>
    </li>
@endpush
@section('content')
    <section class="inner-page">
        <section>
            <div class="container text-right">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4 border-0">
                            <div class="card-header bg-white  font-weight-bold">
                                <div class="row">
                                    <div class="col-md-4"><a href="{{route('teacher.home')}}" class="btn btn-lg btn-danger"><i class="fa fa-arrow-right"></i> Back to the lessons </a></div>
                                    <div class="col-md-4 text-center"><h3>{{$lesson->translate('ar')->name}} - {{$lesson->translate('en')->name}}</h3></div>
                                    <div class="col-md-4 text-left"><a href="{{route('teacher.curriculum.lesson', [$lesson->id, 'training'])}}" class="btn btn-lg btn-info">Go to practice <i class="fa fa-arrow-left"></i></a></div>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div class="card-header bg-white text-center font-weight-bold">
                                    <h5 class="w-100 text-center" style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;"> استمع إلى المفردات  وأعد قراءتها - Listen to the vocabularies and repeat the words</h5>
                                </div>
                                <div class="" style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px;">
                                @foreach($lesson->words as $key => $word)
                                    <div class="row px-4 my-3 font-weight-bold text-center">
                                        <div class="col-4 text-danger" style="align-items: center;justify-content:center;display: flex;">
                                            {{$word->translate('ar')->content}}
                                        </div>
                                        <div class="col-4 text-primary" style="align-items: center;justify-content:center;display: flex;">
                                            {{$word->translate('en')->content}}
                                        </div>
                                        <div class="col-4">

                                            <button type="button" data-id="{{$word->id}}" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                <i class="fa fa-play-circle fa-2x"></i>

                                            </button>
                                            <audio style="display:none" id="audio{{$word->id}}" data-id="{{$word->id}}" src="{{asset($word->file)}}"></audio>
                                            {{--                                            <div class="player" style="width: 100%; direction: ltr">--}}
                                            {{--                                               <audio crossorigin>--}}
                                            {{--                                                   <source src="{{asset($word->file)}}">--}}
                                            {{--                                               </audio>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <hr />
                                    @endif
                                @endforeach
                                </div>
                                @if(count($lesson->lesson_videos))
                                <hr />
                                    <h5 class="mt-4 w-100 text-center" style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;">
                                        انظر إلى الكلمات في الفيديو  وأعد قراءتها - Look at the words in the video and repeat the words
                                    </h5>
                                    <div class="row px-4 my-2 justify-content-center" style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px;">
                                        @foreach($lesson->lesson_videos as $lesson_video)
                                        <div class="col-10 text-center">
{{--                                            <h5>{{$lesson_video->title}}</h5>--}}
                                            <div id="video{{$lesson_video->id}}" class="mb-4"></div>
                                        </div>
                                    @endforeach
                                    </div>

                                @endif

                                <hr />
                                <h5 class="mt-3  w-100 mb-5 text-center" style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;">
                                    Read loudly أقرأ بصوتٍ عالٍ - Do you want to practice your reading? it’s your turn now
                                </h5>

                                <div class="row px-4 my-2" style="border: 2px solid #999;margin: 20px;border-radius: 20px;padding: 20px 10px;">
                                    <div class="col-md-12">
                                        {!! $lesson->content !!}
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
@section('script')
    <script src="{{asset('s_website/js/playerjs.js')}}"></script>

    <script>
        @foreach($lesson->lesson_videos as $lesson_video)
            var player_{{$lesson_video->id}} = new Playerjs({
                id:"video{{$lesson_video->id}}",
                file: '{{asset($lesson_video->video)}}',
            });
        @endforeach
        $(document).ready(function(){
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

    </script>
@endsection
