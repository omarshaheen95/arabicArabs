{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')
    <link href="{{asset('s_website/css/animate_cards.css')}}" rel="stylesheet">
@endsection
@section('content')
    <section class="login-home pt-5 user-home ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('levels')}}"> المهارات والدروس </a>
                            <span class="breadcrumb-item active" aria-current="page">{{$title}} </span>
                        </nav>
                        <h3 class="title">{{$title}}</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach($levels as $level)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset("web_assets/img/levels/".$level["level"].".svg")}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> المستوى {{$level["level"]}}</div>
                                    <a href="{{route('sub_lessons', [$grade->id, $type, $level["level"]])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
