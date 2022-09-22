{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<html lang="ar" dir="rtl">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester');

        .cursive {
            font-family: 'Pinyon Script', cursive;
        }

        .sans {
            font-family: 'Open Sans', sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        .block {
            display: block;
        }

        .underline {
            border-bottom: 1px solid #777;
            padding: 5px;
            margin-bottom: 15px;
        }

        .margin-0 {
            margin: 0;
        }

        .padding-0 {
            padding: 0;
        }

        .pm-empty-space {
            height: 40px;
            width: 100%;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Open Sans', sans-serif", sans-serif !important;
            direction: rtl !important;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 297mm;
            min-height: 210mm;
            padding: 20mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            background-image: url("{{asset('s_website/img/pattern.png')}}");
            padding: 1cm;
            border: 5px #cecece solid;
            width: 257mm;
            height: 189mm;
            outline: 2cm #618597 solid;
        }
        .subpage h2{
            font-size: 54px !important;
            font-family: "Open Sans', sans-serif", sans-serif !important;
            color: #c8ae17 !important;
        }
        .subpage h3{
            font-size: 34px !important;
            font-family: "Open Sans', sans-serif", sans-serif !important;
        }

        .subpage .content{
            font-size: 18px !important;
            line-height: 2
        }

        @page {
            size: landscape;
            margin: 0;
        }

        @media print {
            html, body {
                width: 297mm;
                height: 210mm;
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

    </style>
</head>
<body>
<div class="book">
    <div class="page">
        <div class="subpage">
            <div class="row mb-5">
                <div class="cursive col-md-12 text-center">
                    <h2 class="bold">شهادة إنجاز</h2>
                </div>
            </div>
            <br />
            <div class="row mt-2">
                <div class="col-md-12 text-center block bold cursive mb-4">
                    <h3 class="bold">تم منح هذه الشهادة لـ :  <span class="underline" style="color: #FF0000;">{{$student_test->user->name}}.</span></h3>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-center pm-credits-text block bold sans content" style="font-size: 25px !important;">
                    تقديرًا لاجتياز تقييم الدرس

                    @if($student_test->lesson->lesson_type == 'reading' || $student_test->lesson->lesson_type == 'listening' )
                        {{$student_test->lesson->section_type_name}}
                    @endif
                    <span class="underline" style="color: #FF0000">{{$student_test->lesson->name}}</span>

                    <br />
                    مهارة {{$student_test->lesson->type_name}} -
                     الصف {{$student_test->lesson->grade_name}}
                    <br />
                    بنسبة  {{$student_test->total}}%
                    <br />
                    خلال رحلة تعلم اللغة العربية في منصة لغتي الأولى.
                </div>
            </div>
            <div class="row mt-5 text-center">
                <div class="col-md-5">
                    <img src="{{asset('s_website/img/stamp.png')}}" width="70%">
                </div>
                <div class="col-md-2">
                    <div class="mt-4 bold cursive content">
                        منحت بتاريخ :
                        <br />

                        {{$student_test->created_at->format('Y-m-d')}}
                    </div>
                </div>
                <div class="col-md-5 mt-4">
                    <img src="{{asset('web_assets/img/logo.svg')}}" width="100%">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
