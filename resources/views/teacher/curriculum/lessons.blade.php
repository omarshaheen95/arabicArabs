{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('teacher.layout.container')
@section('style')
    <link href="{{asset('s_website/css/lesson_card.css')}}" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        @push('breadcrumb')
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.curriculum.levels', $grade) }}">{{ t('Grade') .' '. $grade }}</a>
            </li>
            <li class="breadcrumb-item">
               {{ $level->name }}
            </li>
        @endpush
    @endpush

    <div class="row">
        @foreach($lessons as $lesson)
            <div class="col-lg-3 mt-4">
                <div class="card hovercard">
                    <div class="mb-4">
                        <img class="card-bkimg w-100" alt="" src="{{$lesson->image}}">
                    </div>
                    <div class="card-info mt-5" style="font-weight: bold">
                        <span class="card-title">{{$lesson->translate('ar')->name}}</span>
                        <br />
                        <span class="card-title">{{$lesson->translate('en')->name}}</span>
                    </div>

                </div>
                <div class="btn-group w-100" role="group" aria-label="Basic example">
                    @if($lesson->student_tested)
                        <a href="{{route('teacher.curriculum.lesson', [$lesson->id, 'play'])}}" type="button" class="btn btn-success" style="font-weight: bold">Play</a>
                    @else
                        <button disabled type="button" class="btn btn-success" style="font-weight: bold">Play</button>
                    @endif
                    <a href="{{route('teacher.curriculum.lesson', [$lesson->id, 'test'])}}" type="button" class="btn btn-danger" style="font-weight: bold">Assess</a>

                    <a href="{{route('teacher.curriculum.lesson', [$lesson->id, 'training'])}}" type="button" class="btn btn-primary" style="font-weight: bold">Practice</a>

                    <a href="{{route('teacher.curriculum.lesson', [$lesson->id, 'learn'])}}" type="button" class="btn btn-warning" style="font-weight: bold">Learn</a>

                </div>

            </div>
        @endforeach
    </div>
@endsection
