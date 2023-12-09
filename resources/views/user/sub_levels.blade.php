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
            <div class="row justify-content-center">
                @for($le = 1; $le <=12; $le++)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="lesson-box">
                            <div class="pic">
                                <img src="{{asset("web_assets/img/sub_levels/$le.svg")}}?v=2" alt="">
                            </div>
                            <div class="content">
                                <div class="title"> الصف {{$le}}</div>
                                <a href="{{route('sub_lessons', [$grade->id, $type, $le])}}"
                                   class="btn   btn-theme w-75 mb-4">
                                    دخول
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor

            </div>
        </div>
    </section>
@endsection
