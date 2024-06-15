
@extends('teacher.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            الطلاب
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            الطلاب
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button id="users_assign" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    إسناد
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="" method="post">
                        {{csrf_field()}}
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>الاسم:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input" placeholder="الاسم">
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
                                <label>الشعبة:</label>
                                <select class="form-control" name="section" id="section">
                                    <option selected value="">الشعبة</option>
                                    @foreach(schoolSections(Auth::guard('teacher')->user()->school_id) as $section)
                                        <option value="{{$section}}">{{$section}}</option>
                                    @endforeach
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
{{--                                <button type="submit" class="btn btn-secondary btn-secondary--icon" id="kt_reset">--}}
{{--                                    <i class="la la-paper-plane"></i>--}}
{{--                                    {{t('Excel')}}--}}
{{--                                </button>--}}
                            </div>

                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th><input type="checkbox" class='checkall' id='checkall'></th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الصف</th>
                        <th>الشعبة</th>
                        <th>فعال حتى</th>
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
                        url : '{{ route('teacher.students_school.index') }}',
                        data: function (d) {
                            d.name = $("#name").val();
                            d.grade = $("#grade").val();
                            d.section = $("#section").val();
                        }
                    },
                    columns: [
                        {data: 'check', name: 'check'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'grade', name: 'grade'},
                        {data: 'section', name: 'section'},
                        {data: 'active_to', name: 'active_to'},
                    ],
                });
            });


            // Check all
            $('#checkall').click(function(){
                if($(this).is(':checked')){
                    $('.user_id').prop('checked', true);
                }else{
                    $('.user_id').prop('checked', false);
                }
            });

            // Delete record
            $(document).on('click', '#users_assign',function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var user_id = [];
                // Read all checked checkboxes
                $("input:checkbox[class=user_id]:checked").each(function () {
                    user_id.push($(this).val());
                });

                // Check checkbox checked or not
                if(user_id.length > 0){

                    // Confirm alert
                    var confirmdelete = confirm("هل أنت متأكد من إسناد الطلاب المحددين");
                    if (confirmdelete == true) {
                        $.ajax({
                            url: "{{route('teacher.student.student_assign')}}",
                            type: 'post',
                            data: {
                                '_token': csrf,
                                user_id: user_id,
                            },
                            success: function(response){
                                $('#users-table').DataTable().draw(true);
                                toastr.success(response.message);
                                $('#checkall').prop('checked', false);
                            }
                        });
                    }
                }
            });
        });
        $('#kt_search').click(function(e){
            e.preventDefault();
            $('#users-table').DataTable().draw(true);
        });
        //jQuery detect user pressing enter
        $(document).on('keypress',function(e) {
            if(e.which == 13) {
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            }
        });

        {{--$('#kt_excel').click(function(e){--}}
        {{--    e.preventDefault();--}}
        {{--    $("#filter").attr("method",'post');--}}
        {{--    $("#filter").attr("action",'{{route('teacher.user.export_students_excel')}}');--}}
        {{--    $('#filter').submit();--}}

        {{--    $("#filter").attr("method",'');--}}
        {{--    $("#filter").attr("action",'');--}}
        {{--});--}}
    </script>
@endsection
