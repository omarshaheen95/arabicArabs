{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<html lang="en" dir="ltr">
<head>
    <link rel="stylesheet" href="{{asset('certification/css/bootstrap-5.0.2/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('certification/css/style.css')}}">
    <title>Certificate Awarded to {{$certificate->user->name}}</title>
    <link rel="shortcut icon" href="{{asset('web_assets/img/logo.svg')}}" type="image/x-icon">
</head>
<body class="no-page-break">
@if($certificate->model_type == \App\Models\Lesson::class)
    <div class="page no-page-break position-relative p-0 d-flex justify-content-center">
        <div class="position-absolute">
            <img src="{{asset('certification/img/border.svg')}}" style="width: 296mm; min-height: 209mm;">
        </div>
        <div class="subpage no-page-break">
            <div class="row mb-5">
                <img src="{{asset('certification/img/header.svg')}}">
            </div>
            <div class="d-flex flex-column align-items-center gap-1" style="padding-top: 40px">
                <div class="d-flex justify-content-center mb-2 gap-1">
                    <h5 class="p-text"> This Certificate Awarded to </h5>
                    <h5 class="p-text"> : </h5>
                    <h5 class="p-s-text">{{$certificate->user->name}}</h5>
                </div>

                <div class="d-flex justify-content-center gap-1 text-center">
                    <h6 class="s-text">In appreciation of the improvement of learning Arabic For the Lesson : <br>
                        <span class="s-s-text" dir="rtl">{{$certificate->model->name}}</span> </h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text">in the {{$certificate->model->grade->name}}</h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text"></h6>
                </div>
                <div class="d-flex justify-content-center">
                    <h6 class="s-text">During the journey of learning Arabic from the {{config('app.name')}}.</h6>
                </div>
                <div class="d-flex flex-column align-items-center w-100" style="padding-top: 20px">
                    <img src="{{asset('certification/img/signature.svg')}}" style="width:80%;margin-bottom: 20px">
                    <img src="{{asset('certification/img/logos_group.svg')}}" style="width: 100%;">
                </div>
            </div>

        </div>
    </div>
@else
    <div class="page no-page-break position-relative p-0 d-flex justify-content-center">
        <div class="position-absolute">
            <img src="{{asset('certification/img/border.svg')}}" style="width: 296mm; min-height: 209mm;">
        </div>
        <div class="subpage no-page-break">
            <div class="row mb-5">
                <img src="{{asset('certification/img/header.svg')}}">
            </div>
            <div class="d-flex flex-column align-items-center gap-1" style="padding-top: 40px">
                <div class="d-flex justify-content-center mb-2 gap-1">
                    <h5 class="p-text"> This Certificate Awarded to </h5>
                    <h5 class="p-text"> : </h5>
                    <h5 class="p-s-text">{{$certificate->user->name}}</h5>
                </div>

                <div class="d-flex justify-content-center gap-1 text-center">
                    <h6 class="s-text">In appreciation of the improvement of learning Arabic For the Story : <br>
                        <span class="s-s-text" dir="rtl">{{$certificate->model->name}}</span> </h6>
                    </h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text">in the {{$certificate->model->grade_name}}</h6>
                </div>
                <div class="d-flex justify-content-center gap-1">
                    <h6 class="s-text"></h6>
                </div>
                <div class="d-flex justify-content-center">
                    <h6 class="s-text">During the journey of learning Arabic from the {{config('app.name')}}.</h6>
                </div>
{{--                {!! QrCode::color(30, 67, 151)->size(80)->generate(sysDomain()) !!}--}}
                <div class="d-flex flex-column align-items-center w-100" style="padding-top: 20px">
                    <img src="{{asset('certification/img/signature.svg')}}" style="width:80%;margin-bottom: 20px">
                    <img src="{{asset('certification/img/logos_group.svg')}}" style="width: 100%;">
                </div>
            </div>

        </div>
    </div>

@endif
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>
</body>
</html>
