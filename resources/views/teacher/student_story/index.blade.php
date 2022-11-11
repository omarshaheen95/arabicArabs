
@extends('teacher.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{$title}}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{$title}}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">

                    </div>

                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="" method="post">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>اسم الطالب:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input" placeholder="اسم الطالب">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الصف:</label>
                                <select class="form-control grade" name="grade" id="grade">
                                    <option selected value="">اختر صف</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>المستوى :</label>
                                <select class="form-control level" name="level" id="level">
                                    <option selected value="">المستوى</option>
                                    <option value="1" >المستوى 1</option>
                                    <option value="2" >المستوى 2</option>
                                    <option value="3" >المستوى 3</option>
                                    <option value="4" >المستوى 4</option>
                                    <option value="5" >المستوى 5</option>
                                    <option value="6" >المستوى 6</option>
                                    <option value="7" >المستوى 7</option>
                                    <option value="8" >المستوى 8</option>
                                    <option value="9" >المستوى 9</option>
{{--                                    <option value="10" >المستوى 10</option>--}}
{{--                                    <option value="11" >المستوى 11</option>--}}
{{--                                    <option value="12" >المستوى 12</option>--}}
                                </select>
                            </div>
                            <input type="hidden" name="teacher_id" value="{{Auth::user()->id}}">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الإجراءات:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    بحث
                                </button>
                            </div>

                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>الطالب</th>
                        <th>الصف</th>
                        <th>القصة</th>
                        <th>المستوى</th>
                        <th>الحالة</th>
                        <th>قدم في</th>
                        <th>الإجراءات</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    dom: `<'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

                    @if(app()->getLocale() == 'ar')
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    @endif
                    ajax: {
                        url : '{{ route('teacher.students_record.index') }}',
                        data: function (d) {
                            d.name = $("#name").val();
                            d.grade = $("#grade").val();
                            d.lesson = $("#lesson").val();
                            d.level = $("#level").val();
                        }
                    },
                    columns: [
                        {data: 'user', name: 'user'},
                        {data: 'grade', name: 'grade'},
                        {data: 'story', name: 'story'},
                        {data: 'level', name: 'level'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'},
                    ],
                });
            });
        });

        $('#kt_search').click(function(e){
            e.preventDefault();
            $('#users-table').DataTable().draw(true);
        });
    </script>
@endsection
