{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')
    <style>
        .table-bordered{
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
                        نتائج الاختبارات  - Tests results
                    </h4>
                </div>
                <div class="card-body pb-0">

                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center justify-content-center">
                            <table class="table table-bordered w-100 text-center justify-content-center">
                                <thead class="table-danger">
                                    <td style="font-weight: bold">Lesson name</td>
                                    <td style="font-weight: bold">Level</td>
                                    <td style="font-weight: bold">Grade</td>
                                    <td style="font-weight: bold">Score</td>
                                    <td style="font-weight: bold">Certificates</td>
                                </thead>
                                <tbody>
                                    @foreach($student_tests as $student_test)
                                        @if($student_test->total >= $student_test->lesson->level->level_mark)
                                            <tr>
                                                <td>{{$student_test->lesson->translate('ar')->name}} - {{$student_test->lesson->translate('en')->name}}</td>
                                                <td>{{$student_test->lesson->level->translate('en')->name}}</td>
                                                <td>{{$student_test->lesson->level->grade}}</td>
                                                <td>{{$student_test->total_per}}</td>
                                                <td>
                                                    <a href="{{route('certificate', $student_test->id)}}">{{t('Preview')}}</a> -
                                                    <a href="{{route('certificate.answers', $student_test->id)}}">{{t('Answers')}}</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        {!! $student_tests->links() !!}
                    </div>

                </div>
            </div>
        </section>
    </section>
@endsection
