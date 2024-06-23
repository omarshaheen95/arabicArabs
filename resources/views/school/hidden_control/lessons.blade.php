@extends('school.layout.container')

@section('title',$title)


@section('actions')

    <div class="dropdown with-filter checked-visible">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{t('Actions')}}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item d-none checked-visible" href="#!" id="hide_lessons">{{t('Hide')}}</a></li>
            <li><a class="dropdown-item d-none checked-visible" href="#!" id="show_lessons">{{t('Show')}}</a></li>
        </ul>
    </div>

@endsection

@section('filter')
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Grade')}} :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Grade')}}" data-allow-clear="true">
                <option></option>
                @foreach($grades as $grade)
                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Lesson')}} :</label>
            <select  name="id" id="lesson_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Lesson')}}" data-allow-clear="true">
                <option></option>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2">{{t('Status')}} :</label>
            <select  name="hidden_status" id="hidden_status" class="form-select" data-control="select2" data-placeholder="{{t('Select Status')}}" data-allow-clear="true">
                <option></option>
                <option value="1">{{t('Active')}}</option>
                <option value="2">{{t('Hidden')}}</option>
            </select>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{$title}}
    </li>
@endpush


@section('content')
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start">{{ t('Lesson') }}</th>
                <th class="text-start">{{ t('Level') }}</th>
                <th class="text-start">{{ t('Grade') }}</th>
                <th class="text-start">{{ t('Status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

@endsection


@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}?v={{time()}}"></script>

    <script>
        var TABLE_URL = "{{route('school.lessons.index')}}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'lesson', name: 'lesson'},
            {data: 'grade', name: 'grade'},
            {data: 'status', name: 'status'},
        ];


        $('#hide_lessons').on('click',function () {
            showLoadingModal()
            $.ajax({
                type: "POST",
                url: '{{ route("school.lessons.hide") }}',
                data:{
                    '_token':'{{csrf_token()}}',
                    lessons_ids:getSelectedRows()
                },
                success:function (response) {
                    hideLoadingModal()
                    toastr.success(response.message)
                    table.DataTable().draw(true);
                },
                error:function (error) {
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message)
                }
            })
        });
        $('#show_lessons').on('click',function () {
            showLoadingModal()
            $.ajax({
                type: "POST",
                url: '{{ route("school.lessons.show") }}',
                data:{
                    '_token':'{{csrf_token()}}',
                    lessons_ids:getSelectedRows()
                },
                success:function (response) {
                    hideLoadingModal()
                    toastr.success(response.message)
                    table.DataTable().draw(true);
                },
                error:function (error) {
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message)
                }
            })
        });

     getLessonsByGrade()

    </script>
    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>

@endsection


