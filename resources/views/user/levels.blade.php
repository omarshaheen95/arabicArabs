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
            @if(isset($grade) && !is_null($grade))
                <h2>
                    <span style="font-weight: bold; color: red">{{$grade->name}}:</span>
                </h2>
                <div class="row justify-content-center">
                    @if(isset($grade) && $grade->reading)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/reading.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة القراءة</div>
                                    <a href="{{route('lessons', [$grade->id, 'reading'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($grade) && $grade->writing)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/writing.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الكتابة</div>
                                    <a href="{{route('lessons', [$grade->id, 'writing'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($grade) && $grade->listening)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/listening.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الاستماع</div>
                                    <a href="{{route('lessons', [$grade->id, 'listening'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($grade) && $grade->speaking)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/speaking.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة التحدث</div>
                                    <a href="{{route('lessons', [$grade->id, 'speaking'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            @if(isset($alternate_grade) && !is_null($alternate_grade))
                <h2>
                    <span style="font-weight: bold; color: red">{{$alternate_grade->name}}:</span>
                </h2>
                <div class="row justify-content-center">
                    @if(isset($alternate_grade) && $alternate_grade->reading)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/reading.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة القراءة</div>
                                    <a href="{{route('lessons', [$alternate_grade->id, 'reading'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($alternate_grade) && $alternate_grade->writing)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/writing.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الكتابة</div>
                                    <a href="{{route('lessons', [$alternate_grade->id, 'writing'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($alternate_grade) && $alternate_grade->listening)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/listening.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الاستماع</div>
                                    <a href="{{route('lessons', [$alternate_grade->id, 'listening'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($alternate_grade) && $alternate_grade->speaking)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/speaking.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة التحدث</div>
                                    <a href="{{route('lessons', [$alternate_grade->id, 'speaking'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        ابدأ الأن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
