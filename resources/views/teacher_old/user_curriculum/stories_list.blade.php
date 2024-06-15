@extends('teacher.user_curriculum.layout.container_v2')
@section('style')

@endsection
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3 class="title"> المستوى - {{$grade}} </h3>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('teacher.levels.stories', $grade)}}"> المستويات </a>
                            <span class="breadcrumb-item active" aria-current="page">القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach($stories as $story)
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="lesson-box">
                            <div class="pic">
                                <img src="{{$story->image}}" alt="">
                            </div>
                            <div class="content">
                                <div class="title"> {{$story->name}} </div>

                                <a href="{{route('teacher.stories.show', [$story->id, 'watch', $grade])}}"
                                   class="btn  btn-theme w-75 mb-4">
                                    استمع وشاهد
                                </a>
                                <div class="option">
                                    <a href="{{route('teacher.stories.show', [$story->id, 'test', $grade])}}"
                                       class="btn btn-soft-danger"> اختبر نفسك </a>
                                    <a href="{{route('teacher.stories.show', [$story->id, 'read', $grade])}}"
                                       class="btn btn-soft-info"> اقرأ القصة </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
