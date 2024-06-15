{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('title')
    {{$title}}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form action="{{ isset($package) ? route('manager.package.update', $package->id): route('manager.package.store') }}"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        @csrf
        @if(isset($package))
            @method('PATCH')
        @endif
        <div class="row">
            <div class="row">
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Name ')}}</label>
                        <input type="text" id="" name="name" class="form-control" placeholder="{{t('Name')}}" value="{{ isset($package) ? $package->name : old("name") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="days" class="form-label">{{t('Days')}}</label>
                        <input type="number" id="days" name="days" class="form-control" placeholder="{{t('Days')}}"
                               value="{{ isset($package) ? $package->days : old("color") }}" required>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="price" class="form-label">{{t('Price')}}</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="{{t('Price')}}"
                               value="{{ isset($package) ? $package->price : old("price") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" {{isset($package) && $package->active ? 'checked':''}}/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            {{t('Active')}}
                        </label>
                    </div>
                </div>


            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2">{{isset($package)?t('Update'):t('Save')}}</button>
                </div>
            </div>
        </div>

    </form>


@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\PackageRequest::class, '#form-data'); !!}
@endsection
