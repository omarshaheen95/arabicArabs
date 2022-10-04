{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('teacher.user_curriculum.layout.container_v2')
@section('style')
    <link href="{{asset('s_website/css/animate_cards.css')}}" rel="stylesheet">
@endsection
@section('content')
    <section class="login-home pt-5 user-home ">
        <div class="container">
                <h2>
                    <span style="font-weight: bold; color: red">الصف {{$grade_steps->grade_name}}:</span>
                </h2>
                <div class="row justify-content-center">
                    @if(isset($grade_steps) && $grade_steps->reading)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/reading.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة القراءة</div>
                                    <a href="{{route('teacher.lessons', [$grade_steps->id, 'reading'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($grade_steps) && $grade_steps->writing)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/writing.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الكتابة</div>
                                    <a href="{{route('teacher.lessons', [$grade_steps->id, 'writing'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($grade_steps) && $grade_steps->listening)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/listening.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الاستماع</div>
                                    <a href="{{route('teacher.lessons', [$grade_steps->id, 'listening'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($grade_steps) && $grade_steps->speaking)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{asset('steps/speaking.png')}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة التحدث</div>
                                    <a href="{{route('teacher.lessons', [$grade_steps->id, 'speaking'])}}"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
        </div>
    </section>
@endsection