@extends('manager.layout.container')

@section('title')
    {{$title}}
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item text-muted">
        <a href="{{route('manager.import_files.index')}}" class="text-muted">
            {{t('Student Import Files')}}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    @if($type == 'Teacher')
        <form action="{{ route('manager.import_files.store') }}"
              method="post" class="form" id="form-data" enctype="multipart/form-data">
            <input name="type" value="Teacher" type="hidden">
            @csrf
            <div class="row">
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">{{t('Select the file containing the data')}}</label>
                        <div class="col-6">
                            <input type="file" class="form-control" name="import_file">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">{{t('School')}}</label>
                        <div class="col-6">
                            <select name="school_id" class="form-control form-select" data-control="select2"
                                    data-allow-clear="true" data-placeholder="{{t('Select School')}}">
                                <option value="" disabled selected>{{t('Select School')}}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">{{t('Active To')}}</label>
                        <div class="col-6">
                            <input class="form-control" name="active_to" type="text"
                                   value="{{ isset($user) ? $user->active_to : old("active_to") }}" id="active_to_date" placeholder="{{t('Active To')}}">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">{{t('Email Ends With')}}</label>
                        <div class="col-6">
                            <input class="form-control" name="last_of_email" type="text"
                                   value="@arabic-arabs.com" dir="ltr" id="last_of_email" placeholder="{{t('Email Ends With')}}">
                        </div>
                    </div>

                <div class="d-flex align-items-center mt-8">
                    <h5 class="m-0 mr-2">{{t('Note')}}: </h5>
                    <p class="m-0">{{$note}}</p>
                </div>
                <div class="d-flex align-items-center my-2">

                    <h5 class="m-0">{{t('Download Sample of Teachers file')}} : <a class="text-danger" target="_blank" href="{{asset('assets_v1/import_example/Teachers Example.xlsx')}}"> {{t('Click to Download')}}</a></h5>
                </div>
                <div class="row my-5">
                    <div class="separator separator-content my-4"></div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit"
                                class="btn btn-primary mr-2">{{t('Import')}}</button>
                    </div>
                </div>
            </div>

        </form>
    @else
        <div class="row">
            <form class="kt-form kt-form--fit mb-15" id="upload_file"
                  method="POST" action="{{route('manager.import_files.store')}}" enctype="multipart/form-data">
                <input type="hidden" name="type" value="User">
                @csrf
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Status')}}</label>
                    <div class="col-6">
                        <select name="process_type" class="form-control form-select" data-control="select2"
                                data-placeholder="{{t('Select Status')}}">
                            @foreach(['create', 'update', 'delete'] as $status)
                                <option value="{{ $status }}" @if($loop->first) selected @endif>{{ t(ucfirst($status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Select the file containing the data')}}</label>
                    <div class="col-6">
                        <input type="file" class="form-control" name="import_file">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('School')}}</label>
                    <div class="col-6">
                        <select name="school_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="{{t('Select School')}}">
                            <option value="" disabled selected>{{t('Select School')}}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Year')}}</label>
                    <div class="col-6">
                        <select name="year_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="{{t('Select Year')}}">
                            <option value="" disabled selected>{{t('Select Year')}}</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Package')}}</label>
                    <div class="col-6">
                        <select name="package_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="{{t('Select Package')}}">
                            <option value="" disabled selected>{{t('Select Package')}}</option>
                            @foreach($packages as $package)
                                <option data-days="{{$package->days}}" value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Active To')}}</label>
                    <div class="col-6">
                        <input class="form-control" name="active_to" type="text"
                               value="{{ isset($user) ? $user->active_to : old("active_to") }}" id="active_to_date" placeholder="{{t('Active To')}}">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Back Grade')}}</label>
                    <div class="col-6">
                        <select name="back_grade" class="form-control form-select" data-control="select2"
                                data-placeholder="{{t('Select Status')}}">
                            @foreach([0, 1, 2] as $grade)
                                <option value="{{ $grade }}" @if($loop->first) selected @endif>{{ $grade }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Email Ends With')}}</label>
                    <div class="col-6">
                        <input class="form-control" name="last_of_email" type="text"
                               value="@arabic-arabs.com" dir="ltr" id="last_of_email" placeholder="{{t('Email Ends With')}}">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{t('Email Structure')}}</label>
                    <div class="col-6">
                        <select name="email_structure" class="form-control form-select" data-control="select2"
                                data-placeholder="{{t('Select Structure')}}">
                                <option value="use_name" selected>{{ t('Use Name') }}</option>
                                <option value="use_student_id" >{{ t('Use Student ID') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{ t('Default mobile') }}</label>
                    <div class="col-6">
                        <input class="form-control" name="default_mobile" type="text"
                               value="+971500000000">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{ t('country code') }}</label>
                    <div class="col-6">
                        <input class="form-control" name="country_code" type="text"
                               value="971">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label">{{ t('short country') }}</label>
                    <div class="col-6">
                        <input class="form-control" name="short_country" type="text"
                               value="ae">
                    </div>
                </div>
                <div class="d-flex align-items-center mt-8">
                    <h5 class="m-0 mr-2">{{t('Note')}}: </h5>
                    <p class="m-0">{{$note}}</p>
                </div>
                <div class="d-flex align-items-center my-2">

                    <h5 class="m-0">{{t('Download Sample of users file')}} : <a class="text-danger" target="_blank" href="{{asset('assets_v1/import_example/Users Example.xlsx')}}"> {{t('Click to Download')}}</a></h5>
                </div>
                <div class="row my-5">
                    <div class="separator separator-content my-4"></div>
                    <div class="col-12 d-flex justify-content-end">
                        <butto onclick="$('#upload_file').submit()" type="submit" class="btn btn-primary mr-2">{{t('Import')}}</butto>
                    </div>
                </div>

            </form>


        </div>
    @endif
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#active_to_date').flatpickr();

            $('select[name="package_id"]').change(function () {
                let days = $(this).find(':selected').data('days');
                let date = new Date();
                date.setDate(date.getDate() + days);
                let formattedDate = date.toISOString().substr(0, 10);
                $('input[name="active_to"]').val(formattedDate);
                $('#active_to_date').flatpickr();
            });
        });
    </script>
@endsection
