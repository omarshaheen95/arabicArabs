{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('teacher.curriculum.lesson_layout')
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('lessons', $lesson->level_id) }}" @if(isset($level) && !is_null($level->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold" @endif>{{ t('Lessons') }}</a>
    </li>
@endpush
@section('content')
    <section class="inner-page">
        <section>
            <div class="card mt-3 mb-4 border-0">
                <div class="card-header bg-white text-center">
                    <h4 style="font-weight: bold">نتيجة الاختبار  - Test result</h4>
                </div>
                <div class="card-body pb-0">

                    <div class="row justify-content-center">
                        <div class="col-md-12 justify-content-center text-center">
                        @if($student_test)
                            @if($student_test->total >= 40)
                                <img src="{{asset('s_website/img/good_icon.png')}}" width="15%">
                                <p class="text-center my-3 mb-4" dir="ltr" style="font-weight: bold">
                                    Congratulations,  Your result for this assessment is {{$student_test->total_per}} and you are eligible to get the certificate and play the game.
                                </p>
                                <p class="text-center my-3 mb-4" style="font-weight: bold">
                                    تهانينا ، نتيجتك في هذا التقييم هي  {{$student_test->total_per}}  وأنت مؤهل للحصول على الشهادة ولعب اللعبة.
                                </p>
                                <div class="text-center">
                                    <a href="{{route('certificate', $student_test->id)}}" target="_blank" class="theme-btn btn-style-one mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            الحصول على الشهادة  - Get certified
                                        </span>
                                    </a>
                                    <a href="{{route('home')}}" class="theme-btn btn-style-one mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            الرئيسة  - Home
                                        </span>
                                    </a>
                                </div>
                            @else
                                    <img src="{{asset('s_website/img/sad.png')}}" width="10%">
                                    <p class="text-center my-3 mb-4" dir="ltr" style="font-weight: bold">
                                        Your result for this assessment is {{$student_test->total_per}} and you are not eligible to get the certificate or play the game - please Re-learn this lesson again to reach 80% or above.                                    </p>
                                    <p class="text-center my-3 mb-4" style="font-weight: bold">
    نتيجتك في هذا التقييم هي {{$student_test->total_per}} ولست مؤهلاً للحصول على الشهادة أو لعب اللعبة - يرجى إعادة تعلم هذا الدرس مرة أخرى للوصول إلى 80٪ أو أعلى.                                    </p>
                                    <div class="text-center">

                                        <a href="{{route('lesson', [$student_test->lesson_id, 'test'])}}" class="theme-btn btn-style-one mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            اعادة الاختبار  - Re-test
                                        </span>
                                        </a>
                                    </div>
                            @endif


                            @else
                            <img src="{{asset('s_website/img/sad_icon.png')}}" width="10%">
                            <p class="text-center my-3 mb-4" style="font-weight: bold">
                                لا يوجد أي بيانات عن الاختبار المحدد  - There is no data for the specific test
                            </p>
                        @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </section>

@endsection
