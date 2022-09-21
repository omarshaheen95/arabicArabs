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
                            <a class="breadcrumb-item" href="/home"> Home </a>
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
                                    <td style="font-weight: bold">Story name</td>
                                    <td style="font-weight: bold">Grade</td>
                                    <td style="font-weight: bold">Test Assignment</td>
                                    <td style="font-weight: bold">Status</td>
                                </tr>
                                @foreach($student_assignments as $student_assignment)
                                    <tr>
                                        <td>{{$student_assignment->story->translate('ar')->name}}
                                            - {{$student_assignment->story->translate('en')->name}}</td>
                                        <td>{{$student_assignment->story->grade}}</td>
                                        <td>@if($student_assignment->done_test_assignment)
                                                Completed
                                            @elseif($student_assignment->test_assignment != 0)
                                                <a href="{{route('lesson', [$student_assignment->lesson_id, 'test'])}}">Go
                                                    to test</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($student_assignment->completed)
                                                Completed Assignment
                                            @else
                                                UnCompleted Assignment
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
