{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')
    <link href="{{asset('s_website/css/animate_cards.css')}}" rel="stylesheet">
@endsection
@section('content')
    <section class="main-inner-page">
        <section>
            @if(isset($grade) && !is_null($grade))
                <h2>
                    <span style="font-weight: bold; color: red">{{$grade->name}}
                    :
</span>
                </h2>
            @endif
            <div class="row justify-content-center">
                @if(isset($grade) && $grade->reading)
                    <div class="col-lg-4 mt-4">
                        <a href="{{route('lessons', [$grade->id, 'reading'])}}" class="url-box">
                            <figure class='newsCard news-Slide-up '>
                                <img
                                    src="{{asset('steps/reading.png')}}"/>
                                <div class='newsCaption px-4'>
                                    <div class="d-flex align-items-center justify-content-center cnt-title text-center">
                                        <h5 class='newsCaption-title m-0'>
{{--                                            مهارة القراءة--}}
                                        </h5>
                                    </div>
                                    <div class='newsCaption-content justify-content-center d-flex text-center'>
                                        <h5 class="col-10 py-3 px-0">

{{--                                            {{$grade->name}}--}}
                                        </h5>
                                    </div>
                                </div>
                                <span class="overlay"></span>
                            </figure>
                        </a>
                    </div>
                @endif
                @if(isset($grade) && $grade->writing)
                    <div class="col-lg-4 mt-4">
                        <a href="{{route('lessons', [$grade->id, 'writing'])}}" class="url-box">
                            <figure class='newsCard news-Slide-up '>
                                <img
                                    src="{{asset('steps/writing.png')}}"/>
                                <div class='newsCaption px-4'>
                                    <div class="d-flex align-items-center justify-content-center cnt-title text-center">
                                        <h5 class='newsCaption-title m-0'>
{{--                                            مهارة الكتابة--}}
                                        </h5>
                                    </div>
                                    <div class='newsCaption-content justify-content-center d-flex text-center'>
                                        <h5 class="col-10 py-3 px-0">

{{--                                            {{$grade->name}}--}}
                                        </h5>
                                    </div>
                                </div>
                                <span class="overlay"></span>
                            </figure>
                        </a>
                    </div>
                @endif
                @if(isset($grade) && $grade->listening)
                    <div class="col-lg-4 mt-4">
                        <a href="{{route('lessons', [$grade->id, 'listening'])}}" class="url-box">
                            <figure class='newsCard news-Slide-up '>
                                <img
                                    src="{{asset('steps/listening.png')}}"/>
                                <div class='newsCaption px-4'>
                                    <div class="d-flex align-items-center justify-content-center cnt-title text-center">
                                        <h5 class='newsCaption-title m-0'>
{{--                                            مهارة الاستماع--}}
                                        </h5>
                                    </div>
                                    <div class='newsCaption-content justify-content-center d-flex text-center'>
                                        <h5 class="col-10 py-3 px-0">

{{--                                            {{$grade->name}}--}}
                                        </h5>
                                    </div>
                                </div>
                                <span class="overlay"></span>
                            </figure>
                        </a>
                    </div>
                @endif
                @if(isset($grade) && $grade->speaking)
                    <div class="col-lg-4 mt-4">
                        <a href="{{route('lessons', [$grade->id, 'speaking'])}}" class="url-box">
                            <figure class='newsCard news-Slide-up '>
                                <img
                                    src="{{asset('steps/speaking.png')}}"/>
                                <div class='newsCaption px-4'>
                                    <div class="d-flex align-items-center justify-content-center cnt-title text-center">
                                        <h5 class='newsCaption-title m-0'>
{{--                                            مهارة التحدث--}}
                                        </h5>
                                    </div>
                                    <div class='newsCaption-content justify-content-center d-flex text-center'>
                                        <h5 class="col-10 py-3 px-0">

{{--                                            {{$grade->name}}--}}
                                        </h5>
                                    </div>
                                </div>
                                <span class="overlay"></span>
                            </figure>
                        </a>
                    </div>
                @endif
            </div>

            @if(isset($alternate_grade) && !is_null($alternate_grade))
                <hr style="    border-top: 4px solid rgba(0,0,0,.1);"/>
                <h2>
                    <span style="font-weight: bold; color: red">{{$alternate_grade->name}}
                    :
</span>
                </h2>

                <div class="row justify-content-center">
                    @if(isset($alternate_grade) && $alternate_grade->reading)
                        <div class="col-lg-4 mt-4">
                            <a href="{{route('lessons', [$alternate_grade->id, 'reading'])}}" class="url-box">
                                <figure class='newsCard news-Slide-up '>
                                    <img
                                        src="{{asset('steps/reading.png')}}"/>
                                    <div class='newsCaption px-4'>
                                        <div
                                            class="d-flex align-items-center justify-content-center cnt-title text-center">
                                            <h5 class='newsCaption-title m-0'>
{{--                                                مهارة القراءة--}}
                                            </h5>
                                        </div>
                                        <div class='newsCaption-content justify-content-center d-flex text-center'>
                                            <h5 class="col-10 py-3 px-0">

{{--                                                {{$alternate_grade->name}}--}}
                                            </h5>
                                        </div>
                                    </div>
                                    <span class="overlay"></span>
                                </figure>
                            </a>
                        </div>
                    @endif
                    @if(isset($alternate_grade) && $alternate_grade->writing)
                        <div class="col-lg-4 mt-4">
                            <a href="{{route('lessons', [$alternate_grade->id, 'writing'])}}" class="url-box">
                                <figure class='newsCard news-Slide-up '>
                                    <img
                                        src="{{asset('steps/writing.png')}}"/>
                                    <div class='newsCaption px-4'>
                                        <div
                                            class="d-flex align-items-center justify-content-center cnt-title text-center">
                                            <h5 class='newsCaption-title m-0'>
{{--                                                مهارة الكتابة--}}
                                            </h5>
                                        </div>
                                        <div class='newsCaption-content justify-content-center d-flex text-center'>
                                            <h5 class="col-10 py-3 px-0">

{{--                                                {{$alternate_grade->name}}--}}
                                            </h5>
                                        </div>
                                    </div>
                                    <span class="overlay"></span>
                                </figure>
                            </a>
                        </div>
                    @endif
                    @if(isset($alternate_grade) && $alternate_grade->listening)
                        <div class="col-lg-4 mt-4">
                            <a href="{{route('lessons', [$alternate_grade->id, 'listening'])}}" class="url-box">
                                <figure class='newsCard news-Slide-up '>
                                    <img
                                        src="{{asset('steps/listening.png')}}"/>
                                    <div class='newsCaption px-4'>
                                        <div
                                            class="d-flex align-items-center justify-content-center cnt-title text-center">
                                            <h5 class='newsCaption-title m-0'>
{{--                                                مهارة الاستماع--}}
                                            </h5>
                                        </div>
                                        <div class='newsCaption-content justify-content-center d-flex text-center'>
                                            <h5 class="col-10 py-3 px-0">

{{--                                                {{$alternate_grade->name}}--}}
                                            </h5>
                                        </div>
                                    </div>
                                    <span class="overlay"></span>
                                </figure>
                            </a>
                        </div>
                    @endif
                    @if(isset($alternate_grade) && $alternate_grade->speaking)
                        <div class="col-lg-4 mt-4">
                            <a href="{{route('lessons', [$alternate_grade->id, 'speaking'])}}" class="url-box">
                                <figure class='newsCard news-Slide-up '>
                                    <img
                                        src="{{asset('steps/speaking.png')}}"/>
                                    <div class='newsCaption px-4'>
                                        <div
                                            class="d-flex align-items-center justify-content-center cnt-title text-center">
                                            <h5 class='newsCaption-title m-0'>
{{--                                                مهارة التحدث--}}
                                            </h5>
                                        </div>
                                        <div class='newsCaption-content justify-content-center d-flex text-center'>
                                            <h5 class="col-10 py-3 px-0">

{{--                                                {{$alternate_grade->name}}--}}
                                            </h5>
                                        </div>
                                    </div>
                                    <span class="overlay"></span>
                                </figure>
                            </a>
                        </div>
                    @endif
                </div>

            @endif
        </section>
    </section>
@endsection
