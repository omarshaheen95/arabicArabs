{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<html lang="en" dir="rtl">
<head>
    <link rel="stylesheet" href="{{asset('certification/css/bootstrap-5.0.2/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('certification/css/style.css')}}">
    <title>تم منح هذه الشهادة لـ {{$student_test->user->name}}</title>
    <link rel="shortcut icon" href="{{asset('web_assets/img/logo.svg')}}" type="image/x-icon">
</head>
<body class="no-page-break">
<div class="page no-page-break position-relative p-0 d-flex justify-content-center">
    <div class="position-absolute">
        <img src="{{asset('certification/img/border.svg')}}" style="width: 295mm; min-height: 207mm;">
    </div>
    <div class="subpage no-page-break">
        <div class="row mb-5">
            <img src="{{asset('certification/img/header.svg')}}">
        </div>
        <div class="d-flex flex-column align-items-center gap-1" style="padding-top: 40px">
            <div class="d-flex justify-content-center mb-2 gap-1">
                <h5 class="p-text"> تم منح هذه الشهادة لـ </h5>
                <h5 class="p-text"> : </h5>
                <h5 class="p-s-text">{{$student_test->user->name}}</h5>
            </div>

            <div class="d-flex justify-content-center gap-1 text-center">
                <h6 class="s-text"> تقديرًا لاجتياز تقييم الدرس : <br>
                    @if($student_test->lesson->lesson_type == 'reading' || $student_test->lesson->lesson_type == 'listening' )

                    <span class="s-s-text" dir="rtl">
                            {{$student_test->lesson->section_type_name}}

                    </span>
                    @endif
                    <span class="s-s-text" dir="rtl">{{$student_test->lesson->name}}</span></h6>
            </div>
            <div class="d-flex justify-content-center gap-1">
                <h6 class="s-text">{{$student_test->lesson->type_name}} -
                    الصف {{$student_test->lesson->grade_name}}</h6>
            </div>
            <div class="d-flex justify-content-center gap-1">
                <h6 class="s-text"> بنسبة {{$student_test->total}}%</h6>
            </div>
            <div class="d-flex justify-content-center">
                <h6 class="s-text">خلال رحلة تعلم اللغة العربية في منصة لغتي الأولى.</h6>
            </div>
            <div class="d-flex flex-column align-items-center w-100" style="padding-top: 20px">
                <img src="{{asset('certification/img/signature.svg')}}" style="width:80%;margin-bottom: 20px">
                <img src="{{asset('certification/img/logos_group.svg')}}" style="width: 100%;">
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>
</body>
</html>
