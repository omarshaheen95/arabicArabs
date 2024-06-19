@extends('teacher.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('teacher.stories_records.update', $user_record->id) }}" method="post">
        @csrf
        @if(isset($user_record))
            @method('PATCH')
        @endif
        <div class="row gap-3">
            <div class="form-group row  align-items-center">
                <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Record answer') }}</label>
                <div class="col-lg-9 d-flex align-items-center">
                    <audio src="{{asset($user_record->record)}}" controls></audio>

                    <div class="ms-auto d-flex flex-column" style="width: 130px">
                        <div>{{t('Mark')}}:</div>
                        <input class="form-control" name="mark" type="number" placeholder="{{t('Mark')}}"
                               value="{{ $user_record->mark }}" min="0" max="10">
                    </div>

                </div>

            </div>
            <div class="col-12 row align-items-center">
                <label class="col-3">{{ t('Status') }}</label>
                <div class="col-6">
                    <div class="d-flex gap-2 p-4 border-secondary ms-1" style="border: 1px solid;border-radius: 5px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="pending" @if($user_record->status == 'pending') checked @endif>
                            <label class="form-check-label" for="section">{{t(ucfirst('Waiting list'))}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="corrected"  @if($user_record->status == 'corrected') checked @endif>
                            <label class="form-check-label" for="section">{{t(ucfirst('Marking Completed'))}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="returned" @if($user_record->status == 'returned') checked @endif>
                            <label class="form-check-label" for="section">{{t(ucfirst('Send back'))}}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 row align-items-center">
                <label class="col-3">{{ t('Show as model') }}</label>
                <div class="col-6">
                    <div class="d-flex gap-2 p-4 border-secondary ms-1" style="border: 1px solid;border-radius: 5px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" value="0"  @if(!$user_record->approved) checked @endif>
                            <label class="form-check-label" for="section">{{t('Do not Show as model')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" value="1" @if($user_record->approved) checked @endif>
                            <label class="form-check-label" for="section">{{t('Show as model')}}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator mt-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\UpdateUserRecordRequest::class, '#form_information'); !!}


@endsection
