{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')

@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> {{$title}} </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> {{$title}} </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">

                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td style="font-weight: bold">القصة</td>
                                    <td style="font-weight: bold">المستوى</td>
                                    <td style="font-weight: bold">تعيين الاختبار</td>
                                    <td style="font-weight: bold">الحالة</td>
                                </tr>
                                @foreach($student_assignments as $student_assignment)
                                    <tr>
                                        <td>{{$student_assignment->story->name}}</td>
                                        <td>{{$student_assignment->story->grade}}</td>
                                        <td>@if($student_assignment->done_test_assignment)
                                                Completed
                                            @elseif($student_assignment->test_assignment != 0)
                                                <a href="{{route('stories.show', [$student_assignment->story_id, 'test'])}}">
                                                    الذهاب للاختبار
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($student_assignment->completed)
                                                مكتمل
                                            @else
                                                غير مكتمل
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="table-footer">
                            {!! $student_assignments->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
