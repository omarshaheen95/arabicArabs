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
            @if(isset($grades) && count($grades)>0)
               @foreach($grades as $grade)
                    <h2>
                        <span style="font-weight: bold; color: red">الصف {{$grade->grade_name}}:</span>
                    </h2>
                    <div class="row justify-content-center">
                        @if(isset($grade) && $grade->reading)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/reading.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة القراءة</div>
                                        <a href="{{route('lessons', [$grade->id, 'reading'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($grade) && $grade->writing)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/writing.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة الكتابة</div>
                                        <a href="{{route('lessons', [$grade->id, 'writing'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($grade) && $grade->listening)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/listening.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة الاستماع</div>
                                        <a href="{{route('lessons', [$grade->id, 'listening'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($grade) && $grade->speaking)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/speaking.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة التحدث</div>
                                        <a href="{{route('lessons', [$grade->id, 'speaking'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($grade) && $grade->grammar)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/grammar.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  القواعد النحوية</div>
                                        <a href="{{route('lessons_levels', [$grade->id, 'grammar'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($grade) && $grade->dictation)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/dictation.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  الإملاء</div>
                                        <a href="{{route('lessons_levels', [$grade->id, 'dictation'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($grade) && $grade->rhetoric)
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="{{asset('steps/rhetoric.svg')}}" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  البلاغة</div>
                                        <a href="{{route('lessons_levels', [$grade->id, 'rhetoric'])}}"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
               @endforeach
            @endif
            @if(isset($alternate_grades) &&  count($alternate_grades)>0)
                    @foreach($alternate_grades as $alternate_grade)
                        <h2>
                            <span style="font-weight: bold; color: red">الصف {{$alternate_grade->grade_name}}:</span>
                        </h2>
                        <div class="row justify-content-center">
                            @if(isset($alternate_grade) && $alternate_grade->reading)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/reading.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة القراءة</div>
                                            <a href="{{route('lessons', [$alternate_grade->id, 'reading'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($alternate_grade) && $alternate_grade->writing)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/writing.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة الكتابة</div>
                                            <a href="{{route('lessons', [$alternate_grade->id, 'writing'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($alternate_grade) && $alternate_grade->listening)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/listening.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة الاستماع</div>
                                            <a href="{{route('lessons', [$alternate_grade->id, 'listening'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($alternate_grade) && $alternate_grade->speaking)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/speaking.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة التحدث</div>
                                            <a href="{{route('lessons', [$alternate_grade->id, 'speaking'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($alternate_grade) && $alternate_grade->grammar)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/grammar.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> القواعد النحوية</div>
                                            <a href="{{route('lessons_levels', [$alternate_grade->id, 'grammar'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($alternate_grade) && $alternate_grade->dictation)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/dictation.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> الإملاء</div>
                                            <a href="{{route('lessons_levels', [$alternate_grade->id, 'dictation'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($alternate_grade) && $alternate_grade->rhetoric)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="{{asset('steps/rhetoric.svg')}}" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> البلاغة</div>
                                            <a href="{{route('lessons_levels', [$alternate_grade->id, 'rhetoric'])}}"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
            @endif
        </div>
    </section>
@endsection
