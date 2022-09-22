{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('teacher.layout.container')
@section('style')
    <link href="{{asset('s_website/css/animate_cards.css')}}" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
           {{ t('The curriculum') }}
        </li>
        <li class="breadcrumb-item">
            {{ t('Grade') . $grade }}
        </li>
    @endpush
    <div class="row">
        @foreach($levels as $level)
            <div class="col-lg-3 mt-4">
                <a href="{{route('teacher.curriculum.lessons', [$grade, $level->id])}}" class="url-box" >
                    <figure class='newsCard news-Slide-up '>
                        <img src="{{optional($level)->image ?? 'https://source.unsplash.com/1600x900/?background'}}"/>
                        <div class='newsCaption px-4' style="bottom: 40px">
                            <div class="d-flex align-items-center justify-content-center cnt-title text-center">
                                <h5 class='newsCaption-title m-0'>{{$level->translate('en')->name}}
                                </h5>
                            </div>
                            <div class='newsCaption-content justify-content-center d-flex text-center'>
                                <h5 class="col-10 py-3 px-0">{{$level->translate('ar')->name}}
                                </h5>
                            </div>
                        </div>
                        <span class="overlay"></span>
                    </figure>
                </a>
            </div> <!-- end card1-->
        @endforeach
    </div>

@endsection
