{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}

@extends('user.layout.container_v2')
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('lessons', $lesson->level_id) }}" @if(isset($level) && !is_null($level->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold" @endif>{{ t('Lessons') }}</a>
    </li>
@endpush
@section('content')
    <section class="inner-page">
        <section>
            <div class="container ">
                <div class="row">
                    <div class="col-12 justify-content-center">
                        <div class="card mb-4 border-0">
                            <div class="card-body pb-0 text-center">
                                @if(!$game)
                                    <div class="card-header bg-white text-center font-weight-bold">
                                        <h5 class="w-100 text-center" style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;"> لا يوجد لعبة متاحة لهذا الدرس - There is no game available for this lesson</h5>
                                    </div>
                                @else
                                    <iframe src="{{asset($game->game)}}" style="width: 100%; min-height: 1000px">
                                    </iframe>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

@section('script')

    <script>

        $(document).ready(function(){
            setTimeout(function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '{{route('track_lesson', [$lesson->id, 'play'])}}';
                $.ajax({
                    url : url,
                    type: 'POST',
                    data : {
                        '_token': csrf,
                    },
                    success: function (data) {
                    },
                    error: function(errMsg) {
                    }
                });

            }, 10000);
        });

    </script>
@endsection
