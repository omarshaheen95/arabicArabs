@extends('layouts.container_2')
@section('header')
    <header class="header">
        <div class="intro-box">
            <h1 class="title">منصة لغتي الأولى</h1>
            <p class="sub-title">منصة مهنية إثرائية لتطوير مهارات اللغة العربية كلغة أولى للطلاب العرب تشمل جميع المهارات. </p>
            <p class="description">تحتوي المنصة على تمارين تفاعلية متنوعة ذات جودة عالية تهدف إلى تطوير مهارات اللغة.</p>


            <div class="btns">
                <a href="/register" class="btn btn-warning">
                    {{--                    <img src="{{asset('web_assets/img/start-learn.svg')}}" alt="">--}}
                    <span>   ابدأ الأن </span>
                </a>
                {{--                <a href="https://www.youtube.com/embed/_K3C8YWTE9w" data-fancybox class="btn btn-warning">--}}
                {{--                    <img src="{{asset('web_assets/img/play-video.svg')}}" alt="">--}}
                {{--                    <span>  {{w('What we offer')}} </span>--}}
                {{--                </a>--}}
            </div>
        </div>
    </header>
@endsection

@section('content')

    <!-- Start price -->
    <section class="price-section" id="price">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <h2 class="title"> الاسعار </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="price-card">
                        @foreach($packages as $package)
                            <a href="/register?package_id={{$package->id}}" class="price-box">
                                <h3 class="title">{{$package->name}}</h3>
                                <h4 class="price">{{$package->price}}$</h4>
                                <div class="price-day">
                                    <div class="day" dir="rtl" style="direction:rtl !important">
                                        {{$package->days}}
                                        @if($package->days < 10)
                                            أيام
                                        @else
                                            يوماً
                                        @endif


                                    </div>
                                    <button type="button" class="btn btn-theme"
                                            href="/register?package_id={{$package->id}}"> اشتراك </button>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End price -->

@endsection

