{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')
    <link href="{{asset('s_website/css/bootstrap-rtl.css')}}" rel="stylesheet">
    <link href="{{asset('s_website/css/lesson_card.css')}}" rel="stylesheet">
    <style>

        .lessonCover{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 360px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .lessonCover img{
            width: 100%;
            /* max-width: 100%; */
            /* height: 100%; */
            /* overflow: hidden; */
            /* margin-bottom: 1rem; */
            object-fit: cover;
        }
    </style>
@endsection
@section('content')

    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : {{$grade->grade_number}} </h3>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('levels')}}"> المستويات </a>
                            <span class="breadcrumb-item active" aria-current="page">الدروس </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach($lessons as $lesson)
                    @if(!$lesson->is_hidden)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="{{isset($lesson) ? $lesson->getFirstMediaUrl('imageLessons'):''}}" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> {{$lesson->name}} </div>
                                    <div class="title"> {{$lesson->section_type_name ? "درس $lesson->section_type_name":null }} </div>

                                    <div class="option">
                                        @if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking')
                                            <a href="{{route('lesson', [$lesson->id, 'learn'])}}"
                                               class="btn btn-soft-success"> تعلم </a>

                                        <a href="{{route('lesson', [$lesson->id, 'training'])}}"
                                           class="btn btn-soft-danger"> تدرب </a>
                                        @endif
                                        <a href="{{route('lesson', [$lesson->id, 'test'])}}"
                                           class="btn btn-soft-info">

                                            اختبر نفسك
                                            @if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking')
                                                @if($lesson->student_tested)- مكتمل   @endif
                                                @else
                                                @if($lesson->student_tested_task)- مكتمل   @endif
                                                @endif


                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection

