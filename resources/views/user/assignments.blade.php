{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')
    <style>
        .table-bordered {
            border: 3px solid #dee2e6;
        }

    </style>
@endsection
@section('content')
    <section class="inner-page">
        <section>
            <div class="card mt-3 mb-4 border-0">
                <div class="card-header bg-white text-center">
                    <h4 style="font-weight: bold">
                        {{$title}}
                    </h4>
                </div>
                <div class="card-body pb-0">

                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center justify-content-center">
                            <table class="table table-bordered w-100 text-center justify-content-center">
                                <thead class="table-danger">
                                <td style="font-weight: bold">Lesson name</td>
                                <td style="font-weight: bold">Level</td>
                                <td style="font-weight: bold">Tasks Assignment</td>
                                <td style="font-weight: bold">Test Assignment</td>
                                <td style="font-weight: bold">Status</td>
                                </thead>
                                <tbody>
                                @foreach($student_assignments as $student_assignment)
                                    <tr>
                                        <td>{{$student_assignment->lesson->translate('ar')->name}}
                                            - {{$student_assignment->lesson->translate('en')->name}}</td>
                                        <td>{{$student_assignment->lesson->level->translate('en')->name}}</td>
                                        <td>@if($student_assignment->done_tasks_assignment)
                                                Completed
                                            @elseif($student_assignment->tasks_assignment != 0)
                                                <a href="{{route('lesson', [$student_assignment->lesson_id, 'learn'])}}#tasks">
                                                    Go to tasks</a>
                                            @else
                                                -
                                            @endif
                                        </td>
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
                                </tbody>
                            </table>

                        </div>
                        {!! $student_assignments->links() !!}
                    </div>

                </div>
            </div>
        </section>
    </section>
@endsection
