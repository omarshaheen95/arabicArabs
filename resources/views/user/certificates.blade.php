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
                        <h1 class="title"> الشهادات </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> الشهادات </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">
                        <div class="table-header">
                            <div class="title"> نتائج الاختبارات </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td style="font-weight: bold">الدرس</td>
                                    <td style="font-weight: bold">المستوى</td>
                                    <td style="font-weight: bold">الدرجة</td>
                                    <td style="font-weight: bold">استحقت في</td>
                                    <td style="font-weight: bold">الشهادة</td>
                                </tr>
                                @foreach($student_tests as $student_test)
                                        @if($student_test->total >= 50)
                                            <tr>
                                                <td>{{$student_test->lesson->name}}</td>
                                                <td>الصف {{$student_test->lesson->grade_name}}</td>
                                                <td>%{{$student_test->total}}</td>
                                                <td>{{$student_test->created_at->format('Y-m-d H:i')}}</td>
                                                <td>
                                                    <a href="{{route('certificate', $student_test->id)}}">معاينة</a> -
                                                    <a href="{{route('certificate.answers', $student_test->id)}}">الإجابات</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                            </table>
                        </div>

                        <div class="table-footer">
                            {!! $student_tests->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
