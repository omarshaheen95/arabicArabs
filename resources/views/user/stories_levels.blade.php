@extends('user.layout.container_v2')
@section('style')

@endsection
@section('content')
    <section class="login-home user-home level-section">
        <div class="container">
            <div class="row justify-content-center">
                @foreach($levels as $level)
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <a href="{{route('stories.list', $level)}}" class="level-box">
                            <div class="pic">
                                <img src="{{asset("web_assets/img/levels/$level.svg")}}?v=2" alt="">
                            </div>
                            <div class="content">
                                <div class="title">
                                    المستوى {{$level != 15 ? $level : 'التأسيسي'}}

                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
