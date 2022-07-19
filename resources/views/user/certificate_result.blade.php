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
                        إجابات الأسئلة  - Questions answers
                    </h4>
                </div>
                <div class="card-body pb-0">

                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center justify-content-center">
                            <table class="table table-bordered w-100 text-center justify-content-center">
                                <thead class="table-danger">
                                    <td style="font-weight: bold">Right Answer</td>
                                    <td style="font-weight: bold">Student Answer</td>
                                    <td style="font-weight: bold">Question Type</td>
                                    <td style="font-weight: bold">Question</td>
                                    <td style="font-weight: bold">#</td>
                                </thead>
                                <tbody>
                                    @foreach($questions as $key => $question)
                                            <tr class="text-center">
                                                @php
                                                    $data = $question->studentAnswer($student_test->id);
                                                @endphp

                                                <td>
                                                    {!! isset($data['question_answer']) ? $data['question_answer']:'' !!}
                                                </td>
                                                <td class="{{isset($data['class']) ? $data['class']:''}}">
                                                    {!! isset($data['student_answer']) ? $data['student_answer']:'' !!}
                                                </td>
                                                <td>{{$question->type_eng_name}}</td>
                                                <td>{{$question->content}}</td>
                                                <td>{{$key + 1}}</td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </section>
@endsection
