{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
--}}
@extends('teacher.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .text-red{
            color: #FF0000;
        }
        .students .bootstrap-select .dropdown-menu.inner > li.selected > a {
            background: #03be1d;
        }
        .students .bootstrap-select .dropdown-menu.inner > li.selected > a .text, .students .bootstrap-select .dropdown-menu.inner > li:hover > a .text {
            color: #ffffff;
        }
        .students .bootstrap-select .dropdown-menu.inner > li.selected > a, .students .bootstrap-select .dropdown-menu.inner > li:hover > a {
            background: #03be1d;
        }
    </style>
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
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button data-toggle="modal" data-target="#assignmentModel" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    إضافة واجب
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
{{--                    <form class="kt-form kt-form--fit kt-margin-b-20" action="" method="post">--}}
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>اسم الطالب:</label>
                                <input type="text" name="username" id="username" class="form-control kt-input" placeholder="اسم الطالب">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الصف:</label>
                                <select class="form-control grade" name="grade" id="grade">
                                    <option selected value="">الصف</option>
                                    <option value="1" >الصف 1</option>
                                    <option value="2" >الصف 2</option>
                                    <option value="3" >الصف 3</option>
                                    <option value="4" >الصف 4</option>
                                    <option value="5" >الصف 5</option>
                                    <option value="6" >الصف 6</option>
                                    <option value="7" >الصف 7</option>
                                    <option value="8" >الصف 8</option>
                                    <option value="9" >الصف 9</option>
                                    <option value="10" >الصف 10</option>
                                    <option value="11" >الصف 11</option>
                                    <option value="12" >الصف 12</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الدرس:</label>
                                <select class="form-control package" name="lesson_id" id="lesson_id">
                                    <option selected value="">اختر درس</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الحالة:</label>
                                <select class="form-control" name="status" id="status">
                                    <option selected value="">الكل</option>
                                    <option value="1">مكتمل</option>
                                    <option value="2">غير مكتمل</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>من تاريخ:</label>
                                <input class="form-control date" name="start_at" id="start_at" type="text" placeholder="من تاريخ">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>إلى تاريخ:</label>
                                <input class="form-control date" name="end_at" id="end_at" type="text" placeholder="إلى تاريخ">
                            </div>

                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الإجراءات:</label>
                                <br />
                                <button type="button" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    بحث
                                </button>
                                &nbsp;&nbsp;

                            </div>
                        </div>
{{--                    </form>--}}
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>الطالب</th>
                        <th>الدرس</th>
                        <th>الصف</th>
                        <th>إسناد واجب ( اختبار )	</th>
                        <th>الحالة</th>
                        <th>قدم في</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="assignmentModel" tabindex="-1" role="dialog" aria-labelledby="assignmentModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">إضافة واجب</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" id="form_information" action="{{route('teacher.student_assignments.store')}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">الصف</label>
                            <div class="col-lg-9 col-xl-9">
                                <select class="form-control assignment_grade" name="assignment_grade" id="assignment_grade">
                                    <option selected value="">الصف</option>
                                    <option value="1" >الصف 1</option>
                                    <option value="2" >الصف 2</option>
                                    <option value="3" >الصف 3</option>
                                    <option value="4" >الصف 4</option>
                                    <option value="5" >الصف 5</option>
                                    <option value="6" >الصف 6</option>
                                    <option value="7" >الصف 7</option>
                                    <option value="8" >الصف 8</option>
                                    <option value="9" >الصف 9</option>
                                    <option value="10" >الصف 10</option>
                                    <option value="11" >الصف 11</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">الدرس</label>
                            <div class="col-lg-9 col-xl-9">
                                <select class="form-control assignment_lesson" name="assignment_lesson" id="assignment_lesson">
                                    <option selected value="">اختر درس</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">الشعبة</label>
                            <div class="col-lg-9 col-xl-9">
                                <select class="form-control" name="section" id="section">
                                    <option selected value="">اختر شعبة</option>
                                    @foreach(schoolSections(Auth::guard('teacher')->user()->school_id) as $section)
                                        <option value="{{$section}}">{{$section}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">الطلاب</label>
                            <div class="col-lg-9 col-xl-9 students">
                                <select class="form-control assignment_students" multiple name="assignment_students[]" id="assignment_students">
                                    <option selected value="">الكل</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
{{--                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Tasks assignment') }}</label>--}}
{{--                            <div class="col-lg-3 col-xl-3">--}}
{{--                                <span class="kt-switch">--}}
{{--                                    <label>--}}
{{--                                    <input type="checkbox" checked value="1" name="tasks_assignment">--}}
{{--                                    <span></span>--}}
{{--                                    </label>--}}
{{--                                </span>--}}
{{--                            </div>--}}
                            <label class="col-xl-3 col-lg-3 col-form-label">إسناد واجب اختبار</label>
                            <div class="col-lg-3 col-xl-3">
                                <span class="kt-switch">
                                    <label>
                                    <input type="checkbox" checked value="1" name="test_assignment">
                                    <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Teacher\UserAssignmentRequest::class, '#form_information'); !!}
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
                        url : '{{ route('teacher.student_assignments.index') }}',
                        data: function (d) {
                            d.grade = $("#grade").val();
                            d.level_id = $("#level_id").val();
                            d.lesson_id = $("#lesson_id").val();
                            d.username = $("#username").val();
                            d.start_at = $("#start_at").val();
                            d.end_at = $("#end_at").val();
                            d.status = $("#status").val();
                        }
                    },
                    columns: [
                        {data: 'user', name: 'user'},
                        {data: 'lesson', name: 'lesson'},
                        {data: 'grade', name: 'grade'},
                        // {data: 'done_tasks_assignment', name: 'done_tasks_assignment'},
                        {data: 'done_test_assignment', name: 'done_test_assignment'},
                        {data: 'completed', name: 'completed'},
                        {data: 'created_at', name: 'created_at'},
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

            $('select[name="assignment_grade"]').change(function () {
                var id = $(this).val();
                var url = '{{ route("teacher.getLessonsByGrade", ":id") }}';
                url = url.replace(':id', id );
                var students_url = '{{ route("teacher.getStudentsByGrade", ":id") }}';
                students_url = students_url.replace(':id', id );
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    $('select[name="assignment_lesson"]').html(data.html);
                    $('select[name="assignment_lesson"]').selectpicker('refresh');
                });
                $.ajax({
                    type: "get",
                    url: students_url,
                }).done(function (student_data) {
                    $('select[name="assignment_students[]"]').html(student_data.html);
                    $('select[name="assignment_students[]"]').selectpicker('refresh');
                    // $('#assignment_students option').attr("selected","selected");
                    $('#assignment_students').selectpicker('selectAll');
                    $('#assignment_students').selectpicker('refresh');
                });
            });
            $('select[name="section"]').change(function () {
                var grade = $('select[name="assignment_grade"]').val();
                var students_url = '{{ route("teacher.getStudentsByGrade", ":id") }}';
                students_url = students_url.replace(':id', grade );
                var section = $(this).val();
                $.ajax({
                    type: "get",
                    url: students_url,
                    data : {
                        section:  section,
                    },
                }).done(function (student_data) {
                    $('select[name="assignment_students[]"]').html(student_data.html);
                    $('select[name="assignment_students[]"]').selectpicker('refresh');
                    $('#assignment_students').selectpicker('selectAll');
                    $('#assignment_students').selectpicker('refresh');
                });
            });
            var all_option = true;
            $("#assignment_students").on("changed.bs.select",
                function(e, clickedIndex, newValue, oldValue) {
                if (clickedIndex == 0)
                {
                    if (newValue == false)
                    {
                        all_option = false;
                    }else{
                        all_option = true;
                    }
                    if (all_option)
                    {
                        console.log('all_option is true');
                        // $('#assignment_students option').attr("selected","selected");
                        $('#assignment_students').selectpicker('selectAll');
                        $('#assignment_students').selectpicker('refresh');
                    }else{
                        console.log('all_option is false');
                        // $('#assignment_students option').attr("selected",false);
                        $('#assignment_students').selectpicker('deselectAll');
                        $('#assignment_students').selectpicker('refresh');
                    }
                }
                console.log($(this).val());
                // else{
                //     if (newValue == false)
                //     {
                //         $('#assignment_students option:first').attr("selected",false);
                //         $('#assignment_students').selectpicker('refresh');
                //
                //         // console.log( $('#assignment_students option:first').is('selected'));
                //         // console.log('index : ' + clickedIndex + ' new value : '  + newValue);
                //     }
                // }

                });

            $(".assignment_students option:first").click(function(){
                alert('select first');
                if ($($(this).is(':selected'))) {
                    $('#assignment_students option').prop('selected', true);
                }else{
                    $('#assignment_students option').prop('selected', true);
                }

                $('select[name="assignment_students[]"]').selectpicker('refresh');
            });
        });
    </script>
@endsection