{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')
    <link href="{{asset('s_website/css/bootstrap-rtl.css')}}" rel="stylesheet">
    <link href="{{asset('s_website/css/lesson_card.css')}}" rel="stylesheet">

@endsection
@section('content')

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="grade{{$grade->id}}" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                @foreach($lessons as $lesson)
                    @if(!$lesson->is_hidden)
                    <div class="col-lg-4 mt-4">
                        <div class="card hovercard @if($lesson->student_tested) overlay1 @endif">
                            @if($lesson->student_tested)
                            <span class="lesson_done">مكتمل - Done</span>
                            @endif
                            <div class="mb-4">
                                <img class="card-bkimg w-100" alt="" src="{{$lesson->image}}">
                            </div>
                            <div class="card-info mt-5" style="font-weight: bold">
                                <span class="card-title">{{$lesson->name}}</span>
                                <br />
                                <span class="card-title">{{$lesson->section_type_name ? "درس $lesson->section_type_name":null }}</span>
                            </div>

                        </div>
                        <div class="btn-group w-100" role="group" aria-label="Basic example">
{{--                            @if($lesson->student_tested)--}}
{{--                                <a href="{{route('lesson', [$lesson->id, 'play'])}}" type="button" class="btn btn-success" style="font-weight: bold">Play</a>--}}
{{--                            @else--}}
{{--                                <button disabled type="button" class="btn btn-success" style="font-weight: bold; font-size:14px">Play</button>--}}
{{--                            @endif--}}
                            <a href="{{route('lesson', [$lesson->id, 'learn'])}}" type="button" class="btn btn-warning" style="font-weight: bold; font-size:14px">تعلم</a>
                            @if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking')
                            <a href="{{route('lesson', [$lesson->id, 'training'])}}" type="button" class="btn btn-primary" style="font-weight: bold; font-size:14px">تدرب</a>
                            @endif
                            <a href="{{route('lesson', [$lesson->id, 'test'])}}" type="button" class="btn btn-danger" style="font-weight: bold; font-size:14px">اختبر نفسك</a>



                        </div>

                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
