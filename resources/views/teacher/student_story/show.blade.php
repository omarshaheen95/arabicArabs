{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('teacher.layout.container')
@section('style')
    <style>
        .kt-radio > span {
            width: 25px;
            height: 25px;
        }

        label.kt-radio {
            font-size: 18px;
        }

        .kt-radio > span:after {
            margin-right: -5px;
            margin-top: -5px;
            width: 10px;
            height: 10px;
        }
    </style>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('teacher.students_record.index') }}">{{t('Students Stories Record')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ $title }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"> {{ $title }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ route('teacher.students_record.update', $user_record->id) }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Record answer') }}</label>
                                    <div class="col-lg-9">
                                        <audio src="{{asset($user_record->record)}}" controls></audio>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mark') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="mark" type="number"
                                               value="{{ $user_record->mark }}">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Status') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="kt-radio-list">
                                            <label class="kt-radio kt-radio--bold kt-radio--disabled ">
                                                <input type="radio" disabled="disabled" value="" name="status"
                                                       @if($user_record->status == 'pending') checked @endif> {{t(ucfirst('Waiting list'))}}
                                                <span></span>
                                            </label>
                                            <label class="kt-radio kt-radio--bold kt-radio--success">
                                                <input type="radio" name="status" value="corrected"
                                                       @if($user_record->status == 'corrected') checked @endif> {{t(ucfirst('Marking Completed'))}}
                                                <span></span>
                                            </label>
                                            <label class="kt-radio kt-radio--bold kt-radio--brand">
                                                <input type="radio" name="status" value="returned"
                                                       @if($user_record->status == 'returned') checked @endif> {{t(ucfirst('Send back'))}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Show as model') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="kt-radio-list">
                                            <label class="kt-radio kt-radio--bold kt-radio--warning">
                                                <input type="radio" name="approved" value="0"
                                                       @if($user_record->approved == 0) checked @endif> {{t('Do not Show as model')}}
                                                <span></span>
                                            </label>
                                            <label class="kt-radio kt-radio--bold kt-radio--success">
                                                <input type="radio" name="approved" value="1"
                                                       @if($user_record->approved == 1) checked @endif> {{t('Show as model')}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="save" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>


    <script>

    </script>

@endsection
