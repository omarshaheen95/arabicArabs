
@extends('teacher.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            اختبارات الطلاب
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            اختبارات الطلاب
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="{{route('teacher.students_tests.export_excel')}}" method="post">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>الاسم:</label>
                                <input type="text" name="username" id="username" class="form-control kt-input" placeholder="الاسم">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>الصف:</label>
                                <select class="form-control grade" name="grade" id="grade">
                                    <option selected value="">اختر صف</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>الدرس:</label>
                                <select class="form-control package" name="lesson_id" id="lesson_id">
                                    <option selected value="">اختر درس</option>
                                </select>
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>من تاريخ:</label>
                                <input class="form-control date" name="start_at" id="start_at" type="text" placeholder="من تاريخ">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>إلى تاريخ:</label>
                                <input class="form-control date" name="end_at" id="end_at" type="text" placeholder="إلى تاريخ">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>الحالة:</label>
                                <select class="form-control" name="status" id="status">
                                    <option selected value="">الحالة</option>
                                    <option value="Pass">ناجح</option>
                                    <option value="Fail">راسب</option>
                                </select>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>حالة التصحيح:</label>
                                <select class="form-control" name="corrected" id="corrected">
                                    <option selected value="">الحالة</option>
                                    <option value="1">مصحح</option>
                                    <option value="2">غير مصحح</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الإجراءات:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    بحث
                                </button>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                    <i class="la la-paper-plane"></i>
                                   تصدير
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>الطالب</th>
                        <th>الدرس</th>
                        <th>الصف</th>
                        <th>الدرجة</th>
                        <th>الحالة</th>
                        <th>تاريخ التقديم</th>
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });

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
                        url : '{{ route('teacher.students_tests.index') }}',
                        data: function (d) {
                            d.grade = $("#grade").val();
                            d.level_id = $("#level_id").val();
                            d.lesson_id = $("#lesson_id").val();
                            d.username = $("#username").val();
                            d.start_at = $("#start_at").val();
                            d.end_at = $("#end_at").val();
                            d.status = $("#status").val();
                            d.corrected = $("#corrected").val();
                        }
                    },
                    columns: [
                        {data: 'user', name: 'user'},
                        {data: 'lesson', name: 'lesson'},
                        {data: 'grade', name: 'grade'},
                        {data: 'total', name: 'total'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'},
                    ],
                });
            });
            $('#kt_search').click(function(e){
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });

            $('select[name="grade"]').change(function () {
                var id = $(this).val();
                var url = '{{ route("teacher.getLessonsByGrade", ":id") }}';
                url = url.replace(':id', id );
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    $('select[name="lesson_id"]').html(data.html);
                    $('select[name="lesson_id"]').selectpicker('refresh');
                });
            });
        });
    </script>
@endsection
