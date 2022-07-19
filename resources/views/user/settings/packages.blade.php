{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container')
@section('style')

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            Subscription upgrade
        </li>
    @endpush
    <section class="inner-page">
        <section>
            <div class="card mt-3 mb-4 border-0">
                <div class="card-header bg-white text-center">
                    <h4 style="font-weight: bold">
                        Subscription upgrade
                    </h4>
                </div>
                <form enctype="multipart/form-data" id="form_information" action="{{ route('post_package_upgrade') }}"
                      method="post">
                    @csrf
                    <div class="card-body pb-0">
                        <div class="form-group row">
                            <label class="col-xl-1"></label>
                            <label class="col-xl-3 col-lg-3 col-form-label">Grade</label>
                            <div class="col-xl-6">
                                <select class="form-control" name="grade" required>
                                    <option value="" selected disabled>Select Grade</option>
                                    @foreach($grades as $grade)
                                        @if($grade == 15)
                                        <option value="{{$grade}}" {{isset($user) && $user->grade == $grade ? 'selected':''}}>KG 2 / Year 1</option>
                                        @else
                                        <option value="{{$grade}}" {{isset($user) && $user->grade == $grade ? 'selected':''}}>Grade {{$grade}} / Year {{$grade+1}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-1"></label>
                            <label class="col-xl-3 col-lg-3 col-form-label">Package</label>
                            <div class="col-xl-6">
                                <select class="form-control" name="package_id" required>
                                    <option value="" selected disabled>Select Package</option>
                                    @foreach($packages as $package)
                                        <option value="{{$package->id}}" {{isset($user) && $user->package_id == $package->id ? 'selected':''}}>
                                            {{$package->name}}  - {{$package->price}}$ - {{$package->days}} Days
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="mt-4 card-footer bg-white text-left">
                        <button type="submit" class="btn btn-danger">{{t('Complete & upgrade')}}</button>
                    </div>
                </form>
            </div>
        </section>
    </section>
@endsection

@section('script')
{{--    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>--}}
{{--    {!! $validator->selector('#form_information') !!}--}}
@endsection
