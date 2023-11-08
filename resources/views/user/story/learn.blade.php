@extends('user.layout.container_v2')
@section('style')
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('stories.list', $story->grade) }}">القصص</a>
    </li>
@endpush
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> {{$story->name}}</h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('stories.list', $story->grade)}}"> المهارات والدروس </a>
                            <span class="breadcrumb-item active" aria-current="page">القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row" dir="rtl">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{route('stories.list', $story->grade)}}" class=" btn btn-theme px-5">
                        <i class="fa-solid fa-arrow-right"></i>
                        القصص
                    </a>
                    {{--                            @if(!is_null($level))--}}
                    {{--                                <div class="exercise-box-header text-center text-danger" style="font-size: 18px;font-weight: 700;">--}}
                    {{--                                    <span class="title">{{$level->level_note}}  </span>--}}
                    {{--                                </div>--}}
                    {{--                            @endif--}}
                    <a href="{{route('stories.show', [$story->id,'read'])}}" class=" btn btn-theme px-5">
                        التدريب
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <div class="card-body pb-0">
                    <div id="video{{$story->id}}" class="mb-4"></div>
                </div>
                @if(!is_null($story->alternative_video))
                <div class="card-body pb-0 pt-4">
                    <div id="alternative_video{{$story->id}}" class="mb-4"></div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{asset('s_website/js/playerjs.js')}}"></script>

    <script>
        var player_{{$story->id}} = new Playerjs({
            id: "video{{$story->id}}",
            file: '{{asset($story->video)}}',
        });
        @if(!is_null($story->alternative_video))
        var player_alternative_video_{{$story->id}} = new Playerjs({
            id: "alternative_video{{$story->id}}",
            file: '{{asset($story->alternative_video)}}',
        });
        @endif
    </script>
@endsection
