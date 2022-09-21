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
                            <a class="breadcrumb-item" href="/home"> الرئيسية </a>
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
                                    <td style="font-weight: bold">الدرس</td>
                                    <td style="font-weight: bold">المستوى</td>
                                    <td style="font-weight: bold">تعيين مهمة</td>
                                    <td style="font-weight: bold">تعيين اختبار</td>
                                    <td style="font-weight: bold">الحالة</td>
                                </tr>
                                @foreach($student_assignments as $student_assignment)
                                    <tr>
                                        <td>{{$student_assignment->lesson->name}}</td>
                                        <td>{{$student_assignment->lesson->grade->grade_number}}</td>
                                        <td>@if($student_assignment->done_tasks_assignment)
                                                مكتمل
                                            @elseif($student_assignment->tasks_assignment != 0)
                                                <a href="{{route('lesson', [$student_assignment->lesson_id, 'learn'])}}#tasks">
                                                    الذهاب للمهمة</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>@if($student_assignment->done_test_assignment)
                                                مكتمل
                                            @elseif($student_assignment->test_assignment != 0)
                                                <a href="{{route('lesson', [$student_assignment->lesson_id, 'test'])}}">الضهاب للاختبار</a>
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
